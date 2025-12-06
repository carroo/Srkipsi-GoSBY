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

                                <!-- Jumlah Hari & Waktu - Compact Grid -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <!-- Jumlah Hari -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-900 mb-1">
                                            Jumlah Hari <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="duration_days" id="durationDays" min="1"
                                            max="30" value="1" required
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-center font-bold text-lg">
                                    </div>

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
                                                placeholder="Lat" step="0.000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                            <input type="number" name="start_longitude" id="startLongitude"
                                                placeholder="Lng" step="0.000001"
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
                                                step="0.000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                            <input type="number" name="end_longitude" id="endLongitude"
                                                placeholder="Lng" step="0.000001"
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
                                <div id="accommodationSection" class="mb-4" style="display: none;">
                                    <div class="bg-amber-50 border-l-4 border-amber-400 p-2 mb-2 rounded-r">
                                        <p class="text-xs text-amber-800 font-medium flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Multi-hari perlu penginapan
                                        </p>
                                    </div>

                                    <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        Penginapan <span class="text-red-500">*</span>
                                    </label>

                                    <div class="space-y-2">
                                        <input type="text" name="accommodation_name" id="accommodationName"
                                            placeholder="Nama hotel"
                                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="number" name="accommodation_latitude"
                                                id="accommodationLatitude" placeholder="Lat" step="0.000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                            <input type="number" name="accommodation_longitude"
                                                id="accommodationLongitude" placeholder="Lng" step="0.000001"
                                                class="px-2 py-1.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-xs">
                                        </div>
                                        <button type="button" id="openAccommodationMapBtn"
                                            class="w-full bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition duration-300 flex items-center justify-center">
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
                                                                max="480" step="15" value="60" required
                                                                class="w-24 px-3 py-1.5 border-2 border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm font-semibold text-center">
                                                            <span class="text-sm font-medium text-gray-600">menit</span>
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
        // Function to remove trip cart item
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

