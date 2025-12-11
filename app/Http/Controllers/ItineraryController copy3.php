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
            'count_days' => 'required|integer|min:1',
            'hotel_lat' => 'required_if:count_days,>,1|nullable|numeric',
            'hotel_long' => 'required_if:count_days,>,1|nullable|numeric',
        ]);

        try {
            // Get trip cart items
            $tripCartItems = TripCart::where('user_id', Auth::id())
                ->with('tourism')
                ->get();

            if ($tripCartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada destinasi di trip cart!'
                ], 400);
            }

            // Prepare start point
            if ($request->start_point_type === 'from_cart') {
                $startTourism = Tourism::find($request->start_tourism_id);
                $startPoint = [
                    'id' => $startTourism->id,
                    'name' => $startTourism->name,
                    'lat' => (float) $startTourism->latitude,
                    'long' => (float) $startTourism->longitude,
                    'type' => 'tourism'
                ];
            } else {
                $startPoint = [
                    'id' => 0,
                    'name' => 'Titik Awal (Custom)',
                    'lat' => (float) $request->start_lat,
                    'long' => (float) $request->start_long,
                    'type' => 'custom'
                ];
            }

            // Prepare destinations (exclude start point if from cart)
            $destinations = [];
            foreach ($tripCartItems as $item) {
                if ($request->start_point_type === 'from_cart' && $item->tourism_id == $request->start_tourism_id) {
                    continue;
                }
                $destinations[] = [
                    'id' => $item->tourism->id,
                    'name' => $item->tourism->name,
                    'lat' => (float) $item->tourism->latitude,
                    'long' => (float) $item->tourism->longitude,
                    'type' => 'tourism',
                    'trip_cart_id' => $item->id
                ];
            }

            if (empty($destinations)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak cukup destinasi untuk generate itinerary!'
                ], 400);
            }

            $countDays = (int) $request->count_days;

            // Generate itinerary based on number of days
            if ($countDays > 1) {
                // Multi-day itinerary with hotel
                $hotelPoint = [
                    'id' => 0,
                    'name' => 'Penginapan',
                    'lat' => (float) $request->hotel_lat,
                    'long' => (float) $request->hotel_long,
                    'type' => 'hotel'
                ];

                $result = $this->generateMultiDayItinerary(
                    $startPoint,
                    $destinations,
                    $hotelPoint,
                    $countDays
                );
            } else {
                // Single day itinerary
                $result = $this->generateSingleDayItinerary($startPoint, $destinations);
            }

            // Store result in session
            session([
                'itinerary_data' => [
                    'name' => $request->name,
                    'travel_date' => $request->travel_date,
                    'count_days' => $countDays,
                    'start_point' => $startPoint,
                    'itinerary' => $result['itinerary'],
                    'total_distance' => $result['total_distance'],
                    'total_duration' => $result['total_duration'],
                    'route_geometry' => $result['route_geometry'] ?? null,
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Itinerary berhasil dibuat!',
                'redirect_url' => route('itinerary.result')
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating itinerary: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate single day itinerary using TSP
     */
    private function generateSingleDayItinerary($startPoint, $destinations)
    {
        // Build distance matrix
        $distanceMatrix = $this->buildDistanceMatrix($startPoint, $destinations);
        
        // Solve TSP using Dynamic Programming
        $optimalRoute = $this->solveTSP($distanceMatrix, count($destinations));
        
        // Build itinerary
        $itinerary = $this->buildItinerary($startPoint, $destinations, $optimalRoute, $distanceMatrix);
        
        // Get route geometry for map
        $routeGeometry = $this->getRouteGeometry($startPoint, $destinations, $optimalRoute);
        
        return [
            'itinerary' => [
                [
                    'day' => 1,
                    'route' => $itinerary['route'],
                    'total_distance' => $itinerary['total_distance'],
                    'total_duration' => $itinerary['total_duration'],
                ]
            ],
            'total_distance' => $itinerary['total_distance'],
            'total_duration' => $itinerary['total_duration'],
            'route_geometry' => $routeGeometry
        ];
    }

    /**
     * Generate multi-day itinerary with hotel stops
     * Uses optimal clustering to determine which destinations to visit each day
     */
    private function generateMultiDayItinerary($startPoint, $destinations, $hotelPoint, $countDays)
    {
        $totalDestinations = count($destinations);
        
        // Find optimal distribution of destinations across days
        $optimalClusters = $this->findOptimalDayClusters(
            $startPoint,
            $destinations,
            $hotelPoint,
            $countDays
        );
        
        $allDaysItinerary = [];
        $totalDistance = 0;
        $totalDuration = 0;
        $allCoordinates = [];
        
        for ($day = 1; $day <= $countDays; $day++) {
            if (!isset($optimalClusters[$day - 1]) || empty($optimalClusters[$day - 1])) {
                break;
            }
            
            $dayDestinations = $optimalClusters[$day - 1];
            
            // Determine start and end point for this day
            if ($day == 1) {
                // First day: original start point → destinations → hotel
                $dayStartPoint = $startPoint;
                $dayEndPoint = $hotelPoint;
            } elseif ($day == $countDays) {
                // Last day: hotel → destinations → end at last destination
                $dayStartPoint = $hotelPoint;
                $dayEndPoint = null; // Will end at last destination
            } else {
                // Middle days: hotel → destinations → hotel
                $dayStartPoint = $hotelPoint;
                $dayEndPoint = $hotelPoint;
            }
            
            // Generate optimal route for this day
            $dayResult = $this->generateDayRoute(
                $dayStartPoint,
                $dayDestinations,
                $dayEndPoint,
                $day
            );
            
            $allDaysItinerary[] = $dayResult;
            $totalDistance += $dayResult['total_distance'];
            $totalDuration += $dayResult['total_duration'];
            
            // Collect coordinates for overall route geometry
            foreach ($dayResult['route'] as $point) {
                $allCoordinates[] = [
                    $point['destination']['long'],
                    $point['destination']['lat']
                ];
            }
        }
        
        return [
            'itinerary' => $allDaysItinerary,
            'total_distance' => $totalDistance,
            'total_duration' => $totalDuration,
            'route_geometry' => [
                'type' => 'multi-day',
                'coordinates' => $allCoordinates,
                'days' => $countDays
            ]
        ];
    }

    /**
     * Find optimal clustering of destinations across multiple days
     * Uses greedy approach to minimize total distance considering hotel location
     */
    private function findOptimalDayClusters($startPoint, $destinations, $hotelPoint, $countDays)
    {
        $totalDestinations = count($destinations);
        $avgPerDay = ceil($totalDestinations / $countDays);
        
        // For 2 days with small number of destinations, use balanced split
        if ($countDays == 2 && $totalDestinations <= 10) {
            return $this->optimizeTwoDaySplit($startPoint, $destinations, $hotelPoint);
        }
        
        // For more complex scenarios, use greedy clustering
        $clusters = array_fill(0, $countDays, []);
        $remainingDestinations = $destinations;
        $currentStartPoint = $startPoint;
        
        for ($day = 0; $day < $countDays; $day++) {
            $isLastDay = ($day == $countDays - 1);
            
            // Calculate how many destinations should be in this day
            $remainingDays = $countDays - $day;
            $remainingCount = count($remainingDestinations);
            $targetCount = ceil($remainingCount / $remainingDays);
            
            if ($isLastDay) {
                // Last day gets all remaining
                $clusters[$day] = $remainingDestinations;
                break;
            }
            
            // Select destinations for this day using greedy approach
            $dayCluster = $this->selectDayDestinations(
                $currentStartPoint,
                $remainingDestinations,
                $hotelPoint,
                $targetCount,
                false // Not last day, must return to hotel
            );
            
            $clusters[$day] = $dayCluster;
            
            // Remove selected destinations from remaining
            $selectedIds = array_map(function($dest) {
                return $dest['id'];
            }, $dayCluster);
            
            $remainingDestinations = array_filter($remainingDestinations, function($dest) use ($selectedIds) {
                return !in_array($dest['id'], $selectedIds);
            });
            
            // Next day starts from hotel
            $currentStartPoint = $hotelPoint;
        }
        
        return $clusters;
    }

    /**
     * Optimize 2-day split using exhaustive search for best distribution
     * Tries different combinations to minimize total distance
     */
    private function optimizeTwoDaySplit($startPoint, $destinations, $hotelPoint)
    {
        $totalCount = count($destinations);
        $bestScore = PHP_INT_MAX;
        $bestSplit = null;
        
        // Try different split ratios
        $minDay1 = max(1, floor($totalCount * 0.3));
        $maxDay1 = min($totalCount - 1, ceil($totalCount * 0.7));
        
        for ($day1Count = $minDay1; $day1Count <= $maxDay1; $day1Count++) {
            // Try several random combinations for this split ratio
            $attempts = min(10, $this->getNumberOfCombinations($totalCount, $day1Count));
            
            for ($attempt = 0; $attempt < $attempts; $attempt++) {
                // Generate a combination
                $indices = range(0, $totalCount - 1);
                shuffle($indices);
                
                $day1Indices = array_slice($indices, 0, $day1Count);
                $day2Indices = array_slice($indices, $day1Count);
                
                $day1Destinations = array_values(array_intersect_key($destinations, array_flip($day1Indices)));
                $day2Destinations = array_values(array_intersect_key($destinations, array_flip($day2Indices)));
                
                // Calculate score for this split
                $score = $this->calculateSplitScore(
                    $startPoint,
                    $day1Destinations,
                    $day2Destinations,
                    $hotelPoint
                );
                
                if ($score < $bestScore) {
                    $bestScore = $score;
                    $bestSplit = [$day1Destinations, $day2Destinations];
                }
            }
        }
        
        // If no better split found, use balanced split
        if ($bestSplit === null) {
            $half = ceil($totalCount / 2);
            $bestSplit = [
                array_slice($destinations, 0, $half),
                array_slice($destinations, $half)
            ];
        }
        
        return $bestSplit;
    }

    /**
     * Calculate score for a day split (lower is better)
     */
    private function calculateSplitScore($startPoint, $day1Destinations, $day2Destinations, $hotelPoint)
    {
        // Build distance matrices
        $day1Matrix = $this->buildDistanceMatrix($startPoint, $day1Destinations);
        $day2Matrix = $this->buildDistanceMatrix($hotelPoint, $day2Destinations);
        
        // Calculate approximate TSP cost for day 1 (end at hotel)
        $day1Cost = $this->estimateTSPCost($day1Matrix);
        // Add distance from last optimal point to hotel
        $day1Cost += $this->getMinDistanceToHotel($day1Destinations, $hotelPoint);
        
        // Calculate approximate TSP cost for day 2 (start from hotel)
        $day2Cost = $this->estimateTSPCost($day2Matrix);
        
        // Total score
        return $day1Cost + $day2Cost;
    }

    /**
     * Estimate TSP cost using greedy nearest neighbor heuristic
     */
    private function estimateTSPCost($distanceMatrix)
    {
        $n = count($distanceMatrix);
        if ($n <= 1) return 0;
        
        $visited = array_fill(0, $n, false);
        $visited[0] = true; // Start point
        $current = 0;
        $totalCost = 0;
        
        for ($i = 1; $i < $n; $i++) {
            $minDist = PHP_INT_MAX;
            $next = -1;
            
            for ($j = 1; $j < $n; $j++) {
                if (!$visited[$j] && $distanceMatrix[$current][$j] < $minDist) {
                    $minDist = $distanceMatrix[$current][$j];
                    $next = $j;
                }
            }
            
            if ($next != -1) {
                $totalCost += $minDist;
                $visited[$next] = true;
                $current = $next;
            }
        }
        
        return $totalCost;
    }

    /**
     * Get minimum distance from any destination to hotel
     */
    private function getMinDistanceToHotel($destinations, $hotelPoint)
    {
        $minDist = PHP_INT_MAX;
        
        foreach ($destinations as $dest) {
            $dist = $this->getDistanceFromCache($dest, $hotelPoint);
            if ($dist === null) {
                $dist = $this->calculateAndCacheDistance($dest, $hotelPoint);
            }
            $minDist = min($minDist, $dist);
        }
        
        return $minDist;
    }

    /**
     * Select destinations for a day using greedy approach
     */
    private function selectDayDestinations($startPoint, $availableDestinations, $hotelPoint, $targetCount, $isLastDay)
    {
        $selected = [];
        $remaining = $availableDestinations;
        $currentPoint = $startPoint;
        
        for ($i = 0; $i < $targetCount && count($remaining) > 0; $i++) {
            $isLastSelection = ($i == $targetCount - 1) || (count($remaining) == 1);
            
            // Find best next destination
            $bestIndex = -1;
            $bestScore = PHP_INT_MAX;
            
            foreach ($remaining as $idx => $dest) {
                // Distance from current point to destination
                $distToDest = $this->getOrCalculateDistance($currentPoint, $dest);
                
                if (!$isLastDay && $isLastSelection) {
                    // If not last day and this is last selection, consider distance to hotel
                    $distToHotel = $this->getOrCalculateDistance($dest, $hotelPoint);
                    $score = $distToDest + ($distToHotel * 0.5); // Weight hotel distance
                } else {
                    $score = $distToDest;
                }
                
                if ($score < $bestScore) {
                    $bestScore = $score;
                    $bestIndex = $idx;
                }
            }
            
            if ($bestIndex != -1) {
                $selected[] = $remaining[$bestIndex];
                $currentPoint = $remaining[$bestIndex];
                array_splice($remaining, $bestIndex, 1);
                $remaining = array_values($remaining);
            }
        }
        
        return $selected;
    }

    /**
     * Get or calculate distance between two points
     */
    private function getOrCalculateDistance($fromPoint, $toPoint)
    {
        $distance = $this->getDistanceFromCache($fromPoint, $toPoint);
        if ($distance === null) {
            $distance = $this->calculateAndCacheDistance($fromPoint, $toPoint);
        }
        return $distance;
    }

    /**
     * Calculate number of combinations (for limiting search space)
     */
    private function getNumberOfCombinations($n, $k)
    {
        if ($k > $n || $k < 0) return 0;
        if ($k == 0 || $k == $n) return 1;
        
        $k = min($k, $n - $k);
        $result = 1;
        
        for ($i = 0; $i < $k; $i++) {
            $result *= ($n - $i);
            $result /= ($i + 1);
        }
        
        return $result;
    }

    /**
     * Generate route for a single day
     */
    private function generateDayRoute($startPoint, $destinations, $endPoint, $dayNumber)
    {
        // If we have an end point (hotel), add it to destinations for TSP
        $allPoints = $destinations;
        $hasEndPoint = false;
        
        if ($endPoint !== null) {
            $allPoints[] = $endPoint;
            $hasEndPoint = true;
        }
        
        // Build distance matrix
        $distanceMatrix = $this->buildDistanceMatrix($startPoint, $allPoints);
        
        // Solve TSP
        if ($hasEndPoint) {
            // Force the route to end at the last point (hotel/end point)
            $optimalRoute = $this->solveTSPWithFixedEnd($distanceMatrix, count($allPoints));
        } else {
            $optimalRoute = $this->solveTSP($distanceMatrix, count($allPoints));
        }
        
        // Build itinerary
        $itinerary = $this->buildItinerary($startPoint, $allPoints, $optimalRoute, $distanceMatrix);
        
        return [
            'day' => $dayNumber,
            'route' => $itinerary['route'],
            'total_distance' => $itinerary['total_distance'],
            'total_duration' => $itinerary['total_duration'],
        ];
    }

    /**
     * Solve TSP using Dynamic Programming (Held-Karp algorithm)
     */
    private function solveTSP($distanceMatrix, $numDestinations)
    {
        $n = $numDestinations + 1; // +1 for start point
        $dp = [];
        $parent = [];
        
        // Initialize DP table
        for ($i = 0; $i < (1 << $n); $i++) {
            $dp[$i] = array_fill(0, $n, PHP_INT_MAX);
            $parent[$i] = array_fill(0, $n, -1);
        }
        
        // Base case: starting from point 0 (start point)
        $dp[1][0] = 0;
        
        // Fill DP table
        for ($mask = 1; $mask < (1 << $n); $mask++) {
            for ($u = 0; $u < $n; $u++) {
                if (!($mask & (1 << $u))) continue;
                if ($dp[$mask][$u] == PHP_INT_MAX) continue;
                
                for ($v = 1; $v < $n; $v++) {
                    if ($mask & (1 << $v)) continue;
                    
                    $newMask = $mask | (1 << $v);
                    $newDist = $dp[$mask][$u] + $distanceMatrix[$u][$v];
                    
                    if ($newDist < $dp[$newMask][$v]) {
                        $dp[$newMask][$v] = $newDist;
                        $parent[$newMask][$v] = $u;
                    }
                }
            }
        }
        
        // Find the best ending point
        $fullMask = (1 << $n) - 1;
        $minDist = PHP_INT_MAX;
        $lastNode = -1;
        
        for ($i = 1; $i < $n; $i++) {
            if ($dp[$fullMask][$i] < $minDist) {
                $minDist = $dp[$fullMask][$i];
                $lastNode = $i;
            }
        }
        
        // Reconstruct path
        $path = [];
        $mask = $fullMask;
        $curr = $lastNode;
        
        while ($curr != 0) {
            $path[] = $curr;
            $newMask = $mask ^ (1 << $curr);
            $curr = $parent[$mask][$curr];
            $mask = $newMask;
        }
        
        return array_reverse($path);
    }

    /**
     * Solve TSP with fixed end point (for routes that must end at hotel)
     */
    private function solveTSPWithFixedEnd($distanceMatrix, $numPoints)
    {
        $n = $numPoints + 1; // +1 for start point
        $endPointIndex = $n - 1; // Last point is the end point (hotel)
        
        $dp = [];
        $parent = [];
        
        // Initialize DP table
        for ($i = 0; $i < (1 << $n); $i++) {
            $dp[$i] = array_fill(0, $n, PHP_INT_MAX);
            $parent[$i] = array_fill(0, $n, -1);
        }
        
        // Base case
        $dp[1][0] = 0;
        
        // Fill DP table (excluding end point from intermediate visits)
        for ($mask = 1; $mask < (1 << $n); $mask++) {
            for ($u = 0; $u < $n; $u++) {
                if (!($mask & (1 << $u))) continue;
                if ($dp[$mask][$u] == PHP_INT_MAX) continue;
                
                for ($v = 1; $v < $n; $v++) {
                    if ($mask & (1 << $v)) continue;
                    
                    $newMask = $mask | (1 << $v);
                    $newDist = $dp[$mask][$u] + $distanceMatrix[$u][$v];
                    
                    if ($newDist < $dp[$newMask][$v]) {
                        $dp[$newMask][$v] = $newDist;
                        $parent[$newMask][$v] = $u;
                    }
                }
            }
        }
        
        // Force ending at the end point
        $fullMask = (1 << $n) - 1;
        
        // Reconstruct path ending at endPointIndex
        $path = [];
        $mask = $fullMask;
        $curr = $endPointIndex;
        
        while ($curr != 0) {
            $path[] = $curr;
            $newMask = $mask ^ (1 << $curr);
            $curr = $parent[$mask][$curr];
            $mask = $newMask;
        }
        
        return array_reverse($path);
    }

    /**
     * Build distance matrix between all points
     */
    private function buildDistanceMatrix($startPoint, $destinations)
    {
        $points = array_merge([$startPoint], $destinations);
        $n = count($points);
        $matrix = array_fill(0, $n, array_fill(0, $n, 0));

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i == $j) {
                    $matrix[$i][$j] = 0;
                    continue;
                }

                $fromPoint = $points[$i];
                $toPoint = $points[$j];

                // Check if distance exists in cache
                $distance = $this->getDistanceFromCache($fromPoint, $toPoint);

                if ($distance === null) {
                    // Calculate and cache the distance
                    $distance = $this->calculateAndCacheDistance($fromPoint, $toPoint);
                }

                $matrix[$i][$j] = $distance;
            }
        }

        return $matrix;
    }

    /**
     * Get distance from cache
     */
    private function getDistanceFromCache($fromPoint, $toPoint)
    {
        // Check cache using coordinates (works for both tourism and custom locations)
        $cache = DistanceCache::where(function($query) use ($fromPoint, $toPoint) {
            $query->where('from_lat', $fromPoint['lat'])
                  ->where('from_long', $fromPoint['long'])
                  ->where('to_lat', $toPoint['lat'])
                  ->where('to_long', $toPoint['long']);
        })->orWhere(function($query) use ($fromPoint, $toPoint) {
            // Check reverse direction (sometimes same route can have different values)
            $query->where('from_lat', $toPoint['lat'])
                  ->where('from_long', $toPoint['long'])
                  ->where('to_lat', $fromPoint['lat'])
                  ->where('to_long', $fromPoint['long']);
        })->first();

        return $cache ? $cache->distance : null;
    }

    /**
     * Calculate distance using TripCartController method and cache it
     */
    private function calculateAndCacheDistance($fromPoint, $toPoint)
    {
        try {
            $result = $this->calculateDistance(
                $fromPoint['lat'],
                $fromPoint['long'],
                $toPoint['lat'],
                $toPoint['long']
            );

            $distance = $result['distance']; // in meters
            $duration = $result['duration']; // in seconds

            // Always cache the distance regardless of type
            try {
                DistanceCache::create([
                    'from_id' => isset($fromPoint['id']) && $fromPoint['id'] > 0 ? $fromPoint['id'] : null,
                    'to_id' => isset($toPoint['id']) && $toPoint['id'] > 0 ? $toPoint['id'] : null,
                    'from_lat' => $fromPoint['lat'],
                    'from_long' => $fromPoint['long'],
                    'to_lat' => $toPoint['lat'],
                    'to_long' => $toPoint['long'],
                    'distance' => (int) $distance,
                    'duration' => (int) $duration,
                ]);
            } catch (\Exception $cacheError) {
                // Ignore duplicate cache entries
                Log::warning('Cache entry might already exist: ' . $cacheError->getMessage());
            }

            return (int) $distance;
        } catch (\Exception $e) {
            Log::error('Failed to calculate distance: ' . $e->getMessage());
            // Fallback: return straight line distance
            return (int) ($this->calculateDistanceHaversine(
                $fromPoint['lat'],
                $fromPoint['long'],
                $toPoint['lat'],
                $toPoint['long']
            ) * 1000);
        }
    }

    /**
     * Calculate distance using OpenRouteService API (same as TripCartController)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        try {
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0=';
            
            $url = "https://api.openrouteservice.org/v2/directions/driving-car";
            $url .= "?api_key={$apiKey}";
            $url .= "&start={$lon1},{$lat1}";
            $url .= "&end={$lon2},{$lat2}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Increased timeout to 30 seconds
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                
                if (isset($data['features'][0]['properties']['segments'][0])) {
                    $segment = $data['features'][0]['properties']['segments'][0];
                    return [
                        'distance' => $segment['distance'],
                        'duration' => $segment['duration']
                    ];
                }
            }
            
            // Fallback
            $distanceKm = $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
            return [
                'distance' => $distanceKm * 1000,
                'duration' => ($distanceKm) * (3600 / 40)
            ];
            
        } catch (\Exception $e) {
            $distanceKm = $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
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
        $cache = DistanceCache::where(function($query) use ($fromPoint, $toPoint) {
            $query->where('from_lat', $fromPoint['lat'])
                  ->where('from_long', $fromPoint['long'])
                  ->where('to_lat', $toPoint['lat'])
                  ->where('to_long', $toPoint['long']);
        })->orWhere(function($query) use ($fromPoint, $toPoint) {
            $query->where('from_lat', $toPoint['lat'])
                  ->where('from_long', $toPoint['long'])
                  ->where('to_lat', $fromPoint['lat'])
                  ->where('to_long', $fromPoint['long']);
        })->first();

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
            dd($coordinates);

            // Call OpenRouteService Directions API
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0=';
            $url = "https://api.openrouteservice.org/v2/directions/driving-car";

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
                    
                    // Decode the encoded polyline geometry
                    $decodedCoordinates = $this->decodePolyline($geometryString);
                    
                    return [
                        'type' => 'success',
                        'geometry' => [
                            'type' => 'LineString',
                            'coordinates' => $decodedCoordinates
                        ],
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
     * Decode polyline geometry string to array of [lng, lat] coordinates
     * Based on Google's Encoded Polyline Algorithm Format
     */
    private function decodePolyline($encoded)
    {
        $length = strlen($encoded);
        $index = 0;
        $points = [];
        $lat = 0;
        $lng = 0;

        while ($index < $length) {
            // Decode latitude
            $result = 0;
            $shift = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            // Decode longitude
            $result = 0;
            $shift = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            // Add point as [longitude, latitude] for GeoJSON format
            $points[] = [$lng / 1e5, $lat / 1e5];
        }

        return $points;
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
        dd($itineraryData);
        
        // Log route geometry for debugging
        Log::info('Route Geometry Data:', [
            'has_geometry' => isset($itineraryData['route_geometry']),
            'geometry_type' => isset($itineraryData['route_geometry']['type']) ? $itineraryData['route_geometry']['type'] : 'unknown',
            'has_coordinates' => isset($itineraryData['route_geometry']['coordinates']),
            'coordinates_count' => isset($itineraryData['route_geometry']['coordinates']) ? count($itineraryData['route_geometry']['coordinates']) : 0,
            'has_geometry_obj' => isset($itineraryData['route_geometry']['geometry']),
            'geometry_coords_count' => isset($itineraryData['route_geometry']['geometry']['coordinates']) ? count($itineraryData['route_geometry']['geometry']['coordinates']) : 0,
        ]);
        
        return view('itinerary.result', compact('itineraryData'));
    }
}
