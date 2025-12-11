<?php

namespace App\Http\Controllers;

use App\Models\TripCart;
use App\Models\Tourism;
use App\Models\DistanceCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItineraryController extends Controller
{
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil semua item trip cart dengan data tourism
        $tripCartItems = TripCart::where('user_id', Auth::id())
            ->with(['tourism.files', 'tourism.prices', 'tourism.categories', 'tourism.hours'])
            ->get();

        return view('itinerary.create', compact('tripCartItems'));
    }

    /**
     * Generate itinerary using TSP with Dynamic Programming
     */
    public function generate(Request $request)
    {
        // Increase execution time for complex TSP calculations
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'travel_date' => 'required|date',
            'start_point_type' => 'required|in:from_cart,custom',
            'start_tourism_id' => 'required_if:start_point_type,from_cart|nullable|exists:tourism,id',
            'start_lat' => 'required_if:start_point_type,custom|nullable|numeric',
            'start_long' => 'required_if:start_point_type,custom|nullable|numeric',
        ]);

        // Get all tourism destinations from trip cart
        $tripCartItems = TripCart::where('user_id', Auth::id())
            ->with('tourism')
            ->get();

        if ($tripCartItems->count() == 0) {
            return response()->json(['success' => false, 'message' => 'Trip cart kosong!'], 400);
        }

        // Prepare starting point
        $startPoint = [];
        if ($request->start_point_type === 'from_cart') {
            $startTourism = Tourism::findOrFail($request->start_tourism_id);
            $startPoint = [
                'id' => $startTourism->id,
                'name' => $startTourism->name,
                'lat' => $startTourism->latitude,
                'long' => $startTourism->longitude,
                'type' => 'tourism'
            ];
        } else {
            $startPoint = [
                'id' => 0, // Custom location
                'name' => 'Lokasi Awal',
                'lat' => $request->start_lat,
                'long' => $request->start_long,
                'type' => 'custom'
            ];
        }

        // Get all destinations (exclude starting point if from cart)
        $destinations = [];
        foreach ($tripCartItems as $item) {
            if ($startPoint['type'] === 'tourism' && $item->tourism_id == $startPoint['id']) {
                continue; // Skip starting point
            }

            // Load tourism hours relationship
            $item->tourism->load('hours');

            $destinations[] = [
                'id' => $item->tourism_id,
                'name' => $item->tourism->name,
                'lat' => $item->tourism->latitude,
                'long' => $item->tourism->longitude,
                'tourism' => $item->tourism
            ];
        }

        if (count($destinations) == 0) {
            return response()->json(['success' => false, 'message' => 'Tambahkan minimal 1 destinasi selain titik awal!'], 400);
        }

        // Build distance matrix
        $distanceMatrix = $this->buildDistanceMatrix($startPoint, $destinations);

        // Solve TSP using Dynamic Programming
        $optimalRoute = $this->solveTSP($distanceMatrix, count($destinations));

        // Build final itinerary
        $itinerary = $this->buildItinerary($startPoint, $destinations, $optimalRoute, $distanceMatrix);

        // Get route geometry for map visualization
        $routeGeometry = $this->getRouteGeometry($startPoint, $destinations, $optimalRoute);

        // Store in session for result page
        session([
            'itinerary_data' => [
                'name' => $request->name,
                'travel_date' => $request->travel_date,
                'start_point' => $startPoint,
                'destinations' => $destinations,
                'route' => $itinerary,
                'total_distance' => $itinerary['total_distance'],
                'total_duration' => $itinerary['total_duration'],
                'distance_matrix' => $distanceMatrix,
                'route_geometry' => $routeGeometry,
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Itinerary berhasil dibuat!',
            'redirect_url' => route('itinerary.result')
        ]);
    }

    /**
     * Build distance matrix between all points
     * Optimized with batch cache lookup and batch insert
     */
    private function buildDistanceMatrix($startPoint, $destinations)
    {
        $points = array_merge([$startPoint], $destinations);
        $n = count($points);
        $matrix = array_fill(0, $n, array_fill(0, $n, 0));

        // Step 1: Collect all coordinate pairs that need to be checked
        $pairs = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i == $j) {
                    continue;
                }
                $pairs[] = [
                    'i' => $i,
                    'j' => $j,
                    'from' => $points[$i],
                    'to' => $points[$j],
                ];
            }
        }

        // Step 2: Batch lookup from cache
        $cacheMap = $this->batchGetDistanceFromCache($pairs);

        // Step 3: Identify missing pairs and calculate them
        $missingPairs = [];
        foreach ($pairs as $pair) {
            $key = $this->makeCacheKey($pair['from'], $pair['to']);
            if (!isset($cacheMap[$key])) {
                $missingPairs[] = $pair;
            }
        }

        // Step 4: Calculate missing distances (if any)
        if (count($missingPairs) > 0) {
            Log::info('Calculating ' . count($missingPairs) . ' missing distances...');
            $newDistances = $this->batchCalculateAndCacheDistances($missingPairs);
            // Merge new distances into cache map
            $cacheMap = array_merge($cacheMap, $newDistances);
        }

        // Step 5: Fill the matrix
        foreach ($pairs as $pair) {
            $key = $this->makeCacheKey($pair['from'], $pair['to']);
            $matrix[$pair['i']][$pair['j']] = $cacheMap[$key] ?? 0;
        }

        return $matrix;
    }

    /**
     * Create a unique key for cache lookup
     */
    private function makeCacheKey($fromPoint, $toPoint)
    {
        return sprintf(
            '%.6f,%.6f->%.6f,%.6f',
            $fromPoint['lat'],
            $fromPoint['long'],
            $toPoint['lat'],
            $toPoint['long']
        );
    }

    /**
     * Batch get distances from cache (single query)
     */
    private function batchGetDistanceFromCache($pairs)
    {
        if (empty($pairs)) {
            return [];
        }

        // Build a single query with multiple OR conditions
        $query = DistanceCache::query();

        foreach ($pairs as $index => $pair) {
            $query->orWhere(function ($q) use ($pair) {
                $q->where('from_lat', $pair['from']['lat'])
                    ->where('from_long', $pair['from']['long'])
                    ->where('to_lat', $pair['to']['lat'])
                    ->where('to_long', $pair['to']['long']);
            });
        }

        $cached = $query->get();

        // Map results by coordinate key
        $cacheMap = [];
        foreach ($cached as $cache) {
            $key = sprintf(
                '%.6f,%.6f->%.6f,%.6f',
                $cache->from_lat,
                $cache->from_long,
                $cache->to_lat,
                $cache->to_long
            );
            $cacheMap[$key] = $cache->distance;
        }

        return $cacheMap;
    }

    /**
     * Calculate and cache distances for missing pairs
     */
    private function batchCalculateAndCacheDistances($pairs)
    {
        $results = [];

        foreach ($pairs as $index => $pair) {
            try {
                $result = $this->calculateDistance(
                    $pair['from']['lat'],
                    $pair['from']['long'],
                    $pair['to']['lat'],
                    $pair['to']['long']
                );

                $distance = (int) $result['distance'];
                $duration = (int) $result['duration'];

                $key = $this->makeCacheKey($pair['from'], $pair['to']);
                $results[$key] = $distance;

                // Insert to cache immediately (individual insert for better duplicate handling)
                try {
                    DistanceCache::create([
                        'from_id' => isset($pair['from']['id']) && $pair['from']['id'] > 0 ? $pair['from']['id'] : null,
                        'to_id' => isset($pair['to']['id']) && $pair['to']['id'] > 0 ? $pair['to']['id'] : null,
                        'from_lat' => $pair['from']['lat'],
                        'from_long' => $pair['from']['long'],
                        'to_lat' => $pair['to']['lat'],
                        'to_long' => $pair['to']['long'],
                        'distance' => $distance,
                        'duration' => $duration,
                    ]);
                } catch (\Exception $cacheError) {
                    // Ignore duplicate cache entries
                    Log::debug('Cache entry already exists, skipping');
                }

                // Add small delay every 5 requests to avoid rate limiting
                if (($index + 1) % 5 == 0) {
                    usleep(200000); // 0.2 seconds
                }
            } catch (\Exception $e) {
                Log::error('Failed to calculate distance: ' . $e->getMessage());
                // Fallback: use straight line distance
                $distance = (int) ($this->calculateDistanceHaversine(
                    $pair['from']['lat'],
                    $pair['from']['long'],
                    $pair['to']['lat'],
                    $pair['to']['long']
                ) * 1000);

                $key = $this->makeCacheKey($pair['from'], $pair['to']);
                $results[$key] = $distance;
            }
        }

        return $results;
    }

    /**
     * Calculate distance using OpenRouteService API (same as TripCartController)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        try {
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0=';

            $url = "https://api.openrouteservice.org/v2/directions/cycling-regular";
            $body = [
                "coordinates" => [
                    [(float)$lon1, (float)$lat1],
                    [(float)$lon2, (float)$lat2]
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: ' . $apiKey,
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/geo+json, application/json'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);

                if (isset($data['routes'][0]['summary'])) {
                    $distanceMeters = $data['routes'][0]['summary']['distance']; // in meters
                    $durationSeconds = $data['routes'][0]['summary']['duration']; // in seconds

                    return [
                        'distance' => $distanceMeters,
                        'duration' => $durationSeconds,
                    ];
                }
            }

            // Fallback
            $distanceKm = $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
            dd("s");
            return [
                'distance' => $distanceKm * 1000,
                'duration' => ($distanceKm) * (3600 / 40)
            ];
        } catch (\Exception $e) {
            $distanceKm = $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
            dd("x");
            return [
                'distance' => $distanceKm * 1000,
                'duration' => ($distanceKm) * (3600 / 40)
            ];
        }
    }

    /**
     * Haversine distance calculation
     */
    private function calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Solve TSP using Dynamic Programming (Held-Karp algorithm)
     */
    private function solveTSP($distanceMatrix, $n)
    {
        // n is number of destinations (excluding start point)
        // Start point is always index 0

        if ($n == 0) return [];
        if ($n == 1) return [1]; // Only one destination

        // DP table: dp[mask][i] = minimum distance to visit cities in mask, ending at city i
        $dp = [];
        $parent = [];

        // Initialize
        for ($mask = 0; $mask < (1 << $n); $mask++) {
            $dp[$mask] = array_fill(0, $n + 1, PHP_INT_MAX);
            $parent[$mask] = array_fill(0, $n + 1, -1);
        }

        // Base case: starting from city 0 (start point) to each destination
        for ($i = 1; $i <= $n; $i++) {
            $dp[1 << ($i - 1)][$i] = $distanceMatrix[0][$i];
        }

        // Fill DP table
        for ($mask = 0; $mask < (1 << $n); $mask++) {
            for ($last = 1; $last <= $n; $last++) {
                if (!($mask & (1 << ($last - 1)))) continue;
                if ($dp[$mask][$last] == PHP_INT_MAX) continue;

                for ($next = 1; $next <= $n; $next++) {
                    if ($mask & (1 << ($next - 1))) continue; // Already visited

                    $newMask = $mask | (1 << ($next - 1));
                    $newDist = $dp[$mask][$last] + $distanceMatrix[$last][$next];

                    if ($newDist < $dp[$newMask][$next]) {
                        $dp[$newMask][$next] = $newDist;
                        $parent[$newMask][$next] = $last;
                    }
                }
            }
        }

        // Find the best ending city
        $fullMask = (1 << $n) - 1;
        $minDist = PHP_INT_MAX;
        $lastCity = -1;

        for ($i = 1; $i <= $n; $i++) {
            $dist = $dp[$fullMask][$i];
            if ($dist < $minDist) {
                $minDist = $dist;
                $lastCity = $i;
            }
        }

        // Reconstruct path
        $path = [];
        $mask = $fullMask;
        $current = $lastCity;

        while ($current != -1) {
            $path[] = $current;
            $prev = $parent[$mask][$current];
            if ($prev != -1) {
                $mask ^= (1 << ($current - 1));
            }
            $current = $prev;
        }

        return array_reverse($path);
    }

    /**
     * Build final itinerary from optimal route
     */
    private function buildItinerary($startPoint, $destinations, $optimalRoute, $distanceMatrix)
    {
        $route = [];
        $totalDistance = 0;
        $totalDuration = 0;
        $currentIndex = 0; // Start point index

        // Add start point
        $route[] = [
            'order' => 0,
            'destination' => $startPoint,
            'distance_from_previous' => 0,
            'duration_from_previous' => 0,
        ];

        // Add destinations in optimal order
        foreach ($optimalRoute as $order => $destIndex) {
            $destination = $destinations[$destIndex - 1]; // -1 because route indices start from 1
            $distance = $distanceMatrix[$currentIndex][$destIndex];

            // Get duration from cache or calculate
            $duration = $this->getDuration($currentIndex == 0 ? $startPoint : $destinations[$currentIndex - 1], $destination);

            $route[] = [
                'order' => $order + 1,
                'destination' => $destination,
                'distance_from_previous' => $distance,
                'duration_from_previous' => $duration,
            ];

            $totalDistance += $distance;
            $totalDuration += $duration;
            $currentIndex = $destIndex;
        }

        return [
            'route' => $route,
            'total_distance' => $totalDistance,
            'total_duration' => $totalDuration,
        ];
    }

    /**
     * Get duration between two points
     */
    private function getDuration($fromPoint, $toPoint)
    {
        // Try to get from cache first using coordinates
        // Only check exact direction (from -> to), NOT reverse direction
        $cache = DistanceCache::where('from_lat', $fromPoint['lat'])
            ->where('from_long', $fromPoint['long'])
            ->where('to_lat', $toPoint['lat'])
            ->where('to_long', $toPoint['long'])
            ->first();

        if ($cache) {
            return $cache->duration;
        }

        // Calculate if not in cache
        $result = $this->calculateDistance(
            $fromPoint['lat'],
            $fromPoint['long'],
            $toPoint['lat'],
            $toPoint['long']
        );

        return $result['duration'];
    }

    /**
     * Get route geometry from OpenRouteService for map visualization
     */
    private function getRouteGeometry($startPoint, $destinations, $optimalRoute)
    {
        try {
            // Build coordinates array in order: [longitude, latitude]
            $coordinates = [];

            // Add start point
            $coordinates[] = [(float)$startPoint['long'], (float)$startPoint['lat']];

            // Add destinations in optimal order
            foreach ($optimalRoute as $destIndex) {
                $destination = $destinations[$destIndex - 1];
                $coordinates[] = [(float)$destination['long'], (float)$destination['lat']];
            }

            // Call OpenRouteService Directions API
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0=';
            $url = "https://api.openrouteservice.org/v2/directions/cycling-regular";

            $postData = json_encode([
                'coordinates' => $coordinates
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Increased timeout to 60 seconds
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8',
                'Authorization: ' . $apiKey
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);

                // OpenRouteService API returns 'routes' array, not 'features'
                if (isset($data['routes'][0]['geometry'])) {
                    $geometryString = $data['routes'][0]['geometry'];

                    // Return encoded polyline to be decoded in JS
                    return [
                        'type' => 'success',
                        'geometry' => $geometryString, // Encoded polyline string
                        'coordinates' => $coordinates,
                        'summary' => isset($data['routes'][0]['summary']) ? $data['routes'][0]['summary'] : null,
                    ];
                }
            }

            Log::warning('Failed to get route geometry from OpenRouteService. HTTP Code: ' . $httpCode);
            if ($response) {
                Log::warning('API Response: ' . substr($response, 0, 500));
            }

            // Fallback: return straight lines
            return [
                'type' => 'fallback',
                'coordinates' => $coordinates,
                'geometry' => null
            ];
        } catch (\Exception $e) {
            Log::error('Error getting route geometry: ' . $e->getMessage());

            // Fallback
            $coordinates = [[$startPoint['long'], $startPoint['lat']]];
            foreach ($optimalRoute as $destIndex) {
                $destination = $destinations[$destIndex - 1];
                $coordinates[] = [$destination['long'], $destination['lat']];
            }

            return [
                'type' => 'fallback',
                'coordinates' => $coordinates,
                'geometry' => null
            ];
        }
    }

    /**
     * Show itinerary result
     */
    public function result()
    {
        if (!session()->has('itinerary_data')) {
            return redirect()->route('itinerary.create')->with('error', 'Data itinerary tidak ditemukan!');
        }

        $itineraryData = session('itinerary_data');

        return view('itinerary.result', compact('itineraryData'));
    }
}
