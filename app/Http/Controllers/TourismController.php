<?php

namespace App\Http\Controllers;

use App\Models\Tourism;
use App\Models\Category;
use Illuminate\Http\Request;

class TourismController extends Controller
{
    /**
     * Display a listing of tourism destinations with SAW algorithm.
     * Handles both normal page load and AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $result = $this->processTourismList($request);
        
        // Return JSON response for AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('tourism.partials.tourism-list', [
                    'tourisms' => $result['tourisms']
                ])->render(),
                'weights' => $result['weights'],
                'calculations' => $result['calculations'],
                'total' => $result['tourisms']->count(),
            ]);
        }
        
        return $result['view'];
    }

    /**
     * Process tourism list with filters and SAW algorithm.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function processTourismList(Request $request)
    {
        // Get all categories for filter
        $categories = Category::all();

        // Build query with filters
        $query = Tourism::with(['categories', 'prices', 'files']);
        $query = $this->applyFilters($query, $request);

        // Get all tourism data
        $tourisms = $query->get();

        // Get weights from request (default to 0 if not provided)
        $weights = $this->getWeights($request);

        // Calculate distances if needed
        $this->calculateDistancesIfNeeded($tourisms, $request, $weights);

        // Apply SAW algorithm and get detailed results
        $calculations = $this->calculateSAW($tourisms, $weights);
        
        // Extract sorted tourisms
        $sortedTourisms = collect($calculations)->map(function($result) {
            return $result['tourism'];
        });

        return [
            'view' => view('tourism.index', [
                'tourisms' => $sortedTourisms,
                'categories' => $categories,
                'weights' => $weights,
                'calculations' => $calculations,
                'sawMode' => true,
            ]),
            'tourisms' => $sortedTourisms,
            'weights' => $weights,
            'calculations' => $calculations,
        ];
    }

    /**
     * Apply filters to the tourism query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters($query, Request $request)
    {
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('category.id', $request->category);
            });
        }

        // Search by name or location
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        return $query;
    }

    /**
     * Get weights from request or use 0 as default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function getWeights(Request $request)
    {
        $weights = [
            'popularity' => (float) ($request->weight_popularity ?? 0.5),
            'rating' => (float) ($request->weight_rating ?? 0.3),
            'price' => (float) ($request->weight_price ?? 0.2),
            'distance' => (float) ($request->weight_distance ?? 0),
        ];

        return $weights;
    }

    /**
     * Calculate distances for all tourism if coordinates are provided.
     *
     * @param  \Illuminate\Support\Collection  $tourisms
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $weights
     * @return void
     */
    private function calculateDistancesIfNeeded($tourisms, Request $request, $weights)
    {
        // Only calculate if coordinates are provided
        if (!$request->has('latitude') || !$request->has('longitude') ||
            !$request->latitude || !$request->longitude) {
            return;
        }

        foreach ($tourisms as $tourism) {
            $tourism->calculated_distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $tourism->latitude,
                $tourism->longitude
            );
        }
    }

    /**
     * Display the specified tourism detail.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Get tourism with all related data
        $tourism = Tourism::with([
            'categories',
            'prices',
            'files',
            'hours' => function($query) {
                $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')");
            }
        ])->findOrFail($id);

        // Get related/similar tourism based on categories (exclude current)
        $relatedTourism = Tourism::with(['categories', 'prices', 'files'])
            ->whereHas('categories', function($query) use ($tourism) {
                $query->whereIn('category.id', $tourism->categories->pluck('id'));
            })
            ->where('id', '!=', $tourism->id)
            ->orderByDesc('rating')
            ->limit(3)
            ->get();

        return view('tourism.show', [
            'tourism' => $tourism,
            'relatedTourism' => $relatedTourism,
        ]);
    }

    /**
     * Apply SAW algorithm and return detailed results for each tourism.
     *
     * @param \Illuminate\Support\Collection $tourisms Collection of tourism objects
     * @param array $weights Array of weights for criteria
     * @return array Array of detailed results with scores and normalization
     */
    private function calculateSAW($tourisms, $weights)
    {
        if ($tourisms->isEmpty()) {
            return [];
        }

        // Step 1: Collect raw data and find min/max values
        $rawData = [];
        $maxValues = ['popularity' => 0, 'rating' => 0];
        $minValues = ['price' => PHP_INT_MAX, 'distance' => PHP_INT_MAX];

        foreach ($tourisms as $tourism) {
            $data = [
                'tourism' => $tourism,
                'popularity' => $tourism->popularity ?? 0,
                'rating' => $tourism->rating ?? 0,
                'price' => $tourism->prices->min('price') ?? 0,
                'distance' => $tourism->calculated_distance ?? 0,
            ];

            $rawData[$tourism->id] = $data;

            // Track max values for benefit criteria
            $maxValues['popularity'] = max($maxValues['popularity'], $data['popularity']);
            $maxValues['rating'] = max($maxValues['rating'], $data['rating']);
            
            // Track min values for cost criteria
            if ($data['price'] > 0) {
                $minValues['price'] = min($minValues['price'], $data['price']);
            }
            if ($data['distance'] > 0) {
                $minValues['distance'] = min($minValues['distance'], $data['distance']);
            }
        }

        // Step 2: Normalize and calculate SAW scores
        $results = [];
        foreach ($rawData as $tourismId => $data) {
            $normalized = $this->normalize($data, $maxValues, $minValues);
            $weighted = $this->calculateWeighted($normalized, $weights);
            $sawScore = array_sum($weighted);

            $results[] = [
                'tourism' => $data['tourism'],
                'raw_values' => [
                    'popularity' => $data['popularity'],
                    'rating' => $data['rating'],
                    'price' => $data['price'],
                    'distance' => $data['distance'],
                ],
                'normalized' => $normalized,
                'weighted' => $weighted,
                'saw_score' => $sawScore,
            ];
        }

        // Step 3: Sort by SAW score (descending)
        usort($results, function($a, $b) {
            return $b['saw_score'] <=> $a['saw_score'];
        });

        return $results;
    }

    /**
     * Normalize values for SAW algorithm.
     *
     * @param array $data Raw data
     * @param array $maxValues Maximum values for benefit criteria
     * @param array $minValues Minimum values for cost criteria
     * @return array Normalized values
     */
    private function normalize($data, $maxValues, $minValues)
    {
        return [
            'popularity' => $maxValues['popularity'] > 0 
                ? $data['popularity'] / $maxValues['popularity'] 
                : 0,
            'rating' => $maxValues['rating'] > 0 
                ? $data['rating'] / $maxValues['rating'] 
                : 0,
            'price' => ($data['price'] > 0 && $minValues['price'] > 0) 
                ? $minValues['price'] / $data['price'] 
                : 1,
            'distance' => ($data['distance'] > 0 && $minValues['distance'] > 0) 
                ? $minValues['distance'] / $data['distance'] 
                : 1,
        ];
    }

    /**
     * Calculate weighted scores for each criterion.
     *
     * @param array $normalized Normalized values
     * @param array $weights Weights for each criterion
     * @return array Weighted scores
     */
    private function calculateWeighted($normalized, $weights)
    {
        return [
            'popularity' => $normalized['popularity'] * ($weights['popularity'] ?? 0),
            'rating' => $normalized['rating'] * ($weights['rating'] ?? 0),
            'price' => $normalized['price'] * ($weights['price'] ?? 0),
            'distance' => $normalized['distance'] * ($weights['distance'] ?? 0),
        ];
    }

    /**
     * Calculate distance between two coordinates using OpenRouteService API.
     *
     * @param float $lat1 User latitude
     * @param float $lon1 User longitude
     * @param float $lat2 Tourism latitude
     * @param float $lon2 Tourism longitude
     * @return float Distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        return $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
        try {
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0';
            
            // OpenRouteService API endpoint
            $url = "https://api.openrouteservice.org/v2/directions/driving-car";
            $url .= "?api_key={$apiKey}";
            $url .= "&start={$lon1},{$lat1}";  // Note: OpenRouteService uses lon,lat format
            $url .= "&end={$lon2},{$lat2}";
            
            // Make HTTP request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                
                // Extract distance from response (in meters)
                if (isset($data['features'][0]['properties']['segments'][0]['distance'])) {
                    $distanceMeters = $data['features'][0]['properties']['segments'][0]['distance'];
                    return round($distanceMeters / 1000, 2); // Convert to kilometers
                }
            }
            
            // Fallback to Haversine formula if API fails
            return $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
            
        } catch (\Exception $e) {
            // Fallback to Haversine formula on error
            return $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
        }
    }

    /**
     * Calculate distance using Haversine formula as fallback.
     *
     * @param float $lat1 User latitude
     * @param float $lon1 User longitude
     * @param float $lat2 Tourism latitude
     * @param float $lon2 Tourism longitude
     * @return float Distance in kilometers
     */
    private function calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }
}
