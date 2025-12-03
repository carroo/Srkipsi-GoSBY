<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourism;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TourismController extends Controller
{
    /**
     * Display tourism management page
     */
    public function index()
    {
        return view('admin.tourism.index');
    }
}
