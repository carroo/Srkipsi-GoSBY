<?php

namespace App\Http\Controllers;

use App\Models\TripCart;
use App\Models\DistanceCache;
use App\Models\Tourism;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripCartController extends Controller
{
    /**
     * Add tourism to trip cart
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $request->validate([
            'tourism_id' => 'required|exists:tourism,id'
        ]);

        try {
            $tripCart = TripCart::firstOrCreate([
                'user_id' => Auth::id(),
                'tourism_id' => $request->tourism_id
            ]);

            // Get the current tourism
            $currentTourism = Tourism::findOrFail($request->tourism_id);

            // Get all other tourisms in user's trip cart
            $otherTourisms = TripCart::where('user_id', Auth::id())
                ->where('tourism_id', '!=', $request->tourism_id)
                ->with('tourism')
                ->get();

            // Create distance cache for each pair (both directions)
            foreach ($otherTourisms as $otherCart) {
                $otherTourism = $otherCart->tourism;
                
                // Check if distance cache from current to other exists
                $cacheCurrentToOther = DistanceCache::where('from_id', $currentTourism->id)
                    ->where('to_id', $otherTourism->id)
                    ->first();

                if (!$cacheCurrentToOther) {
                    // Calculate distance from current to other
                    $this->createDistanceCache($currentTourism, $otherTourism);
                }

                // Check if distance cache from other to current exists
                $cacheOtherToCurrent = DistanceCache::where('from_id', $otherTourism->id)
                    ->where('to_id', $currentTourism->id)
                    ->first();

                if (!$cacheOtherToCurrent) {
                    // Calculate distance from other to current
                    $this->createDistanceCache($otherTourism, $currentTourism);
                }
            }

            return response()->json([
                'success' => true, 
                'message' => 'Destinasi berhasil ditambahkan ke trip cart!',
                'data' => $tripCart
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menambahkan destinasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create distance cache between two tourism locations
     */
    private function createDistanceCache($fromTourism, $toTourism)
    {
        try {
            // Use existing calculateDistance function (returns array with distance and duration)
            $result = $this->calculateDistance(
                $fromTourism->latitude,
                $fromTourism->longitude,
                $toTourism->latitude,
                $toTourism->longitude
            );
            
            $distanceMeters = $result['distance']; // already in meters
            $durationSeconds = $result['duration']; // already in seconds

            // Create distance cache entry (distance in meters, duration in seconds)
            DistanceCache::create([
                'from_id' => $fromTourism->id,
                'to_id' => $toTourism->id,
                'from_lat' => $fromTourism->latitude,
                'from_long' => $fromTourism->longitude,
                'to_lat' => $toTourism->latitude,
                'to_long' => $toTourism->longitude,
                'distance' => (int) $distanceMeters,
                'duration' => (int) $durationSeconds,
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the trip cart addition
            Log::error('Failed to create distance cache: ' . $e->getMessage());
        }
    }

    /**
     * Remove tourism from trip cart
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        TripCart::where('user_id', Auth::id())
            ->where('tourism_id', $id)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Destinasi berhasil dihapus dari trip cart!']);
    }

    /**
     * View trip cart
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tripCart = TripCart::where('user_id', Auth::id())
            ->with('tourism')
            ->get();

        return view('trip-cart.index', compact('tripCart'));
    }
    
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
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
                
                // Extract distance and duration from response
                if (isset($data['features'][0]['properties']['segments'][0])) {
                    $segment = $data['features'][0]['properties']['segments'][0];
                    $distanceMeters = $segment['distance']; // in meters
                    $durationSeconds = $segment['duration']; // in seconds
                    
                    return [
                        'distance' => $distanceMeters,
                        'duration' => $durationSeconds
                    ];
                }
            }
            
            // Fallback to Haversine formula if API fails
            $distanceKm = $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
            return [
                'distance' => $distanceKm * 1000, // convert to meters
                'duration' => ($distanceKm) * (3600 / 40) // estimate duration
            ];
            
        } catch (\Exception $e) {
            // Fallback to Haversine formula on error
            $distanceKm = $this->calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2);
            return [
                'distance' => $distanceKm * 1000, // convert to meters
                'duration' => ($distanceKm) * (3600 / 40) // estimate duration
            ];
        }
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in kilometers
     */
    private function calculateDistanceHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); // Distance in kilometers
    }
}
