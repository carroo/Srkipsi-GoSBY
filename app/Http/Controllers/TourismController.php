<?php

namespace App\Http\Controllers;

use App\Models\Tourism;
use App\Models\Category;
use Illuminate\Http\Request;

class TourismController extends Controller
{
    /**
     * Display a listing of tourism destinations.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get all categories for filter
        $categories = Category::all();

        // Build query
        $query = Tourism::with(['categories', 'prices', 'files']);

        // Filter by category if provided
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

        // Sort
        $sortBy = $request->get('sort', 'rating');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            case 'latest':
                $query->orderByDesc('created_at');
                break;
        }

        // Paginate
        $tourisms = $query->paginate(12);

        return view('tourism.index', [
            'tourisms' => $tourisms,
            'categories' => $categories,
            'sawMode' => false,
        ]);
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
            },
            'facilities'
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
     * Calculate distance between two coordinates (Haversine formula).
     * TODO: Implement actual calculation
     *
     * @param float $lat1 User latitude
     * @param float $lon1 User longitude
     * @param float $lat2 Tourism latitude
     * @param float $lon2 Tourism longitude
     * @return float Distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Dummy return random number for now (1-50 km)
        return rand(1, 50);
    }

    /**
     * SAW (Simple Additive Weighting) Algorithm for tourism recommendation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function saw(Request $request)
    {
        // Get weights from request
        $weights = [
            'rating' => floatval($request->weight_rating) / 100,
            'price' => floatval($request->weight_price) / 100,
            'facility' => floatval($request->weight_facility) / 100,
            'distance' => floatval($request->weight_distance) / 100,
        ];

        // Get selected categories and their weights
        $selectedCategories = [];
        $categoryWeightTotal = 0;

        if ($request->has('categories')) {
            foreach ($request->categories as $categoryId) {
                $weightKey = 'weight_category_' . $categoryId;
                $weight = floatval($request->$weightKey);
                if ($weight > 0) {
                    $selectedCategories[$categoryId] = $weight / 100;
                    $categoryWeightTotal += $weight / 100;
                }
            }
        }

        // Validate total weight must be exactly 100%
        $totalWeight = $weights['rating'] + $weights['price'] + $weights['facility'] + $weights['distance'] + $categoryWeightTotal;

        if (abs($totalWeight - 1.0) > 0.001) { // Allow small floating point differences
            return redirect()->route('tourism.index')->with('error', 'Total bobot harus tepat 100%! Total saat ini: ' . number_format($totalWeight * 100, 2) . '%');
        }

        // User coordinates
        $userLat = floatval($request->latitude);
        $userLon = floatval($request->longitude);

        // Get all tourism with relations
        $tourisms = Tourism::with(['categories', 'prices', 'facilities'])->get();

        // Initialize arrays for normalization
        $rawData = [];
        $maxValues = [
            'rating' => 0,
            'price' => 0,
            'facility' => 0,
            'distance' => 0,
        ];
        $minValues = [
            'rating' => PHP_INT_MAX,
            'price' => PHP_INT_MAX,
            'facility' => PHP_INT_MAX,
            'distance' => PHP_INT_MAX,
        ];

        // Collect raw data and find min/max values
        foreach ($tourisms as $tourism) {
            // Rating (benefit - higher is better)
            $rating = $tourism->rating;

            // Price (cost - lower is better) - use minimum price
            $price = $tourism->prices->min('price') ?? 0;

            // Facility count (benefit - more is better)
            $facilityCount = $tourism->facilities->count();

            // Distance (cost - lower is better)
            $distance = $this->calculateDistance(
                $userLat,
                $userLon,
                $tourism->latitude,
                $tourism->longitude
            );

            // Category matching - INDIVIDUAL per category (benefit - 1 if matches, 0 if not)
            $categoryMatches = [];
            $tourismCategories = $tourism->categories->pluck('id')->toArray();

            foreach ($selectedCategories as $catId => $catWeight) {
                $categoryMatches[$catId] = in_array($catId, $tourismCategories) ? 1 : 0;
            }

            // Store raw data
            $rawData[$tourism->id] = [
                'tourism' => $tourism,
                'rating' => $rating,
                'price' => $price,
                'facility' => $facilityCount,
                'distance' => $distance,
                'categories' => $categoryMatches, // Array of individual category matches
            ];

            // Update min/max values
            $maxValues['rating'] = max($maxValues['rating'], $rating);
            $maxValues['facility'] = max($maxValues['facility'], $facilityCount);
            $maxValues['distance'] = max($maxValues['distance'], $distance);

            $minValues['rating'] = min($minValues['rating'], $rating);
            $minValues['price'] = min($minValues['price'], $price > 0 ? $price : PHP_INT_MAX);
            $minValues['facility'] = min($minValues['facility'], $facilityCount);
            $minValues['distance'] = min($minValues['distance'], $distance);
        }

        // Normalize and calculate SAW scores
        $results = [];
        foreach ($rawData as $tourismId => $data) {
            $normalized = [];

            // Normalize Rating (benefit: value/max)
            $normalized['rating'] = $maxValues['rating'] > 0
                ? $data['rating'] / $maxValues['rating']
                : 0;

            // Normalize Price (cost: min/value) - lower is better
            $normalized['price'] = ($data['price'] > 0 && $minValues['price'] > 0)
                ? $minValues['price'] / $data['price']
                : 1; // Free entrance gets max score

            // Normalize Facility (benefit: value/max)
            $normalized['facility'] = $maxValues['facility'] > 0
                ? $data['facility'] / $maxValues['facility']
                : 0;

            // Normalize Distance (cost: min/value) - lower is better
            $normalized['distance'] = ($data['distance'] > 0 && $minValues['distance'] > 0)
                ? $minValues['distance'] / $data['distance']
                : 1;

            // Normalize each category individually (already 0 or 1, no normalization needed)
            $normalized['categories'] = [];
            foreach ($selectedCategories as $catId => $catWeight) {
                $normalized['categories'][$catId] = $data['categories'][$catId] ?? 0;
            }

            // Calculate weighted sum (SAW formula)
            $sawScore =
                ($normalized['rating'] * $weights['rating']) +
                ($normalized['price'] * $weights['price']) +
                ($normalized['facility'] * $weights['facility']) +
                ($normalized['distance'] * $weights['distance']);

            // Add each category score individually
            foreach ($selectedCategories as $catId => $catWeight) {
                $sawScore += ($normalized['categories'][$catId] * $catWeight);
            }

            $results[] = [
                'tourism_id' => $tourismId,
                'tourism_name' => $data['tourism']->name,
                'raw_data' => $data,
                'normalized' => $normalized,
                'weights' => array_merge($weights, ['categories' => $selectedCategories]),
                'saw_score' => $sawScore,
            ];
        }

        // Sort by SAW score (highest first)
        usort($results, function($a, $b) {
            return $b['saw_score'] <=> $a['saw_score'];
        });

        // Get all tourism recommendations (no slicing)
        $recommendations = [];
        foreach ($results as $result) {
            $recommendations[] = $result['raw_data']['tourism'];
        }

        // Store calculation data in session for modal view
        session([
            'saw_calculation' => [
                'input' => [
                    'weights' => array_merge($weights, ['category_total' => $categoryWeightTotal]),
                    'selected_categories' => $selectedCategories,
                    'user_coordinates' => ['lat' => $userLat, 'lon' => $userLon],
                ],
                'minMaxValues' => [
                    'max' => $maxValues,
                    'min' => $minValues,
                ],
                'results' => $results,
            ]
        ]);

        // Return to index with all SAW results
        return view('tourism.index', [
            'tourisms' => collect($recommendations), // All results, not sliced
            'categories' => Category::all(),
            'sawMode' => true,
            'totalResults' => count($results),
            'sawCalculation' => session('saw_calculation'), // Pass to view for modal
        ]);
    }

    /**
     * Show detailed SAW calculation from session.
     *
     * @return \Illuminate\View\View
     */
    public function showCalculation()
    {
        // Get calculation data from session
        $calculationData = session('saw_calculation');

        if (!$calculationData) {
            return redirect()->route('tourism.index')->with('error', 'Tidak ada data perhitungan. Silakan lakukan pencarian rekomendasi terlebih dahulu.');
        }

        return view('tourism.saw', [
            'input' => $calculationData['input'],
            'minMaxValues' => $calculationData['minMaxValues'],
            'results' => $calculationData['results'],
            'topRecommendations' => array_slice($calculationData['results'], 0, 5),
            'categories' => Category::all(),
        ]);
    }
}
