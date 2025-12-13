<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourism;
use App\Models\DistanceCache;
use Illuminate\Http\Request;

class DistanceMatrixController extends Controller
{
    /**
     * Display distance matrix with map
     */
    public function index()
    {
        // Get only tourism locations that exist in distance_cache
        $tourismIds = DistanceCache::distinct()
            ->pluck('from_id')
            ->merge(DistanceCache::distinct()->pluck('to_id'))
            ->unique()
            ->sort()
            ->values();

        $tourisms = Tourism::whereIn('id', $tourismIds)
            ->orderBy('name')
            ->get();
        
        // Build distance matrix
        $matrix = [];
        foreach ($tourisms as $from) {
            $row = [];
            foreach ($tourisms as $to) {
                if ($from->id === $to->id) {
                    $row[] = ['distance' => 0, 'duration' => 0];
                } else {
                    $distance = DistanceCache::where('from_id', $from->id)
                        ->where('to_id', $to->id)
                        ->first();
                    
                    if ($distance) {
                        $row[] = [
                            'distance' => $distance->distance,
                            'duration' => $distance->duration,
                        ];
                    } else {
                        $row[] = ['distance' => null, 'duration' => null];
                    }
                }
            }
            $matrix[$from->id] = $row;
        }

        return view('admin.distance-matrix.index', compact('tourisms', 'matrix'));
    }

    /**
     * Get distance data via API
     */
    public function getDistanceData(Request $request)
    {
        // Get ALL tourism locations for map display
        $tourisms = Tourism::orderBy('name')->get();
        
        return response()->json([
            'tourisms' => $tourisms->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'latitude' => $t->latitude,
                    'longitude' => $t->longitude,
                ];
            }),
        ]);
    }
}
