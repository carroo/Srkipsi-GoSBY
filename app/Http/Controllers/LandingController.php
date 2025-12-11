<?php

namespace App\Http\Controllers;

use App\Models\Tourism;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page with popular tourism destinations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get popular tourism destinations (top 6 based on rating)
        $popularTourism = Tourism::with(['categories', 'prices', 'files'])
            ->orderByDesc('popularity')
            ->limit(6)
            ->get();

        // Get all categories for reference if needed
        $categories = Category::all();

        // Get total statistics
        $stats = [
            'total_destinations' => Tourism::count(),
            'total_categories' => Category::count(),
        ];

        return view('landing', [
            'popularTourism' => $popularTourism,
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }
}
