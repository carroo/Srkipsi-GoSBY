@extends('layout')

@section('title', 'Hasil Itinerary - ' . $itineraryData['name'])

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<style>
    #map {
        height: 500px;
        width: 100%;
        z-index: 1;
        position: relative;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
    }
    
    @media print {
        nav, footer, .no-print {
            display: none !important;
        }
        
        body {
            background: white;
        }
        
        .bg-gray-50 {
            background: white;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-600 via-teal-600 to-blue-700 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            <h1 class="text-3xl md:text-4xl font-black mb-2">Itinerary Berhasil Dibuat!</h1>
            <p class="text-lg text-green-100">Rute optimal telah dihitung menggunakan algoritma TSP</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Itinerary Info Card -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $itineraryData['name'] }}</h2>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Travel Date -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <label class="text-xs font-semibold text-gray-600 uppercase">Tanggal</label>
                        </div>
                        <input type="date" 
                               id="travel_date" 
                               value="{{ $itineraryData['travel_date'] }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               onchange="calculateTimes()">
                    </div>

                    <!-- Start Time -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <label class="text-xs font-semibold text-gray-600 uppercase">Waktu Mulai</label>
                        </div>
                        <input type="time" 
                               id="start_time" 
                               value="08:00" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               onchange="calculateTimes()">
                    </div>

                    <!-- Total Distance -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            <span class="text-xs font-semibold text-gray-600 uppercase">Total Jarak</span>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($itineraryData['total_distance'] / 1000, 2) }} km</p>
                    </div>

                    <!-- Total Duration -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-gray-600 uppercase">Waktu Perjalanan</span>
                        </div>
                        <p class="text-2xl font-bold text-green-600">
                            @php
                                $hours = floor($itineraryData['total_duration'] / 3600);
                                $minutes = ceil(($itineraryData['total_duration'] % 3600) / 60);
                            @endphp
                            {{ $hours }}j {{ $minutes }}m
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('itinerary.create') }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Itinerary Baru
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak
                </button>
            </div>
        </div>

        <!-- Route Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Rute Perjalanan Optimal
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Urutan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Destinasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jam Operasional
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estimasi Sampai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durasi Berkunjung
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jarak Tempuh
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu Perjalanan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($itineraryData['route']['route'] as $stop)
                            <tr class="hover:bg-gray-50 transition-colors duration-200" 
                                data-duration="{{ $stop['duration_from_previous'] }}"
                                data-order="{{ $stop['order'] }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($stop['order'] == 0)
                                            <span class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-800 rounded-full font-bold">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-800 rounded-full font-bold">
                                                {{ $stop['order'] }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 mb-1">{{ $stop['destination']['name'] }}</div>
                                    @if($stop['order'] == 0)
                                        <div class="text-xs text-green-600 font-semibold mb-1">Titik Awal</div>
                                    @endif
                                    <a href="https://www.google.com/maps?q={{ $stop['destination']['lat'] }},{{ $stop['destination']['long'] }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ number_format($stop['destination']['lat'], 6) }}°, {{ number_format($stop['destination']['long'], 6) }}°
                                    </a>
                                </td>
                                <td class="px-6 py-4 operating-hours-cell" 
                                    data-order="{{ $stop['order'] }}"
                                    @php
                                        // Check if this location has tourism data (either start point from cart or regular destination)
                                        $hasTourismData = false;
                                        $tourismData = null;
                                        
                                        if ($stop['order'] == 0 && $itineraryData['start_point']['type'] === 'tourism') {
                                            // Start point from cart - need to get tourism data
                                            $tourismData = \App\Models\Tourism::with('hours')->find($itineraryData['start_point']['id']);
                                            $hasTourismData = $tourismData && $tourismData->hours->count() > 0;
                                        } elseif ($stop['order'] > 0 && isset($stop['destination']['tourism'])) {
                                            $tourismData = $stop['destination']['tourism'];
                                            $hasTourismData = $tourismData->hours->count() > 0;
                                        }
                                    @endphp
                                    
                                    @if($hasTourismData)
                                        @php
                                            // Get day name from travel date
                                            $travelDate = \Carbon\Carbon::parse($itineraryData['travel_date']);
                                            $dayName = '';
                                            
                                            // Map Carbon day name to Indonesian
                                            switch($travelDate->dayName) {
                                                case 'Monday': $dayName = 'Senin'; break;
                                                case 'Tuesday': $dayName = 'Selasa'; break;
                                                case 'Wednesday': $dayName = 'Rabu'; break;
                                                case 'Thursday': $dayName = 'Kamis'; break;
                                                case 'Friday': $dayName = 'Jumat'; break;
                                                case 'Saturday': $dayName = 'Sabtu'; break;
                                                case 'Sunday': $dayName = 'Minggu'; break;
                                            }
                                            
                                            // Find operating hours for this day
                                            $todayHour = $tourismData->hours->firstWhere('day', $dayName);
                                        @endphp
                                        
                                        @if($todayHour)
                                            @php
                                                // Extract only HH:MM from the time
                                                $openTime = \Carbon\Carbon::parse($todayHour->open_time)->format('H:i');
                                                $closeTime = \Carbon\Carbon::parse($todayHour->close_time)->format('H:i');
                                            @endphp
                                            data-open-time="{{ $openTime }}" 
                                            data-close-time="{{ $closeTime }}"
                                            data-has-hours="true">
                                            <div class="text-sm">
                                                <div class="text-gray-900 font-semibold">
                                                    {{ $openTime }} - {{ $closeTime }}
                                                </div>
                                            </div>
                                        @else
                                            data-has-hours="false"
                                            data-closed="true">
                                            <div class="text-sm">
                                                <div class="text-red-600 font-semibold">Tutup</div>
                                            </div>
                                        @endif
                                    @else
                                        data-has-hours="false"
                                        data-open-24="true">
                                        <span class="text-sm text-gray-500" title="Tidak ada info jam operasional - dianggap buka 24 jam">
                                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="arrival-time text-sm font-semibold text-indigo-600">
                                        --:--
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        // Check if start point is from cart (has tourism data)
                                        $isStartPointTourism = $stop['order'] == 0 && $itineraryData['start_point']['type'] === 'tourism';
                                        $canHaveStayDuration = $stop['order'] > 0 || $isStartPointTourism;
                                    @endphp
                                    
                                    @if($canHaveStayDuration)
                                        <input type="number" 
                                               class="stay-duration border border-gray-300 rounded-lg px-3 py-1 w-20 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               value="60" 
                                               min="0" 
                                               step="15"
                                               onchange="calculateTimes()">
                                        <span class="text-xs text-gray-600 ml-1">menit</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($stop['distance_from_previous'] > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                            {{ number_format($stop['distance_from_previous'] / 1000, 2) }} km
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($stop['duration_from_previous'] > 0)
                                        @php
                                            $hours = floor($stop['duration_from_previous'] / 3600);
                                            $minutes = ceil(($stop['duration_from_previous'] % 3600) / 60);
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @if($hours > 0)
                                                {{ $hours }}j {{ $minutes }}m
                                            @else
                                                {{ $minutes }}m
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right font-bold text-gray-900">
                                TOTAL:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-200 text-blue-900">
                                    {{ number_format($itineraryData['total_distance'] / 1000, 2) }} km
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $totalHours = floor($itineraryData['total_duration'] / 3600);
                                    $totalMinutes = floor(($itineraryData['total_duration'] % 3600) / 60);
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-200 text-green-900">
                                    {{ $totalHours }}j {{ $totalMinutes }}m
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right font-bold text-gray-900">
                                ESTIMASI SELESAI:
                            </td>
                            <td colspan="2" class="px-6 py-4 whitespace-nowrap">
                                <span id="end-time" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-purple-200 text-purple-900">
                                    --:--
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Distance Matrix -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Matriks Jarak (km)
                </h3>
                <p class="text-purple-100 text-sm mt-1">Jarak antara setiap titik lokasi</p>
            </div>

            <div class="p-6 overflow-x-auto">
                @php
                    $allPoints = array_merge([$itineraryData['start_point']], $itineraryData['destinations']);
                    $matrix = $itineraryData['distance_matrix'];
                @endphp
                
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700">
                                Dari / Ke
                            </th>
                            @foreach($allPoints as $index => $point)
                                <th class="border border-gray-300 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-900">
                                    @if($index == 0)
                                        <div class="flex flex-col items-center">
                                            <svg class="w-5 h-5 mb-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-xs">Start</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center">
                                            <span class="text-lg">{{ $index }}</span>
                                        </div>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPoints as $i => $fromPoint)
                            <tr>
                                <td class="border border-gray-300 bg-purple-50 px-4 py-2 font-semibold text-sm text-purple-900">
                                    <div class="flex items-center gap-2">
                                        @if($i == 0)
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Start</span>
                                        @else
                                            <span class="flex items-center justify-center w-6 h-6 bg-purple-600 text-white rounded-full text-xs">
                                                {{ $i }}
                                            </span>
                                        @endif
                                        <div class="text-xs text-gray-600 max-w-[100px] truncate">
                                            {{ $fromPoint['name'] }}
                                        </div>
                                    </div>
                                </td>
                                @foreach($allPoints as $j => $toPoint)
                                    <td class="border border-gray-300 px-4 py-2 text-center text-sm
                                        @if($i == $j) bg-gray-200 text-gray-500 font-bold
                                        @else bg-white hover:bg-blue-50 transition-colors duration-200
                                        @endif">
                                        @if($i == $j)
                                            -
                                        @else
                                            {{ number_format($matrix[$i][$j] / 1000, 2) }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Keterangan:</p>
                            <ul class="space-y-1">
                                <li>• Nilai dalam matriks adalah jarak dalam kilometer (km)</li>
                                <li>• Diagonal matriks (sama dengan dirinya sendiri) ditandai dengan "-"</li>
                                <li>• Rute optimal dihitung menggunakan algoritma TSP (Traveling Salesman Problem)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Visualization -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Visualisasi Peta Rute
                </h3>
                <p class="text-red-100 text-sm mt-1">Tampilan rute perjalanan pada peta interaktif</p>
            </div>
            <div class="p-6">
                <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300"></div>
            </div>
        </div>

        <!-- Ringkasan Rute -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Ringkasan Rute
            </h3>
            <div class="space-y-3">
                @foreach($itineraryData['route']['route'] as $index => $stop)
                    <div class="flex items-center gap-4">
                        @if($index == 0)
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @else
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-600">{{ $stop['order'] }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $stop['destination']['name'] }}</h4>
                            @if($stop['distance_from_previous'] > 0)
                                <p class="text-sm text-gray-600">
                                    {{ number_format($stop['distance_from_previous'] / 1000, 2) }} km • 
                                    @php
                                        $hours = floor($stop['duration_from_previous'] / 3600);
                                        $minutes = floor(($stop['duration_from_previous'] % 3600) / 60);
                                    @endphp
                                    @if($hours > 0){{ $hours }}j @endif{{ $minutes }}m dari lokasi sebelumnya
                                </p>
                            @endif
                        </div>

                        @if($index < count($itineraryData['route']['route']) - 1)
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
@endsection


@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<script>
    // Calculate arrival times for each destination
    function calculateTimes() {
        const startTimeInput = document.getElementById('start_time');
        const startTime = startTimeInput.value;
        
        if (!startTime) {
            return;
        }
        
        // Parse start time
        const [startHour, startMinute] = startTime.split(':').map(Number);
        let currentMinutes = startHour * 60 + startMinute;
        
        // Get all rows
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach((row, index) => {
            // Get order from dataset, fallback to index if not available
            const order = row.dataset.order ? parseInt(row.dataset.order) : index;
            const arrivalTimeSpan = row.querySelector('.arrival-time');
            const operatingHoursCell = row.querySelector('.operating-hours-cell');
            
            // Skip if arrival time span is not found or order is invalid
            if (!arrivalTimeSpan || isNaN(order)) {
                return;
            }
            
            let arrivalMinutes = currentMinutes;
            let departureMinutes = currentMinutes;
            
            if (order === 0) {
                // Starting point - show start time
                arrivalTimeSpan.textContent = formatTime(currentMinutes);
                arrivalMinutes = currentMinutes;
                
                // Add stay duration if starting point has it (tourism location)
                const stayDurationInput = row.querySelector('.stay-duration');
                if (stayDurationInput) {
                    const stayMinutes = parseInt(stayDurationInput.value) || 0;
                    currentMinutes += stayMinutes;
                    departureMinutes = currentMinutes;
                } else {
                    departureMinutes = arrivalMinutes;
                }
            } else {
                // Get travel duration from previous point (in seconds)
                const travelDurationSeconds = parseInt(row.dataset.duration);
                const travelMinutes = Math.ceil(travelDurationSeconds / 60);
                
                // Add travel time
                currentMinutes += travelMinutes;
                arrivalMinutes = currentMinutes;
                
                // Show arrival time
                arrivalTimeSpan.textContent = formatTime(currentMinutes);
                
                // Get stay duration
                const stayDurationInput = row.querySelector('.stay-duration');
                if (stayDurationInput) {
                    const stayMinutes = parseInt(stayDurationInput.value) || 0;
                    currentMinutes += stayMinutes;
                    departureMinutes = currentMinutes;
                } else {
                    departureMinutes = arrivalMinutes;
                }
            }
            
            // Check if location is open during visit
            checkOperatingHours(row, operatingHoursCell, arrivalMinutes, departureMinutes);
        });
        
        // Update end time
        const endTimeSpan = document.getElementById('end-time');
        endTimeSpan.textContent = formatTime(currentMinutes);
    }
    
    // Check if location is open during the visit time
    function checkOperatingHours(row, cell, arrivalMinutes, departureMinutes) {
        if (!cell) return;
        
        const hasHours = cell.dataset.hasHours === 'true';
        const isClosed = cell.dataset.closed === 'true';
        const isOpen24 = cell.dataset.open24 === 'true';
        
        // Remove existing classes
        row.classList.remove('bg-red-100', 'bg-red-50');
        
        // If open 24 hours or no tourism data, always open
        if (isOpen24 || !hasHours) {
            return;
        }
        
        // If closed (no hours for this day)
        if (isClosed) {
            row.classList.add('bg-red-100');
            return;
        }
        
        // Check operating hours
        const openTime = cell.dataset.openTime;
        const closeTime = cell.dataset.closeTime;
        
        if (!openTime || !closeTime) return;
        
        const [openHour, openMinute] = openTime.split(':').map(Number);
        const [closeHour, closeMinute] = closeTime.split(':').map(Number);
        
        const openMinutes = openHour * 60 + openMinute;
        const closeMinutes = closeHour * 60 + closeMinute;
        
        // Check if visit time is outside operating hours
        // Visit is considered closed if arrival is before opening or departure is after closing
        if (arrivalMinutes < openMinutes || departureMinutes > closeMinutes) {
            row.classList.add('bg-red-100');
        }
    }
    
    // Format minutes to HH:MM
    function formatTime(totalMinutes) {
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');
        calculateTimes();
        
        // Add event listeners to all stay duration inputs
        const stayInputs = document.querySelectorAll('.stay-duration');
        stayInputs.forEach(input => {
            input.addEventListener('input', calculateTimes);
        });

        // Check if Leaflet is loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded!');
            return;
        }
        
        console.log('Leaflet version:', L.version);

        // Initialize map
        try {
            initializeMap();
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    });

    // Initialize Leaflet Map
    function initializeMap() {
        // Check if map container exists
        const mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.error('❌ Map container not found!');
            return;
        }
        
        // Get route data from PHP
        const routeGeometry = @json($itineraryData['route_geometry'] ?? null);
        const route = @json($itineraryData['route']['route']);
        
        console.log('=== MAP INITIALIZATION DEBUG ===');
        console.log('Route Geometry:', routeGeometry);
        console.log('Route stops:', route);
        
        if (!route || route.length === 0) {
            console.error('❌ No route data available');
            return;
        }

        // Calculate center point and collect bounds
        const bounds = [];
        let totalLat = 0, totalLng = 0;
        
        route.forEach(stop => {
            const lat = parseFloat(stop.destination.lat);
            const lng = parseFloat(stop.destination.long);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                totalLat += lat;
                totalLng += lng;
                bounds.push([lat, lng]);
            }
        });
        
        if (bounds.length === 0) {
            console.error('❌ No valid coordinates found');
            return;
        }
        
        const centerLat = totalLat / bounds.length;
        const centerLng = totalLng / bounds.length;

        console.log('✓ Map Center:', centerLat, centerLng);
        console.log('✓ Bounds:', bounds.length, 'points');

        // Initialize map
        const map = L.map('map').setView([centerLat, centerLng], 12);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Custom icons
        const startIcon = L.divIcon({
            html: '<div style="background-color: #10b981; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><svg style="width: 20px; height: 20px; color: white;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path></svg></div>',
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        const destinationIcon = (order) => L.divIcon({
            html: `<div style="background-color: #3b82f6; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); color: white; font-weight: bold; font-size: 14px;">${order}</div>`,
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        // Add markers
        route.forEach((stop, index) => {
            const lat = parseFloat(stop.destination.lat);
            const lng = parseFloat(stop.destination.long);

            const icon = stop.order === 0 ? startIcon : destinationIcon(stop.order);
            const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

            // Popup content
            let popupContent = `
                <div style="min-width: 200px;">
                    <h3 style="font-weight: bold; margin-bottom: 8px; color: ${stop.order === 0 ? '#10b981' : '#3b82f6'};">
                        ${stop.order === 0 ? '🚩 Titik Awal' : '📍 Destinasi ' + stop.order}
                    </h3>
                    <p style="font-weight: 600; margin-bottom: 4px;">${stop.destination.name}</p>
                    <p style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                        ${lat.toFixed(6)}°, ${lng.toFixed(6)}°
                    </p>
            `;

            if (stop.distance_from_previous > 0) {
                popupContent += `
                    <div style="background-color: #f3f4f6; padding: 8px; border-radius: 6px; font-size: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="color: #6b7280;">Jarak dari sebelumnya:</span>
                            <span style="font-weight: 600; color: #3b82f6;">${(stop.distance_from_previous / 1000).toFixed(2)} km</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">Waktu perjalanan:</span>
                            <span style="font-weight: 600; color: #10b981;">${Math.floor(stop.duration_from_previous / 60)} menit</span>
                        </div>
                    </div>
                `;
            }

            popupContent += `
                    <a href="https://www.google.com/maps?q=${lat},${lng}" 
                       target="_blank"
                       style="display: inline-block; margin-top: 8px; padding: 6px 12px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                        Buka di Google Maps
                    </a>
                </div>
            `;

            marker.bindPopup(popupContent);
        });

        // Draw route
        console.log('Drawing route...');
        console.log('Route Geometry Structure:', JSON.stringify(routeGeometry, null, 2));
        
        let routeDrawn = false;
        
        if (routeGeometry) {
            if (routeGeometry.geometry && routeGeometry.geometry.coordinates && routeGeometry.geometry.coordinates.length > 0) {
                // Use OpenRouteService decoded geometry
                console.log('Using OpenRouteService geometry with', routeGeometry.geometry.coordinates.length, 'coordinates');
                const coordinates = routeGeometry.geometry.coordinates.map(coord => [coord[1], coord[0]]);
                
                const polyline = L.polyline(coordinates, {
                    color: '#3b82f6',
                    weight: 4,
                    opacity: 0.7,
                    smoothFactor: 1
                }).addTo(map);
                
                console.log('✓ Route drawn with OpenRouteService geometry');
                routeDrawn = true;
            } else if (routeGeometry.coordinates && routeGeometry.coordinates.length > 0) {
                // Fallback: draw straight lines between points
                console.log('Using fallback straight lines with', routeGeometry.coordinates.length, 'coordinates');
                const coordinates = routeGeometry.coordinates.map(coord => [coord[1], coord[0]]);
                
                const polyline = L.polyline(coordinates, {
                    color: '#ef4444',
                    weight: 4,
                    opacity: 0.7,
                    dashArray: '10, 5',
                    smoothFactor: 1
                }).addTo(map);
                
                console.log('✓ Route drawn with straight lines (fallback)');
                routeDrawn = true;
            }
        }
        
        if (!routeDrawn) {
            console.warn('⚠ No valid geometry data - drawing manual route from markers');
            // Last fallback: connect markers with straight lines
            const coordinates = bounds;
            if (coordinates.length > 1) {
                const polyline = L.polyline(coordinates, {
                    color: '#f59e0b',
                    weight: 3,
                    opacity: 0.6,
                    dashArray: '5, 10',
                    smoothFactor: 1
                }).addTo(map);
                console.log('✓ Manual route drawn from', coordinates.length, 'markers');
            }
        }

        // Fit map to show all markers
        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
        
        console.log('✓ Map initialization complete');
    }
</script>
@endsection
