@extends('layout')

@section('title', 'Buat Itinerary')

@section('content')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .location-option {
            transition: all 0.3s ease;
        }

        .location-option.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 99999;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-overlay.active {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 1rem;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.3s ease-out;
            z-index: 100000;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #map {
            height: 300px;
            width: 100%;
            border-radius: 0.5rem;
            z-index: 1;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 0.5rem;
        }
    </style>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in-up">
                <h1 class="text-4xl md:text-5xl font-black mb-3">
                    <svg class="w-12 h-12 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    Buat Itinerary Perjalanan
                </h1>
                <p class="text-xl text-indigo-100">Atur rencana perjalanan wisata Anda dengan mudah</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($tripCartItems->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Trip Cart Anda Kosong</h3>
                    <p class="text-gray-600 mb-6">Tambahkan destinasi wisata ke trip cart terlebih dahulu untuk membuat
                        itinerary</p>
                    <a href="{{ route('tourism.index') }}"
                        class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Jelajahi Destinasi Wisata
                    </a>
                </div>
            @else
                <form id="itineraryForm" method="POST" action="{{ route('itinerary.store') }}">
                    @csrf

                    <div class="grid lg:grid-cols-3 gap-8">
                        <!-- Left: Itinerary Settings (1/3) -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                    <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                        </path>
                                    </svg>
                                    Pengaturan
                                </h2>

                                <!-- Jumlah Hari (Auto-calculated) - Full Width -->
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-900 mb-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Jumlah Hari <span class="text-red-500">*</span>
                                        <span class="ml-2 text-xs font-normal text-white bg-indigo-600 px-2 py-0.5 rounded">Auto-calculated</span>
                                    </label>
                                    <input type="number" name="duration_days" id="durationDays" min="1"
                                        max="30" value="1" required readonly
                                        class="w-full px-4 py-3 border-2 border-indigo-300 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 text-center font-bold text-2xl text-indigo-700 cursor-not-allowed shadow-inner">
                                    <!-- Info akan di-inject oleh JavaScript -->
                                </div>

                                <!-- Waktu & Toleransi - Compact Grid -->
                                <div class="grid grid-cols-2 gap-3 mb-4">

                                    <!-- Waktu Toleransi -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-900 mb-1">
                                            Toleransi <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="tolerance_minutes" id="toleranceMinutes"
                                                min="0" max="120" value="15" required
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-center font-bold text-lg">
                                            <span
                                                class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-medium">mnt</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tanggal Mulai & Waktu -->
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-900 mb-1">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="startDate" 
                                        value="{{ date('Y-m-d') }}" required
                                        class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-semibold">
                                    <p class="text-xs text-gray-500 mt-1">
                                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Untuk validasi jam buka operasional
                                    </p>
                                </div>

                                <!-- Waktu Mulai & Selesai -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-900 mb-1">
                                            Mulai <span class="text-red-500">*</span>
                                        </label>
                                        <input type="time" name="start_time" id="startTime" value="08:00" required
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-semibold">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-900 mb-1">
                                            Selesai <span class="text-red-500">*</span>
                                        </label>
                                        <input type="time" name="end_time" id="endTime" value="18:00" required
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-semibold">
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 my-4"></div>

                                <!-- Titik Awal - Compact -->
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        Titik Awal <span class="text-red-500">*</span>
                                    </label>

                                    <!-- Radio buttons - Inline -->
                                    <div class="flex gap-3 mb-2 text-xs">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="start_location_type" value="destination"
                                                class="mr-1" onchange="toggleStartLocationType()">
                                            <span>Dari destinasi</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="start_location_type" value="custom"
                                                class="mr-1" checked onchange="toggleStartLocationType()">
                                            <span>Custom</span>
                                        </label>
                                    </div>

                                    <!-- Select Destination -->
                                    <div id="startDestinationSelect" class="mb-2" style="display: none;">
                                        <select name="start_destination_id" id="startDestinationId"
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($tripCartItems as $item)
                                                <option value="{{ $item->tourism->id }}"
                                                    data-name="{{ $item->tourism->name }}"
                                                    data-lat="{{ $item->tourism->latitude }}"
                                                    data-lng="{{ $item->tourism->longitude }}">
                                                    {{ Str::limit($item->tourism->name, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Custom Location -->
                                    <div id="startCustomLocation" class="space-y-2">
                                        <input type="text" name="start_location_name" id="startLocationName"
                                            placeholder="Nama lokasi"
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="number" name="start_latitude" id="startLatitude"
                                                placeholder="Lat" step="0.000000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                            <input type="number" name="start_longitude" id="startLongitude"
                                                placeholder="Lng" step="0.000000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                        </div>
                                        <button type="button" id="openStartMapBtn"
                                            class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition duration-300 flex items-center justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                                </path>
                                            </svg>
                                            Pilih di Map
                                        </button>
                                    </div>
                                </div>

                                <!-- Titik Akhir - Compact -->
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        Titik Akhir <span class="text-red-500">*</span>
                                    </label>

                                    <!-- Radio buttons - Inline -->
                                    <div class="flex gap-3 mb-2 text-xs">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="end_location_type" value="destination"
                                                class="mr-1" onchange="toggleEndLocationType()">
                                            <span>Dari destinasi</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="end_location_type" value="custom" class="mr-1"
                                                checked onchange="toggleEndLocationType()">
                                            <span>Custom</span>
                                        </label>
                                    </div>

                                    <!-- Select Destination -->
                                    <div id="endDestinationSelect" class="mb-2" style="display: none;">
                                        <select name="end_destination_id" id="endDestinationId"
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($tripCartItems as $item)
                                                <option value="{{ $item->tourism->id }}"
                                                    data-name="{{ $item->tourism->name }}"
                                                    data-lat="{{ $item->tourism->latitude }}"
                                                    data-lng="{{ $item->tourism->longitude }}">
                                                    {{ Str::limit($item->tourism->name, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Custom Location -->
                                    <div id="endCustomLocation" class="space-y-2">
                                        <input type="text" name="end_location_name" id="endLocationName"
                                            placeholder="Nama lokasi"
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="number" name="end_latitude" id="endLatitude" placeholder="Lat"
                                                step="0.000000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                            <input type="number" name="end_longitude" id="endLongitude"
                                                placeholder="Lng" step="0.000000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                        </div>
                                        <button type="button" id="openEndMapBtn"
                                            class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition duration-300 flex items-center justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                                </path>
                                            </svg>
                                            Pilih di Map
                                        </button>
                                    </div>
                                </div>

                                <!-- Lokasi Penginapan (Conditional) -->
                                <div id="accommodationSection" class="mb-4 transition-all duration-500 opacity-0 transform -translate-y-4" style="display: none;">
                                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-300 p-3 mb-3 rounded-lg shadow-sm">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-bold text-amber-900 mb-1">🏨 Lokasi Penginapan Diperlukan</p>
                                                <p class="text-xs text-amber-800">
                                                    Perjalanan multi-hari memerlukan lokasi penginapan untuk perhitungan rute optimal.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        <span>Nama Penginapan <span class="text-red-500">*</span></span>
                                    </label>

                                    <div class="space-y-3">
                                        <input type="text" name="accommodation_name" id="accommodationName"
                                            placeholder="Contoh: Hotel XYZ, Villa ABC, Homestay 123"
                                            class="w-full px-4 py-3 border-2 border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm font-medium bg-white shadow-sm">
                                        
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                            <label class="block text-xs font-semibold text-gray-700 mb-2">
                                                📍 Koordinat Lokasi
                                            </label>
                                            <div class="grid grid-cols-2 gap-2 mb-2">
                                                <input type="number" name="accommodation_latitude"
                                                    id="accommodationLatitude" placeholder="Latitude" step="0.000001"
                                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                                                <input type="number" name="accommodation_longitude"
                                                    id="accommodationLongitude" placeholder="Longitude" step="0.000001"
                                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                                            </div>
                                            <button type="button" id="openAccommodationMapBtn"
                                                class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-lg transition duration-300 flex items-center justify-center shadow-md">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                                    </path>
                                                </svg>
                                                Pilih Lokasi di Peta
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 my-4"></div>

                                <!-- Submit Button -->
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    Generate Itinerary
                                </button>
                            </div>
                        </div>

                        <!-- Right: Destination List (2/3) -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                                        <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Destinasi Wisata
                                        <span
                                            class="ml-3 bg-purple-100 text-purple-800 text-sm font-bold px-3 py-1 rounded-full">
                                            {{ $tripCartItems->count() }} Lokasi
                                        </span>
                                    </h2>
                                </div>

                                <!-- Info Box -->
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-semibold mb-1">Atur Durasi Kunjungan</p>
                                            <p>Tentukan berapa lama Anda ingin menghabiskan waktu di setiap destinasi (dalam
                                                menit).</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Destination Cards -->
                                <div id="destinationList" class="space-y-4">
                                    @foreach ($tripCartItems as $index => $item)
                                        <div
                                            class="destination-item bg-gradient-to-r from-white to-gray-50 rounded-xl shadow-md hover:shadow-lg transition duration-300 p-5 border-2 border-gray-200">
                                            <div class="flex items-start gap-4">
                                                <!-- Image -->
                                                <div class="flex-shrink-0">
                                                    @if ($item->tourism->files->isNotEmpty())
                                                        <img src="{{ filter_var($item->tourism->files->first()->file_path, FILTER_VALIDATE_URL) ? $item->tourism->files->first()->file_path : asset('storage/' . $item->tourism->files->first()->file_path) }}"
                                                            alt="{{ $item->tourism->name }}"
                                                            class="w-24 h-24 object-cover rounded-lg shadow-md">
                                                    @else
                                                        <div
                                                            class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                            <svg class="w-12 h-12 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                                                        {{ $item->tourism->name }}
                                                    </h3>
                                                    <div class="flex items-start text-gray-600 text-sm mb-3">
                                                        <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                        </svg>
                                                        <span class="line-clamp-2">{{ $item->tourism->location }}</span>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="mb-3">
                                                        @if ($item->tourism->prices->isNotEmpty())
                                                            @php
                                                                $minPrice = $item->tourism->prices->min('price');
                                                            @endphp
                                                            @if ($minPrice == 0)
                                                                <div
                                                                    class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1.5 rounded-lg">
                                                                    <svg class="w-4 h-4 mr-1.5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                                        </path>
                                                                    </svg>
                                                                    <span class="text-sm font-bold">GRATIS</span>
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1.5 rounded-lg">
                                                                    <svg class="w-4 h-4 mr-1.5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                                        </path>
                                                                    </svg>
                                                                    <div class="text-sm">
                                                                        <span class="font-semibold">~ Rp
                                                                            {{ number_format($minPrice, 0, ',', '.') }}</span>
                                                                        <span class="text-xs opacity-75">/orang</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div
                                                                class="inline-flex items-center bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg">
                                                                <span class="text-sm font-medium">Harga tidak
                                                                    tersedia</span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Duration Input -->
                                                    <div class="flex items-center gap-2">
                                                        <label
                                                            class="text-sm font-semibold text-gray-700 flex items-center">
                                                            <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Durasi:
                                                        </label>
                                                        <div class="flex items-center gap-2">
                                                            <input type="number"
                                                                name="durations[{{ $item->tourism->id }}]" min="15"
                                                                max="480" step="1" value="60" required
                                                                class="duration-input w-24 px-3 py-1.5 border-2 border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm font-semibold text-center"
                                                                title="Mengubah durasi akan mempengaruhi jumlah hari">
                                                            <span class="text-sm font-medium text-gray-600">menit</span>
                                                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20" title="Mempengaruhi jumlah hari">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Remove Button -->
                                                <div class="flex-shrink-0">
                                                    <button type="button"
                                                        onclick="removeTripCartItem({{ $item->tourism->id }})"
                                                        class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition duration-200">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Hidden input for tourism ID -->
                                            <input type="hidden" name="tourism_ids[]" value="{{ $item->tourism->id }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </section>

    <!-- Map Modal -->
    <div id="mapModal" class="modal-overlay">
        <div class="modal-content">
            <!-- Modal Header -->
            <div
                class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                        </path>
                    </svg>
                    <h3 class="text-xl font-bold" id="mapModalTitle">Pilih Lokasi di Peta</h3>
                </div>
                <button type="button" id="closeMapModal" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Instructions -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4 rounded-r">
                    <p class="text-sm text-blue-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Klik pada peta untuk memilih lokasi, atau gunakan tombol GPS untuk deteksi lokasi Anda
                    </p>
                </div>
                <div class="grid grid-cols-3 gap-2">

                    <!-- Map Container -->
                    <div id="map" class="col-span-2"></div>

                    <div>
                        <button type="button" id="detectGPSInModal"
                            class="w-full bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-300 flex items-center justify-center mb-4 shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Deteksi Lokasi GPS Saya
                        </button>

                        <!-- Selected Location Info -->
                        <div id="selectedLocationInfo" class="mt-4 p-4 bg-gray-50 rounded-lg" style="display: none;">
                            <h4 class="text-sm font-bold text-gray-900 mb-2">Lokasi Terpilih:</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-600">Latitude:</span>
                                    <span class="font-semibold text-gray-900" id="selectedLat">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Longitude:</span>
                                    <span class="font-semibold text-gray-900" id="selectedLng">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-4 pb-2">
                    <button type="button" id="cancelMapSelection"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition duration-300 shadow-md">
                        Batal
                    </button>
                    <button type="button" id="confirmMapSelection"
                        class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 shadow-lg">
                        ✓ Konfirmasi Lokasi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Itinerary Map Script -->
    <script src="{{ asset('js/itinerary-map.js') }}"></script>
    
    <script>
        // ========================================================================
        // AUTO CALCULATE JUMLAH HARI BERDASARKAN TOTAL DURASI
        // ========================================================================
        function calculateRequiredDays() {
            // Ambil waktu mulai dan selesai per hari
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            
            if (!startTime || !endTime) return;
            
            // Konversi ke menit
            const startMinutes = timeToMinutes(startTime);
            const endMinutes = timeToMinutes(endTime);
            const availableMinutesPerDay = endMinutes - startMinutes;
            
            // Validasi waktu
            if (availableMinutesPerDay <= 0) {
                alert('⚠️ Waktu selesai harus lebih besar dari waktu mulai!');
                document.getElementById('endTime').focus();
                return;
            }
            
            // Hitung total durasi dari semua destinasi
            let totalDuration = 0;
            const durationInputs = document.querySelectorAll('input[name^="durations["]');
            
            durationInputs.forEach(input => {
                const duration = parseInt(input.value) || 0;
                totalDuration += duration;
            });
            
            // Jika tidak ada destinasi, set ke 1 hari
            if (durationInputs.length === 0) {
                const durationDaysInput = document.getElementById('durationDays');
                durationDaysInput.value = 1;
                return;
            }
            
            // Tambahkan estimasi waktu perjalanan antar destinasi (rata-rata 30 menit per perpindahan)
            const numDestinations = durationInputs.length;
            const estimatedTravelTime = numDestinations > 0 ? (numDestinations - 1) * 30 : 0;
            totalDuration += estimatedTravelTime;
            
            // Hitung jumlah hari yang dibutuhkan
            const requiredDays = Math.ceil(totalDuration / availableMinutesPerDay);
            
            // Update input jumlah hari dengan animasi
            const durationDaysInput = document.getElementById('durationDays');
            const oldValue = durationDaysInput.value;
            const newValue = Math.max(1, requiredDays);
            
            // Animasi pulse jika nilai berubah
            if (oldValue != newValue) {
                durationDaysInput.classList.add('animate-pulse');
                setTimeout(() => {
                    durationDaysInput.classList.remove('animate-pulse');
                }, 1000);
            }
            
            durationDaysInput.value = newValue;
            
            // Toggle accommodation section berdasarkan jumlah hari
            toggleAccommodationSection(newValue);
            
            // Update visual feedback
            updateDayCalculationInfo(totalDuration, availableMinutesPerDay, requiredDays, estimatedTravelTime, numDestinations);
        }
        
        function timeToMinutes(time) {
            const [hours, minutes] = time.split(':').map(Number);
            return (hours * 60) + minutes;
        }
        
        // ========================================================================
        // TOGGLE ACCOMMODATION SECTION (Show/Hide berdasarkan jumlah hari)
        // ========================================================================
        function toggleAccommodationSection(days) {
            const accommodationSection = document.getElementById('accommodationSection');
            const accommodationInputs = accommodationSection.querySelectorAll('input');
            
            if (days > 1) {
                // Tampilkan section dengan animasi
                accommodationSection.style.display = 'block';
                
                // Tambahkan animasi slide-in
                accommodationSection.classList.remove('opacity-0', 'transform', '-translate-y-4');
                accommodationSection.classList.add('transition-all', 'duration-500', 'opacity-100');
                
                // Set input sebagai required
                accommodationInputs.forEach(input => {
                    if (input.id !== 'accommodationLatitude' && input.id !== 'accommodationLongitude') {
                        input.required = true;
                    }
                });
                
                console.log(`✅ Accommodation section shown (${days} hari)`);
            } else {
                // Sembunyikan section
                accommodationSection.style.display = 'none';
                
                // Hapus required dari input
                accommodationInputs.forEach(input => {
                    input.required = false;
                });
                
                console.log(`❌ Accommodation section hidden (${days} hari)`);
            }
        }
        
        function updateDayCalculationInfo(totalDuration, availablePerDay, requiredDays, travelTime, numDestinations) {
            // Cari atau buat elemen info
            let infoDiv = document.getElementById('dayCalculationInfo');
            
            if (!infoDiv) {
                infoDiv = document.createElement('div');
                infoDiv.id = 'dayCalculationInfo';
                infoDiv.className = 'mt-3 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-sm';
                
                const durationDaysInput = document.getElementById('durationDays');
                durationDaysInput.parentElement.appendChild(infoDiv);
            }
            
            const visitDuration = totalDuration - travelTime;
            const totalHours = Math.floor(totalDuration / 60);
            const totalMinutes = totalDuration % 60;
            const visitHours = Math.floor(visitDuration / 60);
            const visitMinutes = visitDuration % 60;
            const availableHours = Math.floor(availablePerDay / 60);
            const availableMinutes = availablePerDay % 60;
            const travelHours = Math.floor(travelTime / 60);
            const travelMinutes = travelTime % 60;
            
            // Emoji status
            const statusEmoji = requiredDays === 1 ? '✅' : requiredDays <= 3 ? '📅' : '🗓️';
            const accommodationRequired = requiredDays > 1;
            const accommodationAlert = accommodationRequired ? 
                `<div class="mt-2 p-2 bg-amber-100 border border-amber-300 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-amber-800">Perlu input lokasi penginapan!</span>
                </div>` : '';
            
            infoDiv.innerHTML = `
                <div class="text-xs space-y-2">
                    <div class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Perhitungan Waktu Otomatis:
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-white rounded-lg p-2 border border-blue-100">
                            <div class="text-gray-500 text-xs mb-1">Waktu per hari</div>
                            <div class="font-bold text-gray-900">${availableHours}j ${availableMinutes}m</div>
                        </div>
                        <div class="bg-white rounded-lg p-2 border border-blue-100">
                            <div class="text-gray-500 text-xs mb-1">Jumlah destinasi</div>
                            <div class="font-bold text-gray-900">${numDestinations} tempat</div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-2 border border-blue-100">
                        <div class="text-gray-500 text-xs mb-1">Durasi kunjungan</div>
                        <div class="font-bold text-indigo-700">${visitHours}j ${visitMinutes}m</div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-2 border border-blue-100">
                        <div class="text-gray-500 text-xs mb-1">Estimasi perjalanan</div>
                        <div class="font-bold text-amber-700">${travelHours}j ${travelMinutes}m <span class="text-xs font-normal text-gray-500">(~30m per perpindahan)</span></div>
                    </div>
                    
                    <div class="border-t-2 border-blue-300 my-2"></div>
                    
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="font-bold">Total waktu dibutuhkan:</span>
                            <span class="font-bold text-lg">${totalHours}j ${totalMinutes}m</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg p-3 shadow-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-bold flex items-center gap-1">
                                ${statusEmoji} Hari dibutuhkan:
                            </span>
                            <span class="font-bold text-2xl">${requiredDays} hari</span>
                        </div>
                    </div>
                    
                    ${accommodationAlert}
                </div>
            `;
        }
        
        // Event listeners untuk auto-calculate
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate on page load
            setTimeout(() => {
                calculateRequiredDays();
                // Initial toggle accommodation based on default value
                const initialDays = parseInt(document.getElementById('durationDays').value) || 1;
                toggleAccommodationSection(initialDays);
            }, 500);
            
            // Listen to time changes
            document.getElementById('startTime').addEventListener('change', calculateRequiredDays);
            document.getElementById('endTime').addEventListener('change', calculateRequiredDays);
            document.getElementById('startTime').addEventListener('input', calculateRequiredDays);
            document.getElementById('endTime').addEventListener('input', calculateRequiredDays);
            
            // Listen to duration changes (using event delegation)
            document.addEventListener('input', function(e) {
                if (e.target.matches('input[name^="durations["]')) {
                    calculateRequiredDays();
                }
            });
            
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[name^="durations["]')) {
                    calculateRequiredDays();
                }
            });
            
            // Listen to destination removal
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.removedNodes.length > 0 || mutation.addedNodes.length > 0) {
                        setTimeout(calculateRequiredDays, 100);
                    }
                });
            });
            
            const selectedDestinations = document.getElementById('selectedDestinations');
            if (selectedDestinations) {
                observer.observe(selectedDestinations, { childList: true, subtree: true });
            }
        });
        
        // ========================================================================
        // FUNCTION TO REMOVE TRIP CART ITEM
        // ========================================================================
        function removeTripCartItem(tourismId) {
            if (!confirm('Hapus destinasi ini dari trip cart?')) {
                return;
            }

            // Create a temporary form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/trip-cart/${tourismId}`;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection

