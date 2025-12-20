@extends('layout')

@section('title', 'Hasil Itinerary - ' . $itineraryData['name'])

@section('styles')
    <style>
        /* Tab Pane Styles */
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .tab-button {
            position: relative;
            padding: 12px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-button:hover {
            color: #374151;
            background: rgba(0, 0, 0, 0.02);
        }

        .tab-button.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        /* Map Container */
        #map {
            width: 100%;
            height: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Polyline Styling */
        .route-line-shadow {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .route-line-main {
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
            transition: all 0.3s ease;
        }

        .route-line-accent {
            animation: dashAnimation 20s linear infinite;
        }

        @keyframes dashAnimation {
            0% {
                stroke-dashoffset: 0;
            }

            100% {
                stroke-dashoffset: 50;
            }
        }

        /* Leaflet Marker Custom Styling */
        .leaflet-marker-icon {
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stats-value {
            font-size: 28px;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }

        .stats-label {
            font-size: 12px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('before-scripts')
    <!-- Mapbox Polyline Decoder -->
    <script src="https://unpkg.com/@mapbox/polyline@1.2.1/index.js"></script>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-green-600 via-teal-600 to-blue-700 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <h1 class="text-3xl md:text-4xl font-black mb-2">Itinerary Berhasil Dibuat!</h1>
                <p class="text-lg text-green-100">Rute optimal telah dihitung menggunakan algoritma TSP</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Card with Quick Stats -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $itineraryData['name'] }}</h2>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="stats-label">Tanggal Perjalanan</div>
                        <div class="stats-value">{{ \Carbon\Carbon::parse($itineraryData['travel_date'])->format('d M Y') }}
                        </div>
                    </div>
                    <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <div class="stats-label">Waktu Mulai</div>
                        <input type="time" id="start_time" value="{{ $itineraryData['start_time'] ?? '07:00' }}"
                            class="w-full border border-none bg-transparent text-white font-bold text-lg mt-2 focus:outline-none focus:ring-2 focus:ring-white rounded px-2 py-1"
                            onchange="calculateTimes()">
                    </div>
                    <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="stats-label">Total Jarak</div>
                        <div class="stats-value">{{ number_format($itineraryData['total_distance'] / 1000, 1) }} <span
                                class="text-base">km</span></div>
                    </div>
                    <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="stats-label">Waktu Perjalanan</div>
                        <div class="stats-value">
                            @php
                                $hours = floor($itineraryData['total_duration'] / 3600);
                                $minutes = ceil(($itineraryData['total_duration'] % 3600) / 60);
                            @endphp
                            {{ $hours }}h {{ $minutes }}m
                        </div>
                    </div>
                    <div class="stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <div class="stats-label">Total Destinasi</div>
                        <div class="stats-value">
                            @if (isset($itineraryData['route']))
                                {{ count($itineraryData['route']['route']) }}
                            @else
                                {{ count($itineraryData['details'] ?? []) }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    @if ($itineraryData['is_owner'])
                        <button onclick="openSaveModal()"
                            class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V5">
                                </path>
                            </svg>
                            Simpan Itinerary
                        </button>
                    @endif
                    <button onclick="shareItinerary()"
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Bagikan
                    </button>
                    <button onclick="openGoogleMapsRoute()"
                        class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 003 16.382V5.618a1 1 0 011.553-.894L9 7.382v12.618zM15 20l5.447-2.724A1 1 0 0021 16.382V5.618a1 1 0 00-1.553-.894L15 7.382v12.618zM9 7h6">
                            </path>
                        </svg>
                        Kunjungi di Google Maps
                    </button>
                </div>
            </div>

            <!-- Tab Pane Container -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 flex overflow-x-auto bg-gray-50">
                    <button class="tab-button active" onclick="switchTab('map-tab')">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                            </path>
                        </svg>
                        Peta Rute
                    </button>
                    <button class="tab-button" onclick="switchTab('route-tab')">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                            </path>
                        </svg>
                        Rute Detail
                    </button>
                    <button class="tab-button" onclick="switchTab('matrix-tab')">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Matriks Jarak
                    </button>
                    <button class="tab-button" onclick="switchTab('summary-tab')">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        Ringkasan
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Map Tab -->
                    <div id="map-tab" class="tab-pane active">
                        <div id="map"></div>
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold">Informasi Peta:</p>
                                    <p class="mt-1">Garis biru menunjukkan rute perjalanan optimal. Marker hijau adalah
                                        titik awal, marker biru adalah destinasi dengan nomor urutan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Route Tab -->
                    <div id="route-tab" class="tab-pane">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Urutan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Destinasi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jam Operasional</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estimasi Sampai</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Durasi Berkunjung</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jarak</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Waktu Perjalanan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($itineraryData['route']['route'] as $stop)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200"
                                            data-duration="{{ $stop['duration_from_previous'] }}"
                                            data-order="{{ $stop['order'] }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if ($stop['order'] == 0)
                                                        <span
                                                            class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-800 rounded-full font-bold">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <span
                                                            class="flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-800 rounded-full font-bold">{{ $stop['order'] }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900 mb-2">
                                                    {{ $stop['destination']['name'] }}</div>
                                                @if ($stop['order'] == 0)
                                                    <div class="text-xs text-green-600 font-semibold mb-2">Titik Awal</div>
                                                @endif
                                                <a href="https://www.google.com/maps?q={{ $stop['destination']['lat'] }},{{ $stop['destination']['long'] }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded bg-blue-100 text-blue-600 hover:bg-blue-200 text-xs font-semibold transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    Lihat di Maps
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 operating-hours-cell" data-order="{{ $stop['order'] }}">
                                                @php
                                                    $hasTourismData = false;
                                                    $tourismData = null;
                                                    $openTime = null;
                                                    $closeTime = null;

                                                    if (
                                                        $stop['order'] == 0 &&
                                                        $itineraryData['start_point']['type'] === 'tourism'
                                                    ) {
                                                        $tourismData = \App\Models\Tourism::with('hours')->find(
                                                            $itineraryData['start_point']['id'],
                                                        );
                                                        $hasTourismData =
                                                            $tourismData && $tourismData->hours->count() > 0;
                                                    } elseif (
                                                        $stop['order'] > 0 &&
                                                        isset($stop['destination']['tourism'])
                                                    ) {
                                                        $tourismData = $stop['destination']['tourism'];
                                                        $hasTourismData = $tourismData->hours->count() > 0;
                                                    }

                                                    if ($hasTourismData && $tourismData) {
                                                        $travelDate = \Carbon\Carbon::parse(
                                                            $itineraryData['travel_date'],
                                                        );
                                                        $dayName = '';

                                                        switch ($travelDate->dayName) {
                                                            case 'Monday':
                                                                $dayName = 'Senin';
                                                                break;
                                                            case 'Tuesday':
                                                                $dayName = 'Selasa';
                                                                break;
                                                            case 'Wednesday':
                                                                $dayName = 'Rabu';
                                                                break;
                                                            case 'Thursday':
                                                                $dayName = 'Kamis';
                                                                break;
                                                            case 'Friday':
                                                                $dayName = 'Jumat';
                                                                break;
                                                            case 'Saturday':
                                                                $dayName = 'Sabtu';
                                                                break;
                                                            case 'Sunday':
                                                                $dayName = 'Minggu';
                                                                break;
                                                        }

                                                        $todayHour = $tourismData->hours->firstWhere('day', $dayName);

                                                        if ($todayHour) {
                                                            $openTime = \Carbon\Carbon::parse(
                                                                $todayHour->open_time,
                                                            )->format('H:i');
                                                            $closeTime = \Carbon\Carbon::parse(
                                                                $todayHour->close_time,
                                                            )->format('H:i');
                                                        }
                                                    }
                                                @endphp

                                                @if ($hasTourismData && $openTime && $closeTime)
                                                    <div class="text-sm" data-open-time="{{ $openTime }}"
                                                        data-close-time="{{ $closeTime }}" data-has-hours="true">
                                                        <div class="text-gray-900 font-semibold">{{ $openTime }} -
                                                            {{ $closeTime }}</div>
                                                    </div>
                                                @elseif ($hasTourismData && !$openTime)
                                                    <div class="text-sm" data-has-hours="false" data-closed="true">
                                                        <div class="text-red-600 font-semibold">Tutup</div>
                                                    </div>
                                                @else
                                                    <div class="text-sm" data-has-hours="false" data-open24="true">
                                                        <span class="text-sm text-gray-500">24 Jam</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="arrival-time text-sm font-semibold text-indigo-600">--:--</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $isStartPointTourism =
                                                        $stop['order'] == 0 &&
                                                        $itineraryData['start_point']['type'] === 'tourism';
                                                    $canHaveStayDuration = $stop['order'] > 0 || $isStartPointTourism;
                                                @endphp
                                                @if ($canHaveStayDuration)
                                                    <input type="number"
                                                        class="stay-duration border border-gray-300 rounded-lg px-3 py-1 w-20 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        value="60" min="0" step="15"
                                                        onchange="calculateTimes()">
                                                    <span class="text-xs text-gray-600 ml-1">min</span>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($stop['distance_from_previous'] > 0)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        {{ number_format($stop['distance_from_previous'] / 1000, 2) }} km
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($stop['duration_from_previous'] > 0)
                                                    @php
                                                        $hours = floor($stop['duration_from_previous'] / 3600);
                                                        $minutes = ceil(($stop['duration_from_previous'] % 3600) / 60);
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        @if ($hours > 0)
                                                            {{ $hours }}h
                                                        @endif{{ $minutes }}m
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Matrix Tab -->
                    <div id="matrix-tab" class="tab-pane">
                        @php
                            // Build points array from route
                            $allPoints = [];
                            foreach ($itineraryData['route']['route'] as $stop) {
                                $allPoints[] = $stop['destination'];
                            }
                            $matrix = $itineraryData['distance_matrix'] ?? [];
                        @endphp
                        <div class="overflow-x-auto">
                            @if (count($matrix) > 0)
                                <table class="min-w-full border-collapse">
                                    <thead>
                                        <tr>
                                            <th
                                                class="border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700">
                                                Dari / Ke</th>
                                            @foreach ($allPoints as $index => $point)
                                                <th
                                                    class="border border-gray-300 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-900">
                                                    @if ($index == 0)
                                                        <span class="text-lg">Start</span>
                                                    @else
                                                        <span class="text-lg">{{ $index }}</span>
                                                    @endif
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allPoints as $i => $fromPoint)
                                            <tr>
                                                <td
                                                    class="border border-gray-300 bg-purple-50 px-4 py-2 font-semibold text-sm text-purple-900">
                                                    @if ($i == 0)
                                                        Start
                                                    @else
                                                        {{ $i }}
                                                    @endif
                                                </td>
                                                @foreach ($allPoints as $j => $toPoint)
                                                    <td
                                                        class="border border-gray-300 px-4 py-2 text-center text-sm @if ($i == $j) bg-gray-200 text-gray-500 font-bold @else bg-white hover:bg-blue-50 @endif">
                                                        @if ($i == $j)
                                                            -
                                                        @else
                                                            {{ isset($matrix[$i][$j]) ? number_format($matrix[$i][$j] / 1000, 2) : 'N/A' }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-blue-800">Matriks jarak tidak tersedia. Data matriks hanya tersedia
                                        setelah generate itinerary baru.</p>
                                </div>
                            @endif
                        </div>
                        @if (isset($itineraryData['dp_steps']) && !empty($itineraryData['dp_steps']))
                            <div class="bg-white rounded-xl shadow-lg p-6 mt-6 animate-fade-in-up">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                                        <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                        </svg>
                                        Langkah-langkah Dynamic Programming
                                    </h3>
                                    <button onclick="toggleDPSteps()"
                                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                                        <span id="dp-toggle-text">Lihat Detail</span>
                                        <svg id="dp-toggle-icon" class="w-4 h-4 ml-1 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- DP Summary Cards -->
                                @if (isset($itineraryData['dp_summary']) && !empty($itineraryData['dp_summary']))
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                        <div
                                            class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                                            <div class="text-blue-600 text-xs font-semibold uppercase mb-1">Total Iterasi
                                            </div>
                                            <div class="text-2xl font-bold text-blue-900">
                                                {{ number_format($itineraryData['dp_summary']['total_iterations'] ?? 0) }}
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                                            <div class="text-green-600 text-xs font-semibold uppercase mb-1">Total Transisi
                                            </div>
                                            <div class="text-2xl font-bold text-green-900">
                                                {{ number_format($itineraryData['dp_summary']['total_transitions'] ?? 0) }}
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                                            <div class="text-purple-600 text-xs font-semibold uppercase mb-1">Total States
                                            </div>
                                            <div class="text-2xl font-bold text-purple-900">
                                                {{ $itineraryData['dp_summary']['total_states'] ?? 0 }}
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                                            <div class="text-orange-600 text-xs font-semibold uppercase mb-1">Waktu
                                                Komputasi</div>
                                            <div class="text-2xl font-bold text-orange-900">
                                                {{ number_format($itineraryData['dp_summary']['computation_time'] ?? 0, 3) }}s
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Algorithm Explanation -->
                                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-indigo-900 font-medium mb-2">
                                            <svg class="inline w-5 h-5 mr-2 text-indigo-600" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Tentang Algoritma Held-Karp (Bitmask DP) untuk TSP
                                        </p>
                                        <ul class="text-sm text-indigo-800 ml-7 space-y-1 list-disc list-inside">
                                            <li><strong>State:</strong> dp[mask][i] = jarak minimum mengunjungi lokasi dalam
                                                mask, berakhir di i</li>
                                            <li><strong>Bitmask:</strong> Representasi biner untuk tracking lokasi yang
                                                sudah dikunjungi</li>
                                            <li><strong>Complexity:</strong> O(2<sup>n</sup> × n²) waktu, O(2<sup>n</sup> ×
                                                n) space</li>
                                            <li><strong>Path Optimal:</strong>
                                                Start →
                                                @if (isset($itineraryData['dp_summary']['optimal_path']))
                                                    {{ implode(' → ',array_map(function ($city) {return 'Kota ' . $city;}, $itineraryData['dp_summary']['optimal_path'])) }}
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                @endif

                                <!-- Detailed Steps Table (Collapsible) -->
                                <div id="dp-steps-detail" class="hidden">
                                    <hr class="my-4 border-gray-200">

                                    <h4 class="text-lg font-bold text-gray-800 mb-3">Detail Langkah DP (Tabel)</h4>

                                    <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                                        <table class="min-w-full border-collapse border border-gray-300">
                                            <thead class="bg-gray-100 sticky top-0">
                                                <tr>
                                                    <th
                                                        class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                                        #</th>
                                                    <th
                                                        class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                                        Tipe</th>
                                                    <th
                                                        class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                                        Deskripsi</th>
                                                    <th
                                                        class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                                        Detail</th>
                                                    <th
                                                        class="border border-gray-300 px-4 py-2 text-right text-sm font-semibold text-gray-700">
                                                        Waktu (s)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($itineraryData['dp_steps'] as $step)
                                                    @php
                                                        $stepColors = [
                                                            'INIT' => 'bg-blue-50',
                                                            'BASE_CASE' => 'bg-green-50',
                                                            'TRANSITION' => 'bg-purple-50',
                                                            'ITERATION_COMPLETE' => 'bg-yellow-50',
                                                            'FIND_BEST_END' => 'bg-orange-50',
                                                            'BACKTRACK' => 'bg-pink-50',
                                                        ];
                                                        $stepBadges = [
                                                            'INIT' => 'bg-blue-500',
                                                            'BASE_CASE' => 'bg-green-500',
                                                            'TRANSITION' => 'bg-purple-500',
                                                            'ITERATION_COMPLETE' => 'bg-yellow-500',
                                                            'FIND_BEST_END' => 'bg-orange-500',
                                                            'BACKTRACK' => 'bg-pink-500',
                                                        ];
                                                        $bgColor = $stepColors[$step['step_type']] ?? 'bg-gray-50';
                                                        $badgeColor = $stepBadges[$step['step_type']] ?? 'bg-gray-500';
                                                        $timestamp = number_format(
                                                            ($step['timestamp'] ?? 0) -
                                                                ($itineraryData['dp_steps'][0]['timestamp'] ?? 0),
                                                            4,
                                                        );
                                                    @endphp

                                                    <tr class="{{ $bgColor }}">
                                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                                            <span
                                                                class="{{ $badgeColor }} text-white text-xs font-bold px-2 py-1 rounded">
                                                                {{ $step['step_number'] }}
                                                            </span>
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <span
                                                                class="font-semibold text-sm">{{ $step['step_type'] }}</span>
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2 text-sm">
                                                            {{ $step['description'] ?? '-' }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2 text-xs">
                                                            {{-- INIT Details --}}
                                                            @if ($step['step_type'] === 'INIT')
                                                                Total States: {{ $step['total_states'] ?? 0 }}

                                                                {{-- BASE_CASE Details --}}
                                                            @elseif($step['step_type'] === 'BASE_CASE' && isset($step['base_cases']))
                                                                <div class="space-y-1">
                                                                    @foreach ($step['base_cases'] as $base)
                                                                        <div>Kota {{ $base['destination'] }}: <code
                                                                                class="bg-gray-200 px-1">{{ $base['mask_binary'] }}</code>
                                                                            = {{ number_format($base['distance']) }}m</div>
                                                                    @endforeach
                                                                </div>

                                                                {{-- TRANSITION Details --}}
                                                            @elseif($step['step_type'] === 'TRANSITION' && isset($step['state']))
                                                                <div>
                                                                    <strong>Mask:</strong> <code
                                                                        class="bg-gray-200 px-1">{{ $step['state']['mask_binary'] }}</code><br>
                                                                    <strong>Last City:</strong>
                                                                    {{ $step['state']['last_city'] }}<br>
                                                                    <strong>Distance:</strong>
                                                                    {{ number_format($step['state']['current_distance']) }}m<br>
                                                                    <strong>Transitions:</strong>
                                                                    {{ count($step['state']['transitions']) }}
                                                                </div>

                                                                {{-- ITERATION_COMPLETE Details --}}
                                                            @elseif($step['step_type'] === 'ITERATION_COMPLETE')
                                                                <div>
                                                                    Iterasi:
                                                                    {{ number_format($step['total_iterations']) }}<br>
                                                                    Transisi:
                                                                    {{ number_format($step['total_transitions']) }}
                                                                </div>

                                                                {{-- FIND_BEST_END Details --}}
                                                            @elseif($step['step_type'] === 'FIND_BEST_END')
                                                                <div>
                                                                    <strong>Full Mask:</strong> <code
                                                                        class="bg-gray-200 px-1">{{ $step['full_mask_binary'] ?? '' }}</code><br>
                                                                    <strong>Best City:</strong>
                                                                    {{ $step['best_city'] ?? '-' }}<br>
                                                                    <strong>Best Distance:</strong>
                                                                    {{ number_format($step['best_distance'] ?? 0) }}m
                                                                </div>

                                                                {{-- BACKTRACK Details --}}
                                                            @elseif($step['step_type'] === 'BACKTRACK' && isset($step['final_path']))
                                                                <div>
                                                                    <strong>Final Path:</strong><br>
                                                                    Start →
                                                                    {{ implode(' → ',array_map(function ($city) {return 'Kota ' . $city;}, $step['final_path'])) }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2 text-right text-sm">
                                                            {{ $timestamp }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <p class="text-sm text-blue-800">
                                            <strong>Keterangan:</strong> Tabel di atas menampilkan setiap langkah algoritma
                                            Dynamic Programming
                                            dalam menyelesaikan Traveling Salesman Problem (TSP). Setiap baris
                                            merepresentasikan satu iterasi
                                            atau event dalam proses komputasi untuk menemukan rute optimal.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function toggleDPSteps() {
                                    const detail = document.getElementById('dp-steps-detail');
                                    const toggleText = document.getElementById('dp-toggle-text');
                                    const toggleIcon = document.getElementById('dp-toggle-icon');

                                    if (detail.classList.contains('hidden')) {
                                        detail.classList.remove('hidden');
                                        toggleText.textContent = 'Sembunyikan Detail';
                                        toggleIcon.style.transform = 'rotate(180deg)';
                                    } else {
                                        detail.classList.add('hidden');
                                        toggleText.textContent = 'Lihat Detail';
                                        toggleIcon.style.transform = 'rotate(0deg)';
                                    }
                                }
                            </script>
                        @endif
                    </div>

                    <!-- Summary Tab -->
                    <div id="summary-tab" class="tab-pane">
                        <div class="space-y-4">
                            @foreach ($itineraryData['route']['route'] as $index => $stop)
                                <div
                                    class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    @if ($index == 0)
                                        <div
                                            class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div
                                            class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-xl font-bold text-blue-600">{{ $stop['order'] }}</span>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $stop['destination']['name'] }}</h4>
                                        @if ($stop['distance_from_previous'] > 0)
                                            <p class="text-sm text-gray-600">
                                                {{ number_format($stop['distance_from_previous'] / 1000, 2) }} km •
                                                @php
                                                    $hours = floor($stop['duration_from_previous'] / 3600);
                                                    $minutes = floor(($stop['duration_from_previous'] % 3600) / 60);
                                                @endphp
                                                @if ($hours > 0)
                                                    {{ $hours }}h
                                                @endif{{ $minutes }}m dari lokasi sebelumnya
                                            </p>
                                        @endif
                                    </div>

                                    @if ($index < count($itineraryData['route']['route']) - 1)
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <!-- Save Modal -->
    <div id="saveModal"
        class="fixed inset-0 bg-black/30 hidden z-[9999] flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Simpan Itinerary</h3>
                    <button onclick="closeSaveModal()" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4">
                <!-- Summary Info -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Itinerary:</span>
                        <span class="font-semibold text-gray-900">{{ $itineraryData['name'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Perjalanan:</span>
                        <span
                            class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($itineraryData['travel_date'])->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Waktu Mulai:</span>
                        <span class="font-semibold text-gray-900" id="modalStartTime">08:00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Destinasi:</span>
                        <span class="font-semibold text-gray-900">{{ count($itineraryData['route']['route']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Jarak:</span>
                        <span
                            class="font-semibold text-gray-900">{{ number_format($itineraryData['total_distance'] / 1000, 1) }}
                            km</span>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold">Tips:</p>
                            <p class="mt-1">Silahkan sesuaikan durasi berkunjung di setiap destinasi sebelum menyimpan ke
                                database.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex gap-3 justify-end">
                <button onclick="closeSaveModal()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    Batal
                </button>
                <button onclick="saveItinerary()"
                    class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V5">
                        </path>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/@mapbox/polyline@1.2.1/src/polyline.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // ============================================
        // TAB SWITCHING FUNCTION
        // ============================================
        function switchTab(tabName) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            const selectedTab = document.getElementById(tabName);
            if (selectedTab) {
                selectedTab.classList.add('active');
            }

            // Add active class to clicked button
            event.target.classList.add('active');

            // Trigger map resize if map tab is activated
            if (tabName === 'map-tab' && window.mapInstance) {
                setTimeout(() => {
                    window.mapInstance.invalidateSize();
                }, 100);
            }
        }

        // ============================================
        // MAP INITIALIZATION FUNCTION
        // ============================================
        function initializeMap() {
            const routeGeometry = {!! json_encode($itineraryData['route_geometry']) !!};
            const routeStops = {!! json_encode($itineraryData['route']['route']) !!};

            console.log('Route Geometry:', routeGeometry);
            console.log('Route Stops:', routeStops);

            // Initialize map
            const map = L.map('map');
            window.mapInstance = map; // Store globally for later use

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add markers for each stop
            let markerBounds = L.latLngBounds();
            routeStops.forEach((stop, index) => {
                const lat = stop.destination.lat;
                const lng = stop.destination.long;
                markerBounds.extend([lat, lng]);

                let markerColor = index === 0 ? 'green' : 'blue';
                let icon = L.divIcon({
                    html: `<div style="background: ${markerColor}; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">${index === 0 ? '●' : index}</div>`,
                    className: '',
                    iconSize: [30, 30]
                });

                L.marker([lat, lng], {
                        icon: icon
                    })
                    .bindPopup(`<strong>${stop.destination.name}</strong>`)
                    .addTo(map);
            });

            // Decode and draw polyline if available
            if (routeGeometry && routeGeometry.geometry) {
                try {
                    console.log('Attempting to decode polyline:', routeGeometry.geometry);

                    // Check if polyline library is loaded
                    if (typeof polyline === 'undefined') {
                        console.error('Polyline library not loaded');
                        throw new Error('Polyline library not available');
                    }

                    // Decode using Mapbox Polyline library
                    const decodedCoords = polyline.toGeoJSON(routeGeometry.geometry);
                    console.log('Decoded coordinates:', decodedCoords);

                    if (decodedCoords && decodedCoords.coordinates && decodedCoords.coordinates.length > 0) {
                        // Mapbox returns [lng, lat] format, convert to [lat, lng] for Leaflet
                        const leafletCoords = decodedCoords.coordinates.map(coord => [coord[1], coord[0]]);
                        console.log('Converted to Leaflet format:', leafletCoords);

                        // Draw outer shadow/border polyline (darker, thicker)
                        L.polyline(leafletCoords, {
                            color: '#1e40af',
                            weight: 6,
                            opacity: 0.6,
                            lineCap: 'round',
                            lineJoin: 'round',
                            className: 'route-line-shadow'
                        }).addTo(map);

                        // Draw main polyline (bright, on top)
                        L.polyline(leafletCoords, {
                            color: '#0ea5e9',
                            weight: 3,
                            opacity: 1,
                            lineCap: 'round',
                            lineJoin: 'round',
                            className: 'route-line-main'
                        }).addTo(map);

                        // Draw dashed accent line (animated effect)
                        L.polyline(leafletCoords, {
                            color: '#06b6d4',
                            weight: 2,
                            opacity: 0.7,
                            dashArray: '8, 5',
                            lineCap: 'round',
                            lineJoin: 'round',
                            className: 'route-line-accent'
                        }).addTo(map);

                        // Fit map to polyline bounds
                        const polylineBounds = L.latLngBounds(leafletCoords);
                        markerBounds.extend(polylineBounds);

                        console.log('✓ Polyline decoded and displayed successfully - ' + leafletCoords.length + ' points');
                    } else {
                        console.warn('No coordinates found in decoded polyline');
                    }
                } catch (error) {
                    console.error('✗ Error decoding polyline:', error);
                }
            } else {
                console.warn('No route geometry available');
            }

            // Fit map to all bounds with padding
            if (markerBounds.isValid()) {
                map.fitBounds(markerBounds, {
                    padding: [50, 50]
                });
            } else {
                // Fallback to default view
                map.setView([51.505, -0.09], 13);
            }
        }

        // ============================================
        // CALCULATE TIMES FUNCTION
        // ============================================
        function calculateTimes() {
            const startTimeInput = document.getElementById('start_time');
            const startTime = startTimeInput ? startTimeInput.value : '08:00';

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
        }

        // ============================================
        // CHECK OPERATING HOURS FUNCTION
        // ============================================
        function checkOperatingHours(row, cell, arrivalMinutes, departureMinutes) {
            if (!cell) return;

            const hasHours = cell.querySelector('[data-has-hours]');
            if (!hasHours) return;

            const hasHoursValue = hasHours.getAttribute('data-has-hours') === 'true';
            const isClosed = hasHours.getAttribute('data-closed') === 'true';
            const isOpen24 = hasHours.getAttribute('data-open24') === 'true';

            // Remove existing classes
            row.classList.remove('bg-red-100', 'bg-red-50');

            // If open 24 hours or no tourism data, always open
            if (isOpen24 || !hasHoursValue) {
                return;
            }

            // If closed (no hours for this day)
            if (isClosed) {
                row.classList.add('bg-red-100');
                return;
            }

            // Check operating hours
            const openTime = hasHours.getAttribute('data-open-time');
            const closeTime = hasHours.getAttribute('data-close-time');

            if (!openTime || !closeTime) return;

            const [openHour, openMinute] = openTime.split(':').map(Number);
            const [closeHour, closeMinute] = closeTime.split(':').map(Number);

            const openMinutes = openHour * 60 + openMinute;
            const closeMinutes = closeHour * 60 + closeMinute;

            // Check if visit time is outside operating hours
            if (arrivalMinutes < openMinutes || departureMinutes > closeMinutes) {
                row.classList.add('bg-red-100');
            }
        }

        // ============================================
        // FORMAT TIME FUNCTION
        // ============================================
        function formatTime(totalMinutes) {
            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
        }

        // ============================================
        // SHARE ITINERARY FUNCTION
        // ============================================
        function shareItinerary() {
            const itineraryData = {!! json_encode($itineraryData) !!};

            // Get current URL or construct it
            const shareUrl = window.location.href;

            try {
                // Try to copy to clipboard using the modern API
                navigator.clipboard.writeText(shareUrl).then(function() {
                    // Show success message
                    const message = document.createElement('div');
                    message.className =
                        'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse';
                    message.textContent = '✓ Link berhasil disalin ke clipboard!';
                    document.body.appendChild(message);

                    // Remove message after 3 seconds
                    setTimeout(() => {
                        message.remove();
                    }, 3000);
                }).catch(function(err) {
                    // Fallback for older browsers
                    fallbackCopyToClipboard(shareUrl);
                });
            } catch (err) {
                // Fallback for older browsers
                fallbackCopyToClipboard(shareUrl);
            }
        }

        // Fallback function for older browsers
        function fallbackCopyToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();

            try {
                document.execCommand('copy');
                const message = document.createElement('div');
                message.className =
                    'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse';
                message.textContent = '✓ Link berhasil disalin ke clipboard!';
                document.body.appendChild(message);

                setTimeout(() => {
                    message.remove();
                }, 3000);
            } catch (err) {
                alert('❌ Gagal menyalin link ke clipboard');
            }

            document.body.removeChild(textArea);
        }

        // ============================================
        // GOOGLE MAPS ROUTE FUNCTION
        // ============================================
        function openGoogleMapsRoute() {
            const itineraryData = {!! json_encode($itineraryData) !!};

            // Get all coordinates from the route
            const coordinates = [];

            // Add start point
            if (itineraryData.start_point) {
                coordinates.push(`${itineraryData.start_point.lat},${itineraryData.start_point.long}`);
            }

            // Add all destinations
            if (itineraryData.route && itineraryData.route.route) {
                itineraryData.route.route.forEach(stop => {
                    if (stop.order > 0 && stop.destination) {
                        coordinates.push(`${stop.destination.lat},${stop.destination.long}`);
                    }
                });
            }

            if (coordinates.length < 2) {
                alert('❌ Koordinat tidak cukup untuk membuka Google Maps');
                return;
            }

            // Build Google Maps URL
            const googleMapsUrl = `https://www.google.com/maps/dir/${coordinates.join('/')}`;

            console.log('🗺️ Google Maps URL:', googleMapsUrl);

            // Open in new tab
            window.open(googleMapsUrl, '_blank');
        }

        // ============================================
        // MODAL FUNCTIONS
        // ============================================
        function openSaveModal() {
            const itineraryData = {!! json_encode($itineraryData) !!};

            // Check if user is owner
            if (!itineraryData.is_owner) {
                alert('❌ Hanya pemilik itinerary yang dapat mengedit');
                return;
            }

            const startTime = document.getElementById('start_time').value;
            document.getElementById('modalStartTime').textContent = startTime;
            document.getElementById('saveModal').classList.remove('hidden');
        }

        function closeSaveModal() {
            document.getElementById('saveModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('saveModal');
            if (event.target === modal) {
                closeSaveModal();
            }
        });

        // ============================================
        // SAVE ITINERARY FUNCTION
        // ============================================
        function saveItinerary() {
            // Ambil start_time dari input yang sudah ada di halaman
            const startTime = document.getElementById('start_time').value;

            // Validation
            if (!startTime) {
                alert('❌ Waktu mulai harus diisi!');
                return;
            }

            // Show loading state
            const saveButton = event.target.closest('button');
            const originalHTML = saveButton.innerHTML;
            saveButton.disabled = true;
            saveButton.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span>Menyimpan...';

            // Get data dari session yang sudah disimpan
            const itineraryData = {!! json_encode($itineraryData) !!};

            // Build details array from table - HANYA untuk stay_duration
            const details = [];
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach((row, index) => {
                const tourism = itineraryData.route.route[index];
                const stayDurationInput = row.querySelector('.stay-duration');

                if (tourism) {
                    const detail = {
                        order: tourism.order,
                        stay_duration: stayDurationInput ? parseInt(stayDurationInput.value) || 0 : 0,
                    };

                    // Hanya include detail jika punya tourism_id atau bukan start point
                    // Jika start point adalah custom (tanpa tourism), skip order 0
                    if (tourism.order === 0 && itineraryData.start_point.type === 'custom') {
                        // Skip order 0 ketika start point custom
                        return;
                    }

                    details.push(detail);
                }
            });

            // Build payload - HANYA start_time dan stay_duration
            const payload = {
                itinerary_id: itineraryData.id,
                start_time: startTime,
                details: details,
                _token: '{{ csrf_token() }}'
            };

            console.log('📦 Payload yang akan dikirim:', payload);

            // Send to server
            fetch('{{ route('itinerary.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✓ Response:', data);

                    if (data.success) {
                        // Show success message
                        closeSaveModal();
                        alert('✓ Itinerary berhasil disimpan!');

                        // Redirect to itinerary detail page
                        if (data.redirect_url) {
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 500);
                        }
                    } else {
                        alert('❌ Gagal menyimpan: ' + (data.message || 'Unknown error'));
                        saveButton.disabled = false;
                        saveButton.innerHTML = originalHTML;
                    }
                })
                .catch(error => {
                    console.error('❌ Error:', error);
                    alert('❌ Terjadi kesalahan saat menyimpan: ' + error.message);
                    saveButton.disabled = false;
                    saveButton.innerHTML = originalHTML;
                });
        }

        // ============================================
        // INITIALIZE ON PAGE LOAD
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');

            // Initialize map
            initializeMap();

            // Calculate times
            calculateTimes();

            // Add event listeners to all stay duration inputs
            const stayInputs = document.querySelectorAll('.stay-duration');
            stayInputs.forEach(input => {
                input.addEventListener('input', calculateTimes);
            });
        });
    </script>
@endsection
