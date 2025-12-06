<?php

namespace App\Http\Controllers;

use App\Models\TripCart;
use App\Models\Tourism;
use App\Models\DistanceCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItineraryController extends Controller
{
    /**
     * Show the form to create itinerary
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get all trip cart items with tourism data
        $tripCartItems = TripCart::where('user_id', Auth::id())
            ->with(['tourism.files', 'tourism.prices', 'tourism.categories'])
            ->get();

        return view('itinerary.create', compact('tripCartItems'));
    }

    /**
     * Generate itinerary based on user preferences
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1|max:30',
            'tolerance_minutes' => 'required|integer|min:0|max:120',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'start_location_type' => 'required|in:destination,custom',
            'end_location_type' => 'required|in:destination,custom',
            'tourism_ids' => 'required|array|min:1',
            'tourism_ids.*' => 'exists:tourism,id',
            'start_date' => 'nullable|date',
        ]);

        // Get tourism locations with hours
        $tourismIds = $validated['tourism_ids'];
        $tourismLocations = Tourism::whereIn('id', $tourismIds)
            ->with('hours')
            ->get()
            ->keyBy('id');

        // Prepare start location
        if ($validated['start_location_type'] === 'destination') {
            $startTourism = $tourismLocations[$request->start_destination_id];
            $startLocation = [
                'id' => 'start_' . $startTourism->id,
                'name' => $startTourism->name,
                'latitude' => $startTourism->latitude,
                'longitude' => $startTourism->longitude,
            ];
        } else {
            $startLocation = [
                'id' => 'start_custom',
                'name' => $request->start_location_name ?? 'Titik Awal',
                'latitude' => $request->start_latitude,
                'longitude' => $request->start_longitude,
            ];
        }

        // Prepare end location
        if ($validated['end_location_type'] === 'destination') {
            $endTourism = $tourismLocations[$request->end_destination_id];
            $endLocation = [
                'id' => 'end_' . $endTourism->id,
                'name' => $endTourism->name,
                'latitude' => $endTourism->latitude,
                'longitude' => $endTourism->longitude,
            ];
        } else {
            $endLocation = [
                'id' => 'end_custom',
                'name' => $request->end_location_name ?? 'Titik Akhir',
                'latitude' => $request->end_latitude,
                'longitude' => $request->end_longitude,
            ];
        }

        // Build distance matrix with caching
        $distanceMatrix = $this->buildDistanceMatrix($tourismLocations, $startLocation, $endLocation);
        // dd($distanceMatrix);

        // Get start date for day of week calculation
        $startDate = $validated['start_date'] ?? now()->format('Y-m-d');
        
        // Implement Dynamic Programming for optimal route
        $optimalRoute = $this->calculateOptimalRoute(
            $tourismLocations,
            $distanceMatrix,
            $startLocation,
            $endLocation,
            $validated['duration_days'],
            $validated['start_time'],
            $validated['end_time'],
            $startDate
        );

        // Store in session for result page
        session([
            'itinerary_result' => [
                'route' => $optimalRoute['result'],
                'dp_steps' => $optimalRoute['dp_steps'],
                'distance_matrix' => $distanceMatrix,
                'start_location' => $startLocation,
                'end_location' => $endLocation,
                'settings' => [
                    'duration_days' => $validated['duration_days'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'tolerance_minutes' => $validated['tolerance_minutes'],
                ],
                'alerts' => $optimalRoute['alerts'] ?? [],
            ],
        ]);

        return redirect()->route('itinerary.result');
    }

    /**
     * Build distance matrix with database caching
     */
    private function buildDistanceMatrix($tourismLocations, $startLocation, $endLocation)
    {
        $matrix = [];
        $allLocations = collect([]);
        
        // Add start location
        $allLocations->push($startLocation);
        
        // Add tourism locations
        foreach ($tourismLocations as $tourism) {
            $allLocations->push([
                'id' => $tourism->id,
                'name' => $tourism->name,
                'latitude' => $tourism->latitude,
                'longitude' => $tourism->longitude,
            ]);
        }
        
        // Add end location
        $allLocations->push($endLocation);

        // Build matrix
        foreach ($allLocations as $from) {
            $matrix[$from['id']] = [];
            
            foreach ($allLocations as $to) {
                if ($from['id'] === $to['id']) {
                    $matrix[$from['id']][$to['id']] = [
                        'distance' => 0,
                        'duration' => 0,
                    ];
                    continue;
                }

                // Get distance and duration (with caching)
                $distanceData = $this->getDistanceWithCache(
                    $from['id'],
                    $to['id'],
                    $from['latitude'],
                    $from['longitude'],
                    $to['latitude'],
                    $to['longitude']
                );

                $matrix[$from['id']][$to['id']] = $distanceData;
            }
        }

        return $matrix;
    }

    /**
     * Get distance with database caching
     */
    private function getDistanceWithCache($fromId, $toId, $fromLat, $fromLon, $toLat, $toLon)
    {
        // Only cache for tourism-to-tourism distances (both IDs are numeric)
        if (is_numeric($fromId) && is_numeric($toId)) {
            // Check if distance exists in cache
            $cache = DistanceCache::where('from_id', $fromId)
                ->where('to_id', $toId)
                ->first();

            if ($cache) {
                return [
                    'distance' => $cache->distance,
                    'duration' => $cache->duration,
                    'cached' => true,
                ];
            }

            // Calculate distance using API
            $result = $this->calculateDistanceAndDuration($fromLat, $fromLon, $toLat, $toLon);

            // Save to cache for future use
            try {
                DistanceCache::create([
                    'from_id' => $fromId,
                    'to_id' => $toId,
                    'distance' => $result['distance'],
                    'duration' => $result['duration'],
                ]);
            } catch (\Exception $e) {
                // Ignore duplicate key errors
            }

            $result['cached'] = false;
            return $result;
        }

        // For custom locations, calculate without caching
        return $this->calculateDistanceAndDuration($fromLat, $fromLon, $toLat, $toLon);
    }

    /**
     * Calculate distance and duration using API or fallback
     */
    private function calculateDistanceAndDuration($lat1, $lon1, $lat2, $lon2)
    {
        try {
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0';
            
            $url = "https://api.openrouteservice.org/v2/directions/driving-car";
            $url .= "?api_key={$apiKey}";
            $url .= "&start={$lon1},{$lat1}";
            $url .= "&end={$lon2},{$lat2}";
            
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
                
                if (isset($data['features'][0]['properties']['segments'][0])) {
                    $segment = $data['features'][0]['properties']['segments'][0];
                    return [
                        'distance' => (int) $segment['distance'], // in meters
                        'duration' => (int) $segment['duration'], // in seconds
                    ];
                }
            }
            
            // Fallback to Haversine
            return $this->calculateDistanceHaversineFallback($lat1, $lon1, $lat2, $lon2);
            
        } catch (\Exception $e) {
            return $this->calculateDistanceHaversineFallback($lat1, $lon1, $lat2, $lon2);
        }
    }

    /**
     * Haversine formula fallback (returns distance in meters and estimated duration)
     */
    private function calculateDistanceHaversineFallback($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = (int) ($earthRadius * $c);

        // Estimate duration: assume average speed 40 km/h
        $duration = (int) (($distance / 1000) / 40 * 3600);

        return [
            'distance' => $distance,
            'duration' => $duration,
        ];
    }

    /**
     * Calculate optimal route using Dynamic Programming (Bitmask DP for TSP)
     */
    private function calculateOptimalRoute($tourismLocations, $distanceMatrix, $startLocation, $endLocation, $days, $startTime, $endTime, $startDate = null)
    {
        // Array to store all DP steps for visualization
        $dpSteps = [];
        
        // Convert times to minutes
        $startMinutes = $this->timeToMinutes($startTime);
        $endMinutes = $this->timeToMinutes($endTime);
        $dailyAvailableMinutes = $endMinutes - $startMinutes;

        // Parse start date for day of week calculation
        $startDate = $startDate ?? now()->format('Y-m-d');
        $startDayOfWeek = date('w', strtotime($startDate)); // 0=Sunday, 6=Saturday

        $dpSteps[] = [
            'step' => 'INITIALIZATION',
            'description' => 'Inisialisasi parameter Dynamic Programming',
            'data' => [
                'total_days' => $days,
                'start_date' => $startDate,
                'start_day_of_week' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$startDayOfWeek],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'start_minutes' => $startMinutes,
                'end_minutes' => $endMinutes,
                'daily_available_minutes' => $dailyAvailableMinutes,
                'total_locations' => count($tourismLocations),
            ],
        ];

        // Prepare locations
        $locations = $tourismLocations->values()->toArray();
        $n = count($locations);

        $dpSteps[] = [
            'step' => 'LOCATION_PREPARATION',
            'description' => 'Menyiapkan daftar lokasi untuk DP',
            'data' => [
                'n' => $n,
                'locations' => collect($locations)->map(function($loc, $idx) {
                    $hoursInfo = 'Selalu buka';
                    if (isset($loc['hours']) && count($loc['hours']) > 0) {
                        $hoursInfo = collect($loc['hours'])->map(function($h) {
                            $day = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$h['day']];
                            return "{$day}: {$h['open_time']}-{$h['close_time']}";
                        })->join(', ');
                    }
                    
                    return [
                        'index' => $idx,
                        'id' => $loc['id'],
                        'name' => $loc['name'],
                        'hours' => $hoursInfo,
                    ];
                })->toArray(),
            ],
        ];

        // DP Table: dp[mask][last] = minimum distance to visit all locations in mask, ending at last
        // mask: bitmask representing visited locations
        // last: index of last visited location
        $INF = PHP_INT_MAX / 2;
        $dp = [];
        $parent = [];
        
        // Initialize DP table
        for ($mask = 0; $mask < (1 << $n); $mask++) {
            $dp[$mask] = [];
            $parent[$mask] = [];
            for ($i = 0; $i < $n; $i++) {
                $dp[$mask][$i] = $INF;
                $parent[$mask][$i] = -1;
            }
        }

        $dpSteps[] = [
            'step' => 'DP_TABLE_INIT',
            'description' => 'Inisialisasi tabel DP dengan bitmask',
            'data' => [
                'table_size' => '2^' . $n . ' x ' . $n,
                'total_states' => (1 << $n) * $n,
                'infinity_value' => 'INF',
                'explanation' => 'dp[mask][i] = jarak minimum untuk mengunjungi lokasi dalam mask, berakhir di i',
            ],
        ];

        // Base case: Start from start location to each location
        $baseDistances = [];
        for ($i = 0; $i < $n; $i++) {
            $distData = $distanceMatrix[$startLocation['id']][$locations[$i]['id']];
            $travelMinutes = ceil($distData['duration'] / 60);
            
            if ($travelMinutes + 60 <= $dailyAvailableMinutes) {
                $dp[1 << $i][$i] = $distData['distance'];
                $baseDistances[] = [
                    'to_index' => $i,
                    'to_name' => $locations[$i]['name'],
                    'distance' => $distData['distance'],
                    'duration_minutes' => $travelMinutes,
                    'feasible' => true,
                ];
            } else {
                $baseDistances[] = [
                    'to_index' => $i,
                    'to_name' => $locations[$i]['name'],
                    'distance' => $distData['distance'],
                    'duration_minutes' => $travelMinutes,
                    'feasible' => false,
                    'reason' => 'Waktu tidak cukup',
                ];
            }
        }

        $dpSteps[] = [
            'step' => 'DP_BASE_CASE',
            'description' => 'Base case: jarak dari titik awal ke setiap lokasi',
            'data' => [
                'from' => $startLocation['name'],
                'distances' => $baseDistances,
            ],
        ];

        // DP Transition: Try all possible next locations
        $transitionCount = 0;
        for ($mask = 0; $mask < (1 << $n); $mask++) {
            for ($last = 0; $last < $n; $last++) {
                // Check if last is in mask
                if (!(($mask >> $last) & 1)) continue;
                if ($dp[$mask][$last] == $INF) continue;

                // Try to go to next location
                for ($next = 0; $next < $n; $next++) {
                    // Check if next is already visited
                    if (($mask >> $next) & 1) continue;

                    $distData = $distanceMatrix[$locations[$last]['id']][$locations[$next]['id']];
                    $newDist = $dp[$mask][$last] + $distData['distance'];
                    $newMask = $mask | (1 << $next);

                    if ($newDist < $dp[$newMask][$next]) {
                        $dp[$newMask][$next] = $newDist;
                        $parent[$newMask][$next] = $last;
                        
                        $transitionCount++;
                        
                        // Log significant transitions
                        if ($transitionCount <= 20 || $mask == (1 << $n) - 1) {
                            $visitedLocs = [];
                            for ($i = 0; $i < $n; $i++) {
                                if (($mask >> $i) & 1) {
                                    $visitedLocs[] = $locations[$i]['name'];
                                }
                            }
                            
                            $dpSteps[] = [
                                'step' => 'DP_TRANSITION',
                                'description' => 'Transisi DP: mencoba perpindahan ke lokasi baru',
                                'data' => [
                                    'mask' => decbin($mask) . ' → ' . decbin($newMask),
                                    'visited_before' => $visitedLocs,
                                    'from' => $locations[$last]['name'],
                                    'to' => $locations[$next]['name'],
                                    'distance_last_to_next' => number_format($distData['distance'] / 1000, 2) . ' km',
                                    'dp_old' => $dp[$mask][$last],
                                    'dp_new' => $newDist,
                                    'is_better' => true,
                                ],
                            ];
                        }
                    }
                }
            }
        }

        $dpSteps[] = [
            'step' => 'DP_COMPLETE',
            'description' => 'DP table lengkap dihitung',
            'data' => [
                'total_transitions' => $transitionCount,
                'states_computed' => (1 << $n) * $n,
            ],
        ];

        // Find optimal route considering time constraints for multi-day
        $result = $this->reconstructRouteMultiDay(
            $dp, 
            $parent, 
            $locations, 
            $distanceMatrix, 
            $startLocation, 
            $endLocation, 
            $days, 
            $dailyAvailableMinutes,
            $dpSteps,
            $n,
            $startTime,
            $startDayOfWeek
        );

        // Check if actual days needed is less than requested
        $alerts = [];
        $actualDays = count($result['days']);
        if ($actualDays < $days) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Itinerary Lebih Efisien',
                'message' => "Anda memasukkan {$days} hari, tetapi semua destinasi dapat dikunjungi dalam {$actualDays} hari saja dengan waktu yang tersedia.",
            ];
        }

        return [
            'result' => $result,
            'dp_steps' => $dpSteps,
            'alerts' => $alerts,
        ];
    }

    /**
     * Reconstruct route from DP table with multi-day support
     */
    private function reconstructRouteMultiDay($dp, $parent, $locations, $distanceMatrix, $startLocation, $endLocation, $maxDays, $dailyAvailableMinutes, &$dpSteps, $n, $startTime, $startDayOfWeek)
    {
        $INF = PHP_INT_MAX / 2;
        
        // Find best ending location from DP table
        $fullMask = (1 << $n) - 1;
        $minDist = $INF;
        $bestLast = -1;
        
        $candidates = [];
        for ($i = 0; $i < $n; $i++) {
            if ($dp[$fullMask][$i] < $INF) {
                $candidates[] = [
                    'index' => $i,
                    'name' => $locations[$i]['name'],
                    'total_distance' => $dp[$fullMask][$i],
                    'is_best' => false,
                ];
                
                if ($dp[$fullMask][$i] < $minDist) {
                    $minDist = $dp[$fullMask][$i];
                    $bestLast = $i;
                }
            }
        }

        // Mark best candidate
        foreach ($candidates as &$c) {
            if ($c['index'] == $bestLast) {
                $c['is_best'] = true;
            }
        }

        $dpSteps[] = [
            'step' => 'FIND_OPTIMAL_ENDING',
            'description' => 'Mencari lokasi akhir optimal dari tabel DP',
            'data' => [
                'full_mask' => decbin($fullMask) . ' (semua lokasi dikunjungi)',
                'candidates' => $candidates,
                'best_ending' => $bestLast >= 0 ? $locations[$bestLast]['name'] : 'none',
                'min_distance_km' => $bestLast >= 0 ? number_format($minDist / 1000, 2) : 'N/A',
            ],
        ];

        // Backtrack to get the path
        $path = [];
        $mask = $fullMask;
        $current = $bestLast;

        while ($current != -1) {
            array_unshift($path, $current);
            $prev = $parent[$mask][$current];
            if ($prev != -1) {
                $mask ^= (1 << $current);
            }
            $current = $prev;
        }

        $dpSteps[] = [
            'step' => 'BACKTRACK_PATH',
            'description' => 'Backtrack dari tabel DP untuk mendapatkan urutan optimal',
            'data' => [
                'path_indices' => $path,
                'path_names' => array_map(fn($i) => $locations[$i]['name'], $path),
                'explanation' => 'Menggunakan parent pointer untuk merekonstruksi path optimal',
            ],
        ];

        // Split path into multiple days considering time constraints
        $result = [
            'days' => [],
            'total_distance' => 0,
            'total_duration' => 0,
        ];

        $currentDay = 1;
        $currentTime = 0;
        $currentLocation = $startLocation;
        $currentDayOfWeek = $startDayOfWeek;
        
        $dayPlan = [
            'day' => 1,
            'day_of_week' => $currentDayOfWeek,
            'locations' => [],
            'start_location' => $startLocation,
            'end_location' => null,
            'total_distance' => 0,
            'total_duration' => 0,
        ];

        $dpSteps[] = [
            'step' => 'SPLIT_INTO_DAYS',
            'description' => 'Membagi path optimal berdasarkan constraint waktu harian',
            'data' => [
                'max_days_requested' => $maxDays,
                'daily_time_limit' => $dailyAvailableMinutes . ' minutes',
                'note' => 'Sistem akan menggunakan hari seminimal mungkin',
            ],
        ];

        foreach ($path as $idx => $locIdx) {
            $location = $locations[$locIdx];
            $distData = $distanceMatrix[$currentLocation['id']][$location['id']];
            $travelMinutes = ceil($distData['duration'] / 60);
            $visitMinutes = 60;

            // Calculate estimated arrival time
            $estimatedArrivalMinutes = $currentTime + $travelMinutes;
            $estimatedArrivalTime = sprintf('%02d:%02d', 
                floor(($this->timeToMinutes($startTime) + $estimatedArrivalMinutes) / 60) % 24,
                ($this->timeToMinutes($startTime) + $estimatedArrivalMinutes) % 60
            );

            // Check if tourism is open at arrival time
            $openCheck = $this->isTourismOpen($location, $currentDayOfWeek, $estimatedArrivalTime);

            // Check if we need to move to next day
            if ($currentTime + $travelMinutes + $visitMinutes > $dailyAvailableMinutes || !$openCheck['is_open']) {
                // Check if we still have days available
                if ($currentDay >= $maxDays) {
                    $dpSteps[] = [
                        'step' => 'DAY_LIMIT_REACHED',
                        'description' => 'Batas hari maksimal tercapai',
                        'data' => [
                            'max_days' => $maxDays,
                            'unvisited_locations' => count($path) - $idx,
                            'warning' => 'Tidak semua lokasi dapat dikunjungi dalam batas waktu',
                        ],
                    ];
                    break; // Stop if max days reached
                }
                
                // Save current day
                $result['days'][] = $dayPlan;
                $result['total_distance'] += $dayPlan['total_distance'];
                $result['total_duration'] += $dayPlan['total_duration'];

                $closeReason = !$openCheck['is_open'] ? $openCheck['reason'] : 'Waktu tidak cukup untuk lokasi berikutnya';

                $dpSteps[] = [
                    'step' => "DAY_{$currentDay}_COMPLETE",
                    'description' => "Hari {$currentDay} selesai, lanjut ke hari berikutnya",
                    'data' => [
                        'day_completed' => $currentDay,
                        'locations_visited' => count($dayPlan['locations']),
                        'time_used' => $currentTime . ' minutes',
                        'reason' => $closeReason,
                    ],
                ];

                // Start new day
                $currentDay++;
                $currentTime = 0;
                $currentDayOfWeek = ($currentDayOfWeek + 1) % 7;
                
                // For day 2+, start from the end location of previous day
                $lastLocOfPrevDay = end($result['days'])['locations'];
                $prevDayEndLocation = end($lastLocOfPrevDay)['tourism'];
                
                $dayPlan = [
                    'day' => $currentDay,
                    'day_of_week' => $currentDayOfWeek,
                    'locations' => [],
                    'start_location' => $prevDayEndLocation,
                    'end_location' => null,
                    'total_distance' => 0,
                    'total_duration' => 0,
                ];
                
                // Recalculate from new starting point
                $currentLocation = $prevDayEndLocation;
                $distData = $distanceMatrix[$currentLocation['id']][$location['id']];
                $travelMinutes = ceil($distData['duration'] / 60);
                
                // Recalculate arrival time for new day
                $estimatedArrivalMinutes = $currentTime + $travelMinutes;
                $estimatedArrivalTime = sprintf('%02d:%02d', 
                    floor(($this->timeToMinutes($startTime) + $estimatedArrivalMinutes) / 60) % 24,
                    ($this->timeToMinutes($startTime) + $estimatedArrivalMinutes) % 60
                );
                
                // Recheck if open on new day
                $openCheck = $this->isTourismOpen($location, $currentDayOfWeek, $estimatedArrivalTime);
            }

            // Add location to current day with open status
            $dayPlan['locations'][] = [
                'tourism' => $location,
                'distance_from_previous' => $distData['distance'],
                'duration_from_previous' => $distData['duration'],
                'travel_minutes' => $travelMinutes,
                'arrival_time' => $estimatedArrivalTime,
                'is_open' => $openCheck['is_open'],
                'open_status' => $openCheck['is_open'] ? 'Buka' : $openCheck['reason'],
            ];

            $dayPlan['total_distance'] += $distData['distance'];
            $dayPlan['total_duration'] += $distData['duration'];
            $currentTime += $travelMinutes + $visitMinutes;
            $currentLocation = $location;

            $dpSteps[] = [
                'step' => "ADD_TO_DAY_{$currentDay}",
                'description' => "Menambahkan lokasi ke hari {$currentDay}",
                'data' => [
                    'location' => $location['name'],
                    'arrival_time' => $estimatedArrivalTime,
                    'is_open' => $openCheck['is_open'],
                    'open_status' => $openCheck['is_open'] ? 'Buka' : $openCheck['reason'],
                    'time_used' => $currentTime . ' minutes',
                    'time_remaining' => ($dailyAvailableMinutes - $currentTime) . ' minutes',
                ],
            ];
        }

        // Add last day and return to end location
        if (!empty($dayPlan['locations'])) {
            $lastLoc = end($dayPlan['locations']);
            $returnDistData = $distanceMatrix[$lastLoc['tourism']['id']][$endLocation['id']];
            $dayPlan['return_distance'] = $returnDistData['distance'];
            $dayPlan['return_duration'] = $returnDistData['duration'];
            $dayPlan['total_distance'] += $returnDistData['distance'];
            $dayPlan['total_duration'] += $returnDistData['duration'];
            $dayPlan['end_location'] = $endLocation;
            
            $result['days'][] = $dayPlan;
            $result['total_distance'] += $dayPlan['total_distance'];
            $result['total_duration'] += $dayPlan['total_duration'];
        }

        $actualDays = count($result['days']);

        $dpSteps[] = [
            'step' => 'FINAL_RESULT',
            'description' => 'Hasil akhir dari Dynamic Programming',
            'data' => [
                'algorithm' => 'Bitmask Dynamic Programming (TSP)',
                'max_days_requested' => $maxDays,
                'actual_days_used' => $actualDays,
                'efficiency_note' => $actualDays < $maxDays ? "Lebih efisien: hanya butuh {$actualDays} hari dari {$maxDays} hari yang diminta" : "Menggunakan semua hari yang tersedia",
                'total_locations_visited' => count($path),
                'total_distance_km' => number_format($result['total_distance'] / 1000, 2),
                'total_duration_hours' => number_format($result['total_duration'] / 3600, 2),
                'optimal_path' => array_map(fn($i) => $locations[$i]['name'], $path),
            ],
        ];

        return $result;
    }

    /**
     * Convert time string to minutes
     */
    private function timeToMinutes($time)
    {
        list($hours, $minutes) = explode(':', $time);
        return ($hours * 60) + $minutes;
    }

    /**
     * Check if tourism is open at given time
     * @param array $location Tourism location with hours relationship
     * @param int $dayOfWeek Day of week (0=Sunday, 1=Monday, etc)
     * @param string $time Time in H:i format
     * @return array ['is_open' => bool, 'reason' => string|null]
     */
    private function isTourismOpen($location, $dayOfWeek, $time)
    {
        // If no hours data, assume always open
        if (!isset($location['hours']) || empty($location['hours'])) {
            return ['is_open' => true, 'reason' => null];
        }

        // Find hours for the specific day
        $hoursForDay = null;
        foreach ($location['hours'] as $hour) {
            if ($hour['day'] == $dayOfWeek) {
                $hoursForDay = $hour;
                break;
            }
        }

        // If no hours for this day, assume closed
        if (!$hoursForDay) {
            $dayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$dayOfWeek];
            return [
                'is_open' => false, 
                'reason' => "Tutup pada hari {$dayName}"
            ];
        }

        // Parse times (handle both datetime and time string formats)
        $openTime = $hoursForDay['open_time'];
        $closeTime = $hoursForDay['close_time'];
        
        // Extract time if datetime format
        if (strlen($openTime) > 5) {
            $openTime = substr($openTime, 11, 5);
        }
        if (strlen($closeTime) > 5) {
            $closeTime = substr($closeTime, 11, 5);
        }

        $timeMinutes = $this->timeToMinutes($time);
        $openMinutes = $this->timeToMinutes($openTime);
        $closeMinutes = $this->timeToMinutes($closeTime);

        // Check if time is within opening hours
        if ($timeMinutes >= $openMinutes && $timeMinutes <= $closeMinutes) {
            return ['is_open' => true, 'reason' => null];
        }

        return [
            'is_open' => false,
            'reason' => "Jam operasional: {$openTime}-{$closeTime}"
        ];
    }

    /**
     * Show the generated itinerary result
     */
    public function result()
    {
        if (!session()->has('itinerary_result')) {
            return redirect()->route('itinerary.create')
                ->with('error', 'Tidak ada data itinerary. Silakan buat itinerary terlebih dahulu.');
        }

        $data = session('itinerary_result');
        
        return view('itinerary.result', [
            'route' => $data['route'],
            'dpSteps' => $data['dp_steps'],
            'distanceMatrix' => $data['distance_matrix'],
            'startLocation' => $data['start_location'],
            'endLocation' => $data['end_location'],
            'settings' => $data['settings'],
            'alerts' => $data['alerts'] ?? [],
        ]);
    }
}
