<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourism;
use App\Models\Category;
use App\Models\User;
use App\Models\TripCart;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $totalTourism = Tourism::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalBookings = TripCart::count();

        return view('admin.dashboard', compact(
            'totalTourism',
            'totalCategories',
            'totalUsers',
            'totalBookings'
        ));
    }
}
