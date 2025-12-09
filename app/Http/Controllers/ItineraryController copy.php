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
     * Menampilkan form untuk membuat itinerary
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil semua item trip cart dengan data tourism
        $tripCartItems = TripCart::where('user_id', Auth::id())
            ->with(['tourism.files', 'tourism.prices', 'tourism.categories'])
            ->get();

        return view('itinerary.create', compact('tripCartItems'));
    }

    /**
     * Generate itinerary berdasarkan preferensi user
     */
    public function store(Request $request)
    {
        // Validasi input dari user
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
            'durations' => 'required|array',
            'durations.*' => 'required|integer|min:15|max:480',
            'accommodation_name' => 'nullable|string',
            'accommodation_latitude' => 'nullable|numeric',
            'accommodation_longitude' => 'nullable|numeric',
        ]);

        // Ambil data lokasi wisata beserta jam operasionalnya
        $tourismIds = $validated['tourism_ids'];
        $tourismLocations = Tourism::whereIn('id', $tourismIds)
            ->with('hours')
            ->get()
            ->keyBy('id');

        // Tambahkan durasi kunjungan dari input user ke setiap lokasi wisata
        foreach ($tourismLocations as $id => $tourism) {
            $tourism->stay_duration = $validated['durations'][$id] ?? 60; // default 60 jika tidak ada
        }

        // Siapkan lokasi awal
        if ($validated['start_location_type'] === 'destination') {
            // Jika lokasi awal adalah destinasi wisata
            $startTourism = $tourismLocations[$request->start_destination_id];
            $startLocation = [
                'id' => 'start_' . $startTourism->id,
                'name' => $startTourism->name,
                'latitude' => $startTourism->latitude,
                'longitude' => $startTourism->longitude,
            ];
        } else {
            // Jika lokasi awal adalah lokasi custom (koordinat manual)
            $startLocation = [
                'id' => 'start_custom',
                'name' => $request->start_location_name ?? 'Titik Awal',
                'latitude' => $request->start_latitude,
                'longitude' => $request->start_longitude,
            ];
        }

        // Siapkan lokasi akhir
        if ($validated['end_location_type'] === 'destination') {
            // Jika lokasi akhir adalah destinasi wisata
            $endTourism = $tourismLocations[$request->end_destination_id];
            $endLocation = [
                'id' => 'end_' . $endTourism->id,
                'name' => $endTourism->name,
                'latitude' => $endTourism->latitude,
                'longitude' => $endTourism->longitude,
            ];
        } else {
            // Jika lokasi akhir adalah lokasi custom
            $endLocation = [
                'id' => 'end_custom',
                'name' => $request->end_location_name ?? 'Titik Akhir',
                'latitude' => $request->end_latitude,
                'longitude' => $request->end_longitude,
            ];
        }

        // Siapkan lokasi akomodasi (untuk trip multi-hari)
        $accommodationLocation = null;
        if ($validated['duration_days'] > 1 && $request->accommodation_latitude && $request->accommodation_longitude) {
            $accommodationLocation = [
                'id' => 'accommodation',
                'name' => $request->accommodation_name ?? 'Lokasi Penginapan',
                'latitude' => $request->accommodation_latitude,
                'longitude' => $request->accommodation_longitude,
            ];
        }

        // Bangun matriks jarak dengan caching
        $distanceMatrix = $this->buildDistanceMatrix($tourismLocations, $startLocation, $endLocation, $accommodationLocation);

        // Ambil tanggal mulai untuk perhitungan hari dalam seminggu
        $startDate = $validated['start_date'] ?? now()->format('Y-m-d');

        // Implementasi Dynamic Programming untuk mencari rute optimal
        $optimalRoute = $this->calculateOptimalRoute(
            $tourismLocations,
            $distanceMatrix,
            $startLocation,
            $endLocation,
            $validated['duration_days'],
            $validated['start_time'],
            $validated['end_time'],
            $startDate,
            $accommodationLocation
        );
        // dd($optimalRoute);

        // Simpan hasil di session untuk ditampilkan di halaman result
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
     * Membangun matriks jarak dengan caching ke database
     */
    private function buildDistanceMatrix($tourismLocations, $startLocation, $endLocation, $accommodationLocation = null)
    {
        $matrix = [];
        $allLocations = collect([]);

        // Tambahkan lokasi awal
        $allLocations->push($startLocation);

        // Tambahkan semua lokasi wisata
        foreach ($tourismLocations as $tourism) {
            $allLocations->push([
                'id' => $tourism->id,
                'name' => $tourism->name,
                'latitude' => $tourism->latitude,
                'longitude' => $tourism->longitude,
            ]);
        }

        // Tambahkan lokasi akhir
        $allLocations->push($endLocation);
        
        // Tambahkan lokasi akomodasi jika ada
        if ($accommodationLocation !== null) {
            $allLocations->push($accommodationLocation);
        }

        // Bangun matriks jarak antar lokasi
        foreach ($allLocations as $from) {
            $matrix[$from['id']] = [];

            foreach ($allLocations as $to) {
                if ($from['id'] === $to['id']) {
                    // Jarak dari lokasi ke dirinya sendiri = 0
                    $matrix[$from['id']][$to['id']] = [
                        'distance' => 0,
                        'duration' => 0,
                    ];
                    continue;
                }

                // Ambil data jarak dan durasi (dengan caching)
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
     * Ambil data jarak dengan caching ke database
     */
    private function getDistanceWithCache($fromId, $toId, $fromLat, $fromLon, $toLat, $toLon)
    {
        // Hanya cache untuk jarak antar wisata (kedua ID berupa angka)
        if (is_numeric($fromId) && is_numeric($toId)) {
            // Cek apakah jarak sudah ada di cache
            $cache = DistanceCache::where('from_id', $fromId)
                ->where('to_id', $toId)
                ->first();

            if ($cache) {
                // Jika sudah ada di cache, langsung pakai
                return [
                    'distance' => $cache->distance,
                    'duration' => $cache->duration,
                    'cached' => true,
                ];
            }

            // Hitung jarak menggunakan API
            $result = $this->calculateDistanceAndDuration($fromLat, $fromLon, $toLat, $toLon);

            // Simpan ke cache untuk penggunaan berikutnya
            try {
                DistanceCache::create([
                    'from_id' => $fromId,
                    'to_id' => $toId,
                    'distance' => $result['distance'],
                    'duration' => $result['duration'],
                ]);
            } catch (\Exception $e) {
                // Abaikan error duplicate key
            }

            $result['cached'] = false;
            return $result;
        }

        // Untuk lokasi custom, hitung tanpa caching
        return $this->calculateDistanceAndDuration($fromLat, $fromLon, $toLat, $toLon);
    }

    /**
     * Hitung jarak dan durasi menggunakan API atau fallback
     */
    private function calculateDistanceAndDuration($lat1, $lon1, $lat2, $lon2)
    {
        try {
            // API Key OpenRouteService
            $apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0';

            // Bangun URL untuk API OpenRouteService
            $url = "https://api.openrouteservice.org/v2/directions/driving-car";
            $url .= "?api_key={$apiKey}";
            $url .= "&start={$lon1},{$lat1}";
            $url .= "&end={$lon2},{$lat2}";

            // Request ke API menggunakan cURL
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

            // Jika request berhasil
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);

                if (isset($data['features'][0]['properties']['segments'][0])) {
                    $segment = $data['features'][0]['properties']['segments'][0];
                    return [
                        'distance' => (int) $segment['distance'], // dalam meter
                        'duration' => (int) $segment['duration'], // dalam detik
                    ];
                }
            }

            // Jika API gagal, gunakan Haversine sebagai fallback
            return $this->calculateDistanceHaversineFallback($lat1, $lon1, $lat2, $lon2);
        } catch (\Exception $e) {
            // Jika terjadi error, gunakan Haversine
            return $this->calculateDistanceHaversineFallback($lat1, $lon1, $lat2, $lon2);
        }
    }

    /**
     * Rumus Haversine sebagai fallback (mengembalikan jarak dalam meter dan estimasi durasi)
     */
    private function calculateDistanceHaversineFallback($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = (int) ($earthRadius * $c);

        // Estimasi durasi: asumsikan kecepatan rata-rata 40 km/jam
        $duration = (int) (($distance / 1000) / 40 * 3600);

        return [
            'distance' => $distance,
            'duration' => $duration,
        ];
    }



    // ========================================================================
    // FUNGSI UTAMA: calculateOptimalRoute (Algoritma Dynamic Programming)
    // ========================================================================
    /**
     * Menghitung rute optimal menggunakan Dynamic Programming
     * 
     * KONSEP DP:
     * - State: dp[hari][posisi][mask] = waktu paling awal bisa meninggalkan lokasi tersebut
     * - hari: hari ke-berapa dalam perjalanan (1 sampai N)
     * - posisi: index destinasi terakhir yang dikunjungi (0 sampai n-1)
     * - mask: bitmask yang merepresentasikan destinasi mana saja yang sudah dikunjungi
     *         Contoh: jika ada 3 destinasi
     *         - mask = 000 (binary) = 0 (decimal) = belum ada yang dikunjungi
     *         - mask = 101 (binary) = 5 (decimal) = destinasi 0 dan 2 sudah dikunjungi
     *         - mask = 111 (binary) = 7 (decimal) = semua destinasi sudah dikunjungi
     * 
     * TRANSISI:
     * Dari state dp[hari][pos][mask], kita coba transisi ke destinasi berikutnya (next) yang belum dikunjungi
     * dengan mempertimbangkan:
     * 1. Waktu perjalanan dari pos ke next
     * 2. Jam buka destinasi next
     * 3. Durasi kunjungan di destinasi next
     * 4. Batas waktu harian (start_time sampai end_time)
     */
    private function calculateOptimalRoute($tourismLocations, $distanceMatrix, $startLocation, $endLocation, $days, $startTime, $endTime, $startDate = null, $accommodationLocation = null)
    {
        // Array untuk menyimpan langkah-langkah DP (untuk debugging/visualisasi)
        $dpSteps = [];

        // STEP 1: INISIALISASI PARAMETER
        // Konversi waktu ke menit (misal: 08:00 = 480 menit, 18:00 = 1080 menit)
        $startMinutesOfDay = $this->timeToMinutes($startTime); // contoh: 480 (08:00)
        $endMinutesOfDay   = $this->timeToMinutes($endTime);   // contoh: 1080 (18:00)
        $dailyAvailableMinutes = $endMinutesOfDay - $startMinutesOfDay; // waktu tersedia per hari

        // Hitung hari dalam seminggu untuk tanggal mulai (0=Minggu, 1=Senin, ..., 6=Sabtu)
        $startDate = $startDate ?? now()->format('Y-m-d');
        $startDow = (int)date('w', strtotime($startDate));

        $dpSteps[] = [
            'step' => 'INIT',
            'description' => 'Inisialisasi parameter awal',
            'data' => compact('days', 'startDate', 'startDow', 'startTime', 'endTime', 'startMinutesOfDay', 'endMinutesOfDay', 'dailyAvailableMinutes'),
        ];

        // STEP 2: PERSIAPAN DATA LOKASI
        // Ubah collection menjadi array biasa dengan index 0, 1, 2, ...
        $locations = $tourismLocations->values()->toArray();
        $n = count($locations); // jumlah destinasi

        // Buat mapping dari ID destinasi ke index array
        // Contoh: [1 => 0, 5 => 1, 8 => 2] artinya destinasi ID 1 ada di index 0, dst
        $idToIndex = [];
        for ($i = 0; $i < $n; $i++) {
            $idToIndex[$locations[$i]['id']] = $i;
            
            // Ambil stay_duration dari object Tourism (yang sudah di-assign di store())
            // atau dari validated durations sebagai fallback
            $tourismId = $locations[$i]['id'];
            if (isset($tourismLocations[$tourismId]) && isset($tourismLocations[$tourismId]->stay_duration)) {
                $locations[$i]['stay_duration'] = $tourismLocations[$tourismId]->stay_duration;
            } else {
                // Fallback: ambil dari validated durations atau default 60
                $locations[$i]['stay_duration'] = 60; // akan di-override di bawah jika ada
            }
        }

        // Cek apakah lokasi start/end adalah salah satu destinasi wisata
        $startIdx = null;
        $endIdx = null;
        
        // Cek apakah start location adalah destinasi (tourism) dengan memeriksa ID
        if ($startLocation['id'] && isset($idToIndex[$startLocation['id']])) {
            $startIdx = $idToIndex[$startLocation['id']];
        } elseif (strpos($startLocation['id'], 'start_') === 0) {
            // Extract ID tourism dari format 'start_123'
            $tourismId = (int) str_replace('start_', '', $startLocation['id']);
            if (isset($idToIndex[$tourismId])) {
                $startIdx = $idToIndex[$tourismId];
            }
        }
        
        // Cek apakah end location adalah destinasi (tourism)
        if ($endLocation['id'] && isset($idToIndex[$endLocation['id']])) {
            $endIdx = $idToIndex[$endLocation['id']];
        } elseif (strpos($endLocation['id'], 'end_') === 0) {
            // Extract ID tourism dari format 'end_123'
            $tourismId = (int) str_replace('end_', '', $endLocation['id']);
            if (isset($idToIndex[$tourismId])) {
                $endIdx = $idToIndex[$tourismId];
            }
        }
        
        // Jika start/end adalah lokasi custom (bukan tourism), itu OK
        // Kita akan handle secara terpisah dalam algoritma DP

        $dpSteps[] = [
            'step' => 'LOC_PREP',
            'description' => 'Persiapan daftar lokasi dan mapping index',
            'data' => [
                'n' => $n,
                'locations' => array_map(function($l, $i) {
                    return [
                        'index' => $i, 
                        'id' => $l['id'], 
                        'name' => $l['name'],
                        'stay_duration' => $l['stay_duration'] ?? 'NOT SET'
                    ];
                }, $locations, array_keys($locations)),
            ],
        ];

        // STEP 3: INISIALISASI TABEL DP
        // FULL mask = semua bit 1, artinya semua destinasi sudah dikunjungi
        // Contoh: jika n=3, FULL = (1 << 3) - 1 = 8 - 1 = 7 = 111 (binary)
        $FULL = (1 << $n) - 1;
        
        // INF = nilai tak terhingga (untuk state yang tidak mungkin)
        $INF = PHP_INT_MAX / 4;

        // Tabel DP: dp[hari][posisi][mask] = waktu paling awal bisa meninggalkan lokasi (dalam menit sejak 00:00)
        // parent[hari][posisi][mask] = menyimpan state sebelumnya untuk backtracking rute
        $dp = [];
        $parent = [];

        // Inisialisasi semua state dengan nilai INF (tidak mungkin/belum diproses)
        for ($d = 1; $d <= $days; $d++) {
            for ($i = 0; $i < $n; $i++) {
                for ($mask = 0; $mask <= $FULL; $mask++) {
                    $dp[$d][$i][$mask] = $INF;
                    $parent[$d][$i][$mask] = null;
                }
            }
        }

        // STEP 4: INISIALISASI BASE CASE (Hari pertama)
        // Kita perlu set state awal untuk hari pertama
        
        if ($startIdx !== null) {
            // CASE A: Start adalah destinasi wisata - kunjungi sebagai destinasi pertama
            $arrive = $startMinutesOfDay;
            $dow = $startDow;
            
            // Cek jam operasional destinasi start
            $openMin = null;
            $closeMin = null;
            if (isset($locations[$startIdx]['hours']) && is_array($locations[$startIdx]['hours'])) {
                foreach ($locations[$startIdx]['hours'] as $h) {
                    if ((int)$h['day'] === $dow) {
                        $openMin = $this->timeToMinutes($h['open_time']);
                        $closeMin = $this->timeToMinutes($h['close_time']);
                        break;
                    }
                }
            }
            
            // Jika tiba sebelum buka, tunggu sampai buka
            if ($openMin !== null && $arrive < $openMin) {
                $arrive = $openMin;
            }
            
            // Hitung durasi stay di destinasi start
            $stayMin = intval($locations[$startIdx]['stay_duration']);
            $leave = $arrive + $stayMin;
            
            // VALIDASI: Pastikan bisa selesai kunjungan dalam batas waktu
            $validVisit = true;
            
            // Cek apakah waktu keluar melewati batas waktu harian
            if ($leave > $endMinutesOfDay) {
                $validVisit = false;
            }
            
            // Cek apakah waktu keluar melewati jam tutup wisata
            if ($closeMin !== null && $leave > $closeMin) {
                $validVisit = false;
            }
            
            $dpSteps[] = [
                'step' => 'BASE_CASE_START_DEST',
                'description' => 'Base case: Start adalah destinasi wisata',
                'data' => [
                    'startIdx' => $startIdx,
                    'tourism_id' => $locations[$startIdx]['id'],
                    'tourism_name' => $locations[$startIdx]['name'],
                    'arrive' => $arrive,
                    'stay_duration' => $stayMin,
                    'leave' => $leave,
                    'endMinutesOfDay' => $endMinutesOfDay,
                    'closeMin' => $closeMin,
                    'validVisit' => $validVisit,
                ],
            ];
            
            // Hanya set jika valid, jika tidak valid maka tidak ada solusi dari start ini
            if ($validVisit) {
                $startMask = (1 << $startIdx); // set bit ke-startIdx menjadi 1
                $dp[1][$startIdx][$startMask] = $leave; // waktu keluar setelah stay
                $parent[1][$startIdx][$startMask] = null; // tidak ada parent (ini start)
            }
        } else {
            // CASE B: Start adalah lokasi custom - inisialisasi transisi ke semua destinasi pertama
            for ($i = 0; $i < $n; $i++) {
                // Hitung waktu perjalanan dari start ke destinasi i
                $travelMin = isset($distanceMatrix[$startLocation['id']][$locations[$i]['id']]['duration'])
                    ? ceil($distanceMatrix[$startLocation['id']][$locations[$i]['id']]['duration'] / 60)
                    : 0;
                
                // Waktu tiba di destinasi i
                $arrive = $startMinutesOfDay + $travelMin;
                $dow = $startDow;
                
                // Cek jam operasional destinasi i
                $openMin = null;
                $closeMin = null;
                if (isset($locations[$i]['hours']) && is_array($locations[$i]['hours'])) {
                    foreach ($locations[$i]['hours'] as $h) {
                        if ((int)$h['day'] === $dow) {
                            $openMin = $this->timeToMinutes($h['open_time']);
                            $closeMin = $this->timeToMinutes($h['close_time']);
                            break;
                        }
                    }
                }
                
                // Jika tiba sebelum buka, tunggu sampai buka
                if ($openMin !== null && $arrive < $openMin) {
                    $arrive = $openMin;
                }
                
                // Jika tiba setelah tutup, tidak bisa kunjungi hari ini
                if ($closeMin !== null && $arrive > $closeMin) {
                    continue;
                }
                
                // Hitung waktu tinggal dari input user (sudah ada di stay_duration)
                $stayMin = intval($locations[$i]['stay_duration']);
                $leave = $arrive + $stayMin; // waktu meninggalkan destinasi
                
                // VALIDASI: Pastikan waktu keluar tidak melebihi batas waktu harian DAN jam tutup wisata
                $validVisit = true;
                
                // Cek apakah waktu keluar melewati batas waktu harian
                if ($leave > $endMinutesOfDay) {
                    $validVisit = false;
                }
                
                // Cek apakah waktu keluar melewati jam tutup wisata
                if ($closeMin !== null && $leave > $closeMin) {
                    $validVisit = false;
                }
                
                if ($validVisit) {
                    $mask = (1 << $i); // set bit destinasi i sebagai sudah dikunjungi
                    $dp[1][$i][$mask] = $leave;
                    $parent[1][$i][$mask] = ['start', null, 0, $arrive, $leave];
                }
            }
        }

        // STEP 5: ALGORITMA DP CORE (LOOP UTAMA)
        // Loop untuk setiap hari, setiap mask (kombinasi destinasi), dan setiap posisi
        for ($day = 1; $day <= $days; $day++) {
            for ($mask = 0; $mask <= $FULL; $mask++) {
                for ($pos = 0; $pos < $n; $pos++) {
                    // Ambil waktu paling awal kita bisa meninggalkan posisi saat ini
                    $currTime = $dp[$day][$pos][$mask];
                    
                    // Jika state ini tidak valid (belum pernah diproses), skip
                    if ($currTime >= $INF) continue;

                    // Coba transisi ke setiap destinasi berikutnya yang belum dikunjungi
                    for ($next = 0; $next < $n; $next++) {
                        // Skip jika destinasi next sudah dikunjungi (bit ke-next di mask sudah 1)
                        if ($mask & (1 << $next)) continue;

                        // Hitung durasi perjalanan dari pos ke next (dalam menit, dibulatkan ke atas)
                        $travelMin = isset($distanceMatrix[$locations[$pos]['id']][$locations[$next]['id']]['duration'])
                            ? ceil($distanceMatrix[$locations[$pos]['id']][$locations[$next]['id']]['duration'] / 60)
                            : 0;

                        // Estimasi waktu tiba di destinasi next (masih di hari yang sama dulu)
                        $arrive = $currTime + $travelMin;

                        // Hitung hari dalam seminggu untuk hari ini
                        $dow = ($startDow + ($day - 1)) % 7;

                        // Cek jam operasional destinasi next
                        $openCheck = $this->isTourismOpen($locations[$next], $dow, $arrive);
                        $openMin = null;
                        $closeMin = null;
                        
                        if (isset($locations[$next]['hours']) && is_array($locations[$next]['hours'])) {
                            // Cari data jam operasional untuk hari ini
                            foreach ($locations[$next]['hours'] as $h) {
                                if ((int)$h['day'] === $dow) {
                                    $openMin = $this->timeToMinutes($h['open_time']);
                                    $closeMin = $this->timeToMinutes($h['close_time']);
                                    break;
                                }
                            }
                        }

                        // Jika tiba sebelum buka, tunggu sampai buka
                        if ($openMin !== null && $arrive < $openMin) {
                            $arrive = $openMin;
                        }

                        // Hitung durasi kunjungan dari input user (sudah ada di stay_duration)
                        $stayMin = intval($locations[$next]['stay_duration']);
                        $leave = $arrive + $stayMin; // waktu meninggalkan destinasi next

                        // VALIDASI LENGKAP: Cek apakah bisa kunjungi di hari yang sama
                        $sameDayPossible = true;
                        
                        // 1. Cek apakah tiba setelah tutup
                        if ($closeMin !== null && $arrive > $closeMin) {
                            $sameDayPossible = false;
                        }
                        
                        // 2. Cek apakah waktu keluar melewati jam tutup wisata
                        if ($closeMin !== null && $leave > $closeMin) {
                            $sameDayPossible = false;
                        }
                        
                        // 3. Cek apakah waktu keluar melewati batas waktu harian user
                        if ($leave > $endMinutesOfDay) {
                            $sameDayPossible = false;
                        }

                        // CASE A: Bisa kunjungi di hari yang sama
                        if ($sameDayPossible) {
                            // Update mask: tambahkan bit ke-next
                            $newMask = $mask | (1 << $next);
                            
                            // Update state jika lebih baik (waktu keluar lebih awal)
                            if ($leave < $dp[$day][$next][$newMask]) {
                                $dp[$day][$next][$newMask] = $leave;
                                $parent[$day][$next][$newMask] = [$day, $pos, $mask, $arrive, $leave];
                            }
                        } else {
                            // CASE B: Tidak bisa kunjungi hari ini, coba hari berikutnya
                            if ($day < $days) {
                                $nextDay = $day + 1;
                                
                                // Di hari berikutnya, kita mulai dari startMinutesOfDay (misal 08:00)
                                // Travel time dihitung dari lokasi yang tepat:
                                // - Jika ada akomodasi: dari akomodasi ke next
                                // - Jika tidak ada: dari pos (lokasi terakhir hari sebelumnya) ke next
                                if ($accommodationLocation !== null) {
                                    // Travel dari akomodasi ke destinasi next
                                    $travelMinFromAccommodation = isset($distanceMatrix[$accommodationLocation['id']][$locations[$next]['id']]['duration'])
                                        ? ceil($distanceMatrix[$accommodationLocation['id']][$locations[$next]['id']]['duration'] / 60)
                                        : 0;
                                    $arriveNext = $startMinutesOfDay + $travelMinFromAccommodation;
                                } else {
                                    // Travel dari lokasi terakhir hari sebelumnya
                                    $arriveNext = $startMinutesOfDay + $travelMin;
                                }
                                
                                // Hitung hari dalam seminggu untuk hari berikutnya
                                $dowNext = ($startDow + ($nextDay - 1)) % 7;

                                // Cek jam operasional di hari berikutnya
                                $openMinNext = null;
                                $closeMinNext = null;
                                
                                if (isset($locations[$next]['hours']) && is_array($locations[$next]['hours'])) {
                                    foreach ($locations[$next]['hours'] as $h) {
                                        if ((int)$h['day'] === $dowNext) {
                                            $openMinNext = $this->timeToMinutes($h['open_time']);
                                            $closeMinNext = $this->timeToMinutes($h['close_time']);
                                            break;
                                        }
                                    }
                                }
                                
                                // Jika tiba sebelum buka, tunggu sampai buka
                                if ($openMinNext !== null && $arriveNext < $openMinNext) {
                                    $arriveNext = $openMinNext;
                                }
                                
                                // Jika tiba setelah tutup, tidak bisa kunjungi sama sekali
                                if ($closeMinNext !== null && $arriveNext > $closeMinNext) {
                                    continue;
                                }
                                
                                $leaveNext = $arriveNext + $stayMin;
                                
                                // VALIDASI LENGKAP untuk hari berikutnya:
                                // 1. Pastikan waktu keluar tidak melebihi batas waktu harian user
                                if ($leaveNext > $endMinutesOfDay) {
                                    continue;
                                }
                                
                                // 2. Pastikan waktu keluar tidak melewati jam tutup wisata
                                if ($closeMinNext !== null && $leaveNext > $closeMinNext) {
                                    continue;
                                }

                                // Update state untuk hari berikutnya
                                $newMask = $mask | (1 << $next);
                                if ($leaveNext < $dp[$nextDay][$next][$newMask]) {
                                    $dp[$nextDay][$next][$newMask] = $leaveNext;
                                    $parent[$nextDay][$next][$newMask] = [$day, $pos, $mask, $arriveNext, $leaveNext];
                                }
                            }
                        }
                    } // end loop next
                } // end loop pos
            } // end loop mask
        } // end loop day

        // STEP 6: CARI SOLUSI TERBAIK (Best Ending State)
        // Kita cari state dengan mask = FULL (semua destinasi sudah dikunjungi)
        // dan waktu keluar paling awal
        
        $bestDay = -1;
        $bestLeave = $INF;
        $bestPos = -1;
        
        if ($endIdx !== null) {
            // CASE A: End adalah destinasi wisata - harus berakhir di endIdx
            for ($d = 1; $d <= $days; $d++) {
                if ($dp[$d][$endIdx][$FULL] < $bestLeave) {
                    $bestLeave = $dp[$d][$endIdx][$FULL];
                    $bestDay = $d;
                    $bestPos = $endIdx;
                }
            }
        } else {
            // CASE B: End adalah lokasi custom - bisa berakhir di destinasi mana saja
            // lalu travel ke end location
            for ($d = 1; $d <= $days; $d++) {
                for ($pos = 0; $pos < $n; $pos++) {
                    if ($dp[$d][$pos][$FULL] < $INF) {
                        // Cek apakah bisa travel ke end location dalam batas waktu
                        $travelMin = isset($distanceMatrix[$locations[$pos]['id']][$endLocation['id']]['duration'])
                            ? ceil($distanceMatrix[$locations[$pos]['id']][$endLocation['id']]['duration'] / 60)
                            : 0;
                        
                        $arriveEnd = $dp[$d][$pos][$FULL] + $travelMin;
                        
                        if ($arriveEnd <= $endMinutesOfDay && $arriveEnd < $bestLeave) {
                            $bestLeave = $arriveEnd;
                            $bestDay = $d;
                            $bestPos = $pos;
                        }
                    }
                }
            }
        }

        // Jika tidak ada solusi valid
        if ($bestDay === -1) {
            $dpSteps[] = [
                'step' => 'NO_VALID_ROUTE',
                'description' => 'Tidak ditemukan rute valid dalam batas hari / jam buka',
                'data' => [],
            ];
            return [
                'result' => [
                    'days' => [],
                    'total_distance' => 0,
                    'total_duration' => 0,
                    'message' => 'Tidak ada rute valid yang memenuhi constraints',
                ],
                'dp_steps' => $dpSteps,
                'alerts' => [
                    'Tidak ada rute valid yang memenuhi constraints waktu dan jam operasional'
                ],
            ];
        }

        // STEP 7: BACKTRACKING - Rekonstruksi rute dari parent
        // Mulai dari state terbaik, telusuri balik ke awal
        $route = [];
        $d = $bestDay;
        $pos = $bestPos;
        $mask = $FULL;

        while (true) {
            // Simpan informasi state saat ini
            $route[] = ['day' => $d, 'pos' => $pos, 'time' => $dp[$d][$pos][$mask]];
            
            // Ambil parent state
            $p = $parent[$d][$pos][$mask];
            
            // Jika sudah sampai start, berhenti
            if ($p === null || $p === 'start' || (is_array($p) && $p[0] === 'start')) break;
            
            // Extract informasi parent: [prevDay, prevPos, prevMask, arrivalTime, leaveTime]
            [$pd, $ppos, $pmask, $parrive, $pleave] = $p;
            $d = $pd;
            $pos = $ppos;
            $mask = $pmask;
        }

        // Balik urutan rute (dari start ke end)
        $route = array_reverse($route);

        // STEP 8: BANGUN RENCANA PER HARI (Build Per-Day Plan)
        // Transform hasil DP menjadi format yang mudah dibaca
        $result = ['days' => [], 'total_distance' => 0, 'total_duration' => 0];
        $currentDay = 1;
        $dayDow = $startDow;
        $dayPlan = [
            'day' => 1,
            'day_of_week' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$dayDow],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'start_location' => $startLocation,
            'locations' => [],
            'total_distance' => 0,
            'total_duration' => 0
        ];

        // Lokasi saat ini dimulai dari startLocation
        $currLoc = $startLocation;
        $routeIdx = 0;
        
        // Tracking waktu per hari - reset setiap hari baru
        $currentDayTime = $startMinutesOfDay; // waktu saat ini dalam hari (reset per hari)

        // Loop setiap destinasi dalam rute
        foreach ($route as $step) {
            $stepDay = $step['day']; // hari dari hasil DP
            $posIdx = $step['pos'];
            $loc = $locations[$posIdx];

            // Jika hari berganti, simpan dayPlan sebelumnya dan mulai dayPlan baru
            if ($stepDay > $currentDay) {
                // Jika multi-hari dan ada akomodasi, tambahkan perjalanan ke akomodasi di akhir hari
                if ($accommodationLocation !== null) {
                    $distToAccommodation = $distanceMatrix[$currLoc['id']][$accommodationLocation['id']] ?? ['distance' => 0, 'duration' => 0];
                    $dayPlan['total_distance'] += ($distToAccommodation['distance'] ?? 0);
                    $dayPlan['total_duration'] += ($distToAccommodation['duration'] ?? 0);
                    $dayPlan['end_location'] = $accommodationLocation; // end di akomodasi
                    
                    // Update currLoc ke akomodasi untuk start hari berikutnya
                    $currLoc = $accommodationLocation;
                } else {
                    // Jika tidak ada akomodasi, end di lokasi terakhir
                    $dayPlan['end_location'] = $currLoc;
                }
                
                $result['days'][] = $dayPlan;
                $result['total_distance'] += $dayPlan['total_distance'];
                $result['total_duration'] += $dayPlan['total_duration'];

                // Mulai hari baru
                $currentDay = $stepDay;
                $dayDow = ($startDow + ($currentDay - 1)) % 7;
                $dayPlan = [
                    'day' => $currentDay,
                    'day_of_week' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$dayDow],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'start_location' => $currLoc, // start dari akomodasi (atau lokasi terakhir jika tidak ada akomodasi)
                    'locations' => [],
                    'total_distance' => 0,
                    'total_duration' => 0
                ];
                
                // RESET waktu untuk hari baru - mulai dari start time
                $currentDayTime = $startMinutesOfDay;
            }

            // Hitung data perjalanan dari lokasi sebelumnya
            $distData = $distanceMatrix[$currLoc['id']][$loc['id']] ?? ['distance' => 0, 'duration' => 0];
            $travelMin = ceil(($distData['duration'] ?? 0) / 60);
            
            // Update waktu: tambahkan travel time
            $currentDayTime += $travelMin;
            
            // Cek jam buka destinasi dan adjust arrival time jika perlu
            $dow = ($startDow + ($currentDay - 1)) % 7;
            if (isset($loc['hours']) && is_array($loc['hours'])) {
                foreach ($loc['hours'] as $h) {
                    if ((int)$h['day'] === $dow) {
                        $openMin = $this->timeToMinutes($h['open_time']);
                        // Jika tiba sebelum buka, tunggu sampai buka
                        if ($currentDayTime < $openMin) {
                            $currentDayTime = $openMin;
                        }
                        break;
                    }
                }
            }
            
            $arrival = $currentDayTime;

            // Format waktu tiba sebagai string HH:MM
            $arrivalStr = sprintf('%02d:%02d', floor($arrival / 60) % 24, $arrival % 60);

            // Tambahkan ke rencana hari ini
            $dayPlan['locations'][] = [
                'tourism' => $loc,
                'arrival_time' => $arrivalStr,
                'distance_from_prev' => $distData['distance'] ?? 0,
                'travel_minutes' => $travelMin,
            ];

            $dayPlan['total_distance'] += ($distData['distance'] ?? 0);
            $dayPlan['total_duration'] += ($distData['duration'] ?? 0);
            
            // Update waktu: tambahkan durasi kunjungan
            $stayMin = intval($loc['stay_duration']);
            $currentDayTime += $stayMin;

            $currLoc = $loc;
            $routeIdx++;
        }

        // Finalisasi hari terakhir: tambahkan perjalanan ke endLocation jika berbeda
        $lastLoc = $currLoc;
        
        if ($endLocation['id'] !== $lastLoc['id']) {
            $distData = $distanceMatrix[$lastLoc['id']][$endLocation['id']] ?? ['distance' => 0, 'duration' => 0];
            $dayPlan['total_distance'] += ($distData['distance'] ?? 0);
            $dayPlan['total_duration'] += ($distData['duration'] ?? 0);
        }
        
        $dayPlan['end_location'] = $endLocation;
        $result['days'][] = $dayPlan;
        $result['total_distance'] += $dayPlan['total_distance'];
        $result['total_duration'] += $dayPlan['total_duration'];

        $dpSteps[] = [
            'step' => 'FINAL',
            'description' => 'Rute ditemukan dan dibagi per-hari',
            'data' => [
                'used_days' => count($result['days']),
                'route' => array_map(fn($s) => $s['tourism']['name'], $dayPlan['locations']),
                'total_distance_km' => number_format($result['total_distance'] / 1000, 2),
            ],
        ];

        return [
            'result' => $result,
            'dp_steps' => $dpSteps,
            'alerts' => [],
        ];
    }


    /**
     * Konversi string waktu ke menit
     * Contoh: "08:30" -> 510 menit
     */
    private function timeToMinutes($time)
    {
        list($hours, $minutes) = explode(':', $time);
        return ($hours * 60) + $minutes;
    }

    /**
     * Cek apakah tempat wisata buka pada waktu tertentu
     * @param array $location Lokasi tourism dengan data jam operasional
     * @param int $dayOfWeek Hari dalam seminggu (0=Minggu, 1=Senin, ..., 6=Sabtu)
     * @param string $time Waktu dalam format H:i
     * @return array ['is_open' => bool, 'reason' => string|null]
     */
    private function isTourismOpen($location, $dayOfWeek, $time)
    {
        // Jika tidak ada data jam operasional, asumsikan selalu buka
        if (!isset($location['hours']) || empty($location['hours'])) {
            return ['is_open' => true, 'reason' => null];
        }

        // Cari data jam operasional untuk hari spesifik
        $hoursForDay = null;
        foreach ($location['hours'] as $hour) {
            if ($hour['day'] == $dayOfWeek) {
                $hoursForDay = $hour;
                break;
            }
        }

        // Jika tidak ada jam operasional untuk hari ini, asumsikan tutup
        if (!$hoursForDay) {
            $dayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$dayOfWeek];
            return [
                'is_open' => false,
                'reason' => "Tutup pada hari {$dayName}"
            ];
        }

        // Parse waktu (handle format datetime maupun time string)
        $openTime = $hoursForDay['open_time'];
        $closeTime = $hoursForDay['close_time'];

        // Extract waktu jika format datetime
        if (strlen($openTime) > 5) {
            $openTime = substr($openTime, 11, 5);
        }
        if (strlen($closeTime) > 5) {
            $closeTime = substr($closeTime, 11, 5);
        }

        $timeMinutes = $this->timeToMinutes($time);
        $openMinutes = $this->timeToMinutes($openTime);
        $closeMinutes = $this->timeToMinutes($closeTime);

        // Cek apakah waktu dalam rentang jam operasional
        if ($timeMinutes >= $openMinutes && $timeMinutes <= $closeMinutes) {
            return ['is_open' => true, 'reason' => null];
        }

        return [
            'is_open' => false,
            'reason' => "Jam operasional: {$openTime}-{$closeTime}"
        ];
    }

    /**
     * Menampilkan hasil itinerary yang telah di-generate
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
