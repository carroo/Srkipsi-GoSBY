@extends('layout')

@section('title', 'Buat Itinerary Perjalanan')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-black mb-2">Buat Itinerary Perjalanan</h1>
                <p class="text-lg text-blue-100">
                    Rencanakan perjalanan wisata Anda dengan mudah
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Side: Trip Cart -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">
                                <svg class="w-6 h-6 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Trip Cart Anda
                            </h2>
                            <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                                {{ $tripCartItems->count() }} destinasi
                            </span>
                        </div>

                        @if ($tripCartItems->count() > 0)
                            <div class="space-y-4" id="tripCartList">
                                @foreach ($tripCartItems as $item)
                                    <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow duration-300 border border-gray-200"
                                        data-cart-id="{{ $item->id }}" data-tourism-id="{{ $item->tourism_id }}">
                                        <div class="flex items-start gap-4">
                                            <!-- Image -->
                                            <div class="flex-shrink-0">
                                                @if ($item->tourism->files && $item->tourism->files->count() > 0)
                                                    <img src="{{ $item->tourism->files->first()->file_path }}"
                                                        alt="{{ $item->tourism->name }}"
                                                        class="w-26 h-26 object-cover rounded-lg">
                                                @else
                                                    <div
                                                        class="w-26 h-26 bg-gray-300 rounded-lg flex items-center justify-center">
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
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                    {{ $item->tourism->name }}</h3>
                                                <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                                    {{ Str::limit($item->tourism->description, 100) }}</p>

                                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-2">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                        </svg>
                                                        {{ Str::limit($item->tourism->location, 30) }}
                                                    </div>
                                                    @if ($item->tourism->rating)
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                </path>
                                                            </svg>
                                                            {{ number_format($item->tourism->rating, 1) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Price Info -->
                                                <div class="mb-2">
                                                    @if ($item->tourism->prices && $item->tourism->prices->count() > 0)
                                                        @php
                                                            $minPrice = $item->tourism->prices->min('price');
                                                            $maxPrice = $item->tourism->prices->max('price');
                                                        @endphp
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 mr-1 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            @if ($minPrice == $maxPrice)
                                                                <span class="font-semibold text-green-600">Rp
                                                                    {{ number_format($minPrice, 0, ',', '.') }}</span>
                                                            @elseif ($minPrice === 0)
                                                                <span class="font-semibold text-green-600">Gratis</span>
                                                            @else
                                                                <span class="font-semibold text-green-600">Rp
                                                                    {{ number_format($minPrice, 0, ',', '.') }} - Rp
                                                                    {{ number_format($maxPrice, 0, ',', '.') }}</span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 mr-1 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                                                </path>
                                                            </svg>
                                                            <span class="font-semibold text-green-600">Gratis</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if ($item->tourism->categories && $item->tourism->categories->count() > 0)
                                                    <div class="flex flex-wrap gap-2 mt-2">
                                                        @foreach ($item->tourism->categories->take(3) as $category)
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                                {{ $category->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Delete Button -->
                                            <div class="flex-shrink-0">
                                                <button onclick="removeFromCart({{ $item->tourism_id }})"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Trip cart Anda kosong</h3>
                                <p class="text-gray-600 mb-4">Tambahkan destinasi wisata ke trip cart untuk membuat
                                    itinerary</p>
                                <a href="{{ route('tourism.index') }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Jelajahi Wisata
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Side: Itinerary Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">
                            <svg class="w-6 h-6 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Form Itinerary
                        </h2>

                        <form id="itineraryForm" method="POST" action="{{ route('itinerary.generate') }}">
                            @csrf

                            <!-- Nama Itinerary -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Itinerary
                                </label>
                                <input type="text" name="name" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Misal: Liburan Surabaya">
                            </div>

                            <!-- Tanggal Perjalanan -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Perjalanan
                                </label>
                                <input type="date" name="travel_date" required min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Titik Awal -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Titik Awal Perjalanan
                                </label>

                                <!-- Radio Selection -->
                                <div class="space-y-3 mb-3">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="start_point_type" value="from_cart" checked
                                            class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Pilih dari Trip Cart</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="start_point_type" value="custom"
                                            class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Lokasi Custom</span>
                                    </label>
                                </div>

                                <!-- Select from Cart -->
                                <div id="fromCartSection">
                                    <select name="start_tourism_id" id="startTourismSelect"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih destinasi...</option>
                                        @foreach ($tripCartItems as $item)
                                            <option value="{{ $item->tourism_id }}">{{ $item->tourism->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Custom Location -->
                                <div id="customLocationSection" style="display: none;">
                                    <div class="space-y-3">
                                        <button type="button" onclick="openMapModal()"
                                            class="w-full px-4 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Deteksi Lokasi
                                        </button>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Latitude</label>
                                                <input type="text" name="start_lat" id="startLat" readonly
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50"
                                                    placeholder="-7.250445">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Longitude</label>
                                                <input type="text" name="start_long" id="startLong" readonly
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50"
                                                    placeholder="112.768845">
                                            </div>
                                        </div>

                                        <div id="customLocationPreview"
                                            class="hidden p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <p class="text-sm text-green-800 font-medium">✓ Lokasi telah dipilih</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="generateBtn"
                                class="w-full px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center disabled:bg-gray-400 disabled:cursor-not-allowed"
                                @if ($tripCartItems->count() == 0) disabled @endif>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Generate Itinerary
                            </button>

                            @if ($tripCartItems->count() == 0)
                                <p class="text-xs text-red-600 text-center mt-2">Tambahkan minimal 1 destinasi untuk
                                    membuat itinerary</p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Modal -->
    <div id="mapModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 z-50 hidden items-center justify-center p-4"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="text-xl font-bold text-gray-900">Pilih Lokasi Awal</h3>
                <button onclick="closeMapModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-4">
                    <button onclick="detectCurrentLocation()"
                        class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                            </path>
                        </svg>
                        Gunakan Lokasi Saya Sekarang
                    </button>
                </div>

                <div id="map" style="height: 400px;" class="rounded-lg border border-gray-300"></div>

                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Koordinat yang dipilih:</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Latitude</label>
                            <input type="text" id="modalLat" readonly
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Longitude</label>
                            <input type="text" id="modalLong" readonly
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white">
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-3">
                    <button onclick="confirmLocation()"
                        class="flex-1 px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Konfirmasi Lokasi
                    </button>
                    <button onclick="closeMapModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .leaflet-container {
            height: 400px;
            border-radius: 0.5rem;
        }
    </style>

    <script>
        let map;
        let marker;
        let selectedLat = null;
        let selectedLong = null;

        // Toggle between from cart and custom location
        document.querySelectorAll('input[name="start_point_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const fromCartSection = document.getElementById('fromCartSection');
                const customLocationSection = document.getElementById('customLocationSection');
                const startTourismSelect = document.getElementById('startTourismSelect');
                const startLat = document.getElementById('startLat');
                const startLong = document.getElementById('startLong');

                if (this.value === 'from_cart') {
                    fromCartSection.style.display = 'block';
                    customLocationSection.style.display = 'none';
                    startTourismSelect.required = true;
                    startLat.required = false;
                    startLong.required = false;
                } else {
                    fromCartSection.style.display = 'none';
                    customLocationSection.style.display = 'block';
                    startTourismSelect.required = false;
                    startLat.required = true;
                    startLong.required = true;
                }
            });
        });

        function openMapModal() {
            const modal = document.getElementById('mapModal');
            modal.style.display = 'flex';
            modal.classList.remove('hidden');

            // Initialize map if not already initialized
            if (!map) {
                // Center on Surabaya
                map = L.map('map').setView([-7.250445, 112.768845], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Add click event to map
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    // Remove existing marker if any
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Add new marker
                    marker = L.marker([lat, lng]).addTo(map);

                    // Update modal inputs
                    document.getElementById('modalLat').value = lat.toFixed(6);
                    document.getElementById('modalLong').value = lng.toFixed(6);

                    selectedLat = lat;
                    selectedLong = lng;
                });
            }

            // Force map to recalculate size
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }

        function closeMapModal() {
            const modal = document.getElementById('mapModal');
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }

        function detectCurrentLocation() {
            if (navigator.geolocation) {
                showNotification('Mendeteksi lokasi...', 'info');

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Remove existing marker if any
                        if (marker) {
                            map.removeLayer(marker);
                        }

                        // Add new marker
                        marker = L.marker([lat, lng]).addTo(map);
                        map.setView([lat, lng], 15);

                        // Update modal inputs
                        document.getElementById('modalLat').value = lat.toFixed(6);
                        document.getElementById('modalLong').value = lng.toFixed(6);

                        selectedLat = lat;
                        selectedLong = lng;

                        showNotification(
                            'Lokasi terdeteksi! Anda dapat menggeser marker atau klik peta untuk mengubah lokasi',
                            'success');
                    },
                    function(error) {
                        showNotification('Gagal mendeteksi lokasi. Pastikan Anda telah mengizinkan akses lokasi',
                            'error');
                    }
                );
            } else {
                showNotification('Browser Anda tidak mendukung geolocation', 'error');
            }
        }

        function confirmLocation() {
            if (selectedLat && selectedLong) {
                document.getElementById('startLat').value = selectedLat.toFixed(6);
                document.getElementById('startLong').value = selectedLong.toFixed(6);
                document.getElementById('customLocationPreview').classList.remove('hidden');
                closeMapModal();

                showNotification('Lokasi berhasil dipilih!', 'success');
            } else {
                showNotification('Silakan klik pada peta untuk memilih lokasi', 'warning');
            }
        }

        function removeFromCart(tourismId) {
            showConfirmation(
                'Hapus dari Trip Cart?',
                'Destinasi akan dihapus dari trip cart Anda',
                'Ya, Hapus!',
                'Batal'
            ).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/trip-cart/remove/${tourismId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showNotification('Berhasil dihapus dari trip cart!', 'success');

                            // Remove the item from DOM
                            $(`[data-tourism-id="${tourismId}"]`).fadeOut(300, function() {
                                $(this).remove();

                                // Update count
                                const count = $('#tripCartList > div').length;
                                $('.bg-blue-100.text-blue-800').text(`${count} destinasi`);

                                // Show empty state if no items
                                if (count === 0) {
                                    location.reload();
                                }

                                // Remove from select option
                                $(`#startTourismSelect option[value="${tourismId}"]`).remove();
                            });
                        },
                        error: function(xhr) {
                            showNotification('Gagal menghapus destinasi', 'error');
                        }
                    });
                }
            });
        }

        // Form submission with AJAX
        $('#itineraryForm').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const submitBtn = $('#generateBtn');

            // Disable button
            submitBtn.prop('disabled', true);

            // Show loading modal
            showLoadingModal();

            $.ajax({
                url: '{{ route('itinerary.generate') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        hideLoadingModal();
                        showNotification(response.message, 'success');

                        // Redirect after short delay
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 500);
                    }
                },
                error: function(xhr) {
                    hideLoadingModal();
                    submitBtn.prop('disabled', false);

                    let errorMessage = 'Gagal membuat itinerary';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = errors.join(', ');
                    }

                    showNotification(errorMessage, 'error');
                }
            });
        });

        function showLoadingModal() {
            const modal = $('<div>', {
                id: 'loadingModal',
                class: 'fixed inset-0 backdrop-blur-sm bg-black/50 z-50 flex items-center justify-center',
                html: `
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
                    <div class="mb-6">
                        <!-- Animated Globe/Map Icon -->
                        <div class="relative inline-block">
                            <svg class="w-24 h-24 mx-auto text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                                <div class="w-20 h-20 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Sedang Membuat Itinerary</h3>
                    
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center justify-center text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-green-500 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium loading-text">Menganalisis destinasi...</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 h-full rounded-full animate-progress"></div>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-500">
                        Proses ini membutuhkan waktu <strong>10-30 detik</strong><br>
                        Mohon tunggu sebentar...
                    </p>
                </div>
            `
            });

            $('body').append(modal);

            // Animated loading text
            const loadingTexts = [
                'Menganalisis destinasi...',
                'Menghitung jarak optimal...',
                'Menyusun rute terbaik...',
                'Mengoptimalkan perjalanan...',
                'Memproses data lokasi...',
                'Membuat peta rute...'
            ];

            let textIndex = 0;
            window.loadingTextInterval = setInterval(() => {
                textIndex = (textIndex + 1) % loadingTexts.length;
                $('.loading-text').fadeOut(200, function() {
                    $(this).text(loadingTexts[textIndex]).fadeIn(200);
                });
            }, 2000);
        }

        function hideLoadingModal() {
            clearInterval(window.loadingTextInterval);
            $('#loadingModal').fadeOut(300, function() {
                $(this).remove();
            });
        }
    </script>

    <style>
        @keyframes progress {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(400%);
            }
        }

        .animate-progress {
            animation: progress 2s ease-in-out infinite;
        }
    </style>
@endsection
