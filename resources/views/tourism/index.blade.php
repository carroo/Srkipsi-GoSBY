@extends('layout')

@section('title', 'Jelajahi Wisata Surabaya')

@section('content')
    <style>
        /* Minimal custom CSS - only for complex animations and dynamic states */
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

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-slide-down {
            animation: slideDown 0.4s ease-out;
        }

        .criteria-item.dragging {
            opacity: 0.5;
            transform: scale(0.95);
        }

        .criteria-item.drag-over {
            border-color: #3b82f6 !important;
            background-color: #eff6ff !important;
        }
    </style>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in-up">
                <h1 class="text-3xl md:text-4xl font-black mb-2">Rekomendasi Wisata Surabaya</h1>
                <p class="text-lg text-blue-100">
                    Destinasi wisata terbaik berdasarkan algoritma SAW (Simple Additive Weighting)
                </p>
            </div>
        </div>
    </section>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center"
        style="display: none;">
        <div class="bg-white rounded-xl p-8 flex flex-col items-center shadow-2xl border border-gray-200">
            <svg class="animate-spin h-16 w-16 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-gray-700 font-semibold text-lg">Memproses rekomendasi...</p>
            <p class="text-gray-500 text-sm mt-2">Mohon tunggu sebentar</p>
        </div>
    </div>

    <!-- Main Content Section -->
    <section class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6 justify-center">
                <div class="w-full md:w-1/3">
                    <div class="md:sticky md:top-6">
                        <form id="sawForm" method="GET" action="{{ route('tourism.index') }}"
                            class="bg-white rounded-xl shadow-lg p-6">
                            @csrf

                            <!-- Info Box -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-4 rounded-r">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-semibold">Urutkan kriteria dari yang paling penting sampai yang
                                            kurang penting</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            💡 Geser kriteria atau gunakan tombol panah ⬆️ untuk mengurutkan
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Advanced Mode Toggle -->
                            <div class="mb-4">
                                <button type="button" onclick="toggleAdvancedMode()"
                                    class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                                    <svg id="advancedIcon" class="w-4 h-4 mr-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span id="advancedText">Mode Lanjutan (Atur Nilai Manual)</span>
                                </button>
                            </div>

                            <!-- Advanced Mode Info (Hidden by default) -->
                            <div id="advancedInfo" class="bg-amber-50 border-l-4 border-amber-500 p-3 mb-4 rounded-r"
                                style="display: none;">
                                <div class="text-sm text-amber-800">
                                    <p class="font-semibold mb-1">Mode Lanjutan Aktif</p>
                                    <p class="text-xs">Anda dapat mengatur nilai prioritas secara manual. Total harus 100%
                                    </p>
                                </div>
                            </div>

                            <!-- Drag and Drop Container -->
                            <div id="criteriaContainer" class="space-y-4 mb-6">
                                <!-- Criteria 1: Popularity -->
                                <div class="criteria-item relative bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-4 hover:shadow-lg transition-all duration-300 cursor-move hover:-translate-y-0.5"
                                    draggable="true" data-criterion="popularity" data-rank="1">
                                    <div
                                        class="absolute -top-2 -left-2 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-lg z-10 bg-gradient-to-br from-amber-400 to-amber-500 text-amber-900 rank-badge rank-1">
                                        1</div>
                                    <button type="button"
                                        class="move-up-btn absolute top-1/2 -right-3 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 active:scale-95 z-[15] border-2 border-white disabled"
                                        onclick="moveUp(this)" title="Pindahkan ke atas">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </button>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 ml-6">
                                            <div class="bg-purple-500 p-2.5 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-900 text-sm">Popularitas</h3>
                                                <p class="text-xs text-gray-600">Tingkat kepopuleran wisata</p>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="weight-label-text text-lg hidden font-bold text-gray-900">50%</div>
                                            <input type="number"
                                                class="weight-input-field hidden w-[70px] px-2 py-1 border-2 border-blue-500 rounded-md text-base font-bold text-center"
                                                min="0" max="100" step="1" value="50">
                                        </div>
                                    </div>
                                </div>

                                <!-- Criteria 2: Rating -->
                                <div class="criteria-item relative bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-xl p-4 hover:shadow-lg transition-all duration-300 cursor-move hover:-translate-y-0.5"
                                    draggable="true" data-criterion="rating" data-rank="2">
                                    <div
                                        class="absolute -top-2 -left-2 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-lg z-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rank-badge rank-2">
                                        2</div>
                                    <button type="button"
                                        class="move-up-btn absolute top-1/2 -right-3 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 active:scale-95 z-[15] border-2 border-white"
                                        onclick="moveUp(this)" title="Pindahkan ke atas">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </button>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 ml-6">
                                            <div class="bg-yellow-500 p-2.5 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-900 text-sm">Rating</h3>
                                                <p class="text-xs text-gray-600">Penilaian pengunjung</p>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="weight-label-text text-lg hidden font-bold text-gray-900">30%</div>
                                            <input type="number"
                                                class="weight-input-field hidden w-[70px] px-2 py-1 border-2 border-blue-500 rounded-md text-base font-bold text-center"
                                                min="0" max="100" step="1" value="30">
                                        </div>
                                    </div>
                                </div>

                                <!-- Criteria 3: Price -->
                                <div class="criteria-item relative bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4 hover:shadow-lg transition-all duration-300 cursor-move hover:-translate-y-0.5"
                                    draggable="true" data-criterion="price" data-rank="3">
                                    <div
                                        class="absolute -top-2 -left-2 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-lg z-10 bg-gradient-to-br from-green-500 to-green-600 text-white rank-badge rank-3">
                                        3</div>
                                    <button type="button"
                                        class="move-up-btn absolute top-1/2 -right-3 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 active:scale-95 z-[15] border-2 border-white"
                                        onclick="moveUp(this)" title="Pindahkan ke atas">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </button>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 ml-6">
                                            <div class="bg-green-500 p-2.5 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-900 text-sm">Harga</h3>
                                                <p class="text-xs text-gray-600">Biaya tiket masuk</p>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="weight-label-text text-lg hidden font-bold text-gray-900">20%</div>
                                            <input type="number"
                                                class="weight-input-field hidden w-[70px] px-2 py-1 border-2 border-blue-500 rounded-md text-base font-bold text-center"
                                                min="0" max="100" step="1" value="20">
                                        </div>
                                    </div>
                                </div>

                                <!-- Criteria 4: Distance (Hidden by default) -->
                                <div class="criteria-item relative bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl p-4 hover:shadow-lg transition-all duration-300 cursor-move hover:-translate-y-0.5"
                                    draggable="true" data-criterion="distance" data-rank="4" id="distanceCriteria"
                                    style="display: none;">
                                    <div
                                        class="absolute -top-2 -left-2 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-lg z-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rank-badge rank-4">
                                        4</div>
                                    <button type="button"
                                        class="move-up-btn absolute top-1/2 -right-3 -translate-y-1/2 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 active:scale-95 z-[15] border-2 border-white"
                                        onclick="moveUp(this)" title="Pindahkan ke atas">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </button>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 ml-6">
                                            <div class="bg-blue-500 p-2.5 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-900 text-sm">Jarak</h3>
                                                <p class="text-xs text-gray-600">Kedekatan lokasi</p>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="weight-label-text text-lg hidden font-bold text-gray-900">0%</div>
                                            <input type="number"
                                                class="weight-input-field hidden w-[70px] px-2 py-1 border-2 border-blue-500 rounded-md text-base font-bold text-center"
                                                min="0" max="100" step="1" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Distance Location Input (Conditional) -->
                            <div id="distanceSection" class="mb-6 p-4 bg-blue-50 border-2 border-blue-200 rounded-xl">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-bold text-gray-900">Lokasi Anda (Opsional)</label>
                                    <button type="button" id="detectLocationBtn"
                                        class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-semibold">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Deteksi GPS
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Latitude</label>
                                        <input type="text" id="latitudeInput" name="latitude" placeholder="-7.2575"
                                            class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Longitude</label>
                                        <input type="text" id="longitudeInput" name="longitude"
                                            placeholder="112.7521"
                                            class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Inputs for Weights -->
                            <input type="hidden" name="weight_popularity" id="weight_popularity" value="0.5">
                            <input type="hidden" name="weight_rating" id="weight_rating" value="0.3">
                            <input type="hidden" name="weight_price" id="weight_price" value="0.2">
                            <input type="hidden" name="weight_distance" id="weight_distance" value="0">

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Terapkan Prioritas
                            </button>

                            <!-- Reset Button -->
                            <button type="button" onclick="resetCriteria()"
                                class="w-full mt-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg transition duration-300">
                                Reset ke Default
                            </button>

                            <!-- Warning for Advanced Mode -->
                            <div id="advancedWarning" class="mt-3 text-center text-xs text-red-600 font-semibold"
                                style="display: none;">
                                ⚠️ Total harus 100%
                            </div>
                        </form>
                    </div>
                </div>
                <div class="w-full md:w-2/3 hidden" id="resultsSection">
                    <!-- Filter Section -->
                    <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                        <div class="flex items-center gap-4">
                            <!-- Search -->
                            <div class="flex-1">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" id="searchInput" placeholder="Cari wisata atau lokasi..."
                                        value="{{ request('search') }}"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div style="min-width: 200px;">
                                <select id="categoryFilter"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Results Info -->
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6 rounded-r-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <div>
                                    <p class="font-bold text-blue-900">Hasil Rekomendasi SAW</p>
                                    <p class="text-sm text-blue-700">
                                        <span id="showingCount">12</span> dari <span
                                            id="totalCount">{{ $tourisms->count() }}</span> wisata
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button onclick="showSAWCalculation()"
                                    class="inline-flex items-center bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Lihat Perhitungan
                                </button>
                                @if (request('search') || request('category'))
                                    <button onclick="resetFilters()"
                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                        Reset Filter
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tourism Cards Container -->
                    <div id="tourismCardsContainer">

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SAW Calculation Modal -->
    <div id="sawCalculationModal"
        class="fixed inset-0 backdrop-blur-md bg-white/30 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-7xl w-full max-h-[90vh] overflow-hidden border border-gray-200">
            <!-- Modal Header -->
            <div
                class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    <div>
                        <h2 class="text-2xl font-bold">Hasil Perhitungan SAW</h2>
                        <p class="text-sm text-purple-100">Simple Additive Weighting Algorithm</p>
                    </div>
                </div>
                <button onclick="closeSAWCalculation()" class="text-white hover:text-purple-200 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <!-- Weights Info -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r">
                    <h3 class="font-bold text-gray-900 mb-3">Bobot Kriteria</h3>
                    <div id="weightsInfo" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Calculation Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gradient-to-r from-gray-100 to-gray-50">
                            <tr>
                                <th
                                    class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase border-r border-gray-200">
                                    Rank</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase border-r border-gray-200">
                                    Wisata</th>
                                <th colspan="4"
                                    class="px-4 py-2 text-center text-xs font-bold text-purple-700 uppercase bg-purple-50 border-r border-gray-200">
                                    Nilai Asli</th>
                                <th colspan="4"
                                    class="px-4 py-2 text-center text-xs font-bold text-blue-700 uppercase bg-blue-50 border-r border-gray-200">
                                    Normalisasi</th>
                                <th colspan="4"
                                    class="px-4 py-2 text-center text-xs font-bold text-green-700 uppercase bg-green-50 border-r border-gray-200">
                                    Bobot × Nilai</th>
                                <th rowspan="2"
                                    class="px-4 py-3 text-center text-xs font-bold text-yellow-800 uppercase bg-yellow-100">
                                    Skor SAW</th>
                            </tr>
                            <tr>
                                <th class="px-3 py-2 border-r border-gray-200"></th>
                                <th class="px-4 py-2 border-r border-gray-200"></th>
                                <!-- Raw Values -->
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-purple-600 bg-purple-50 border-r border-gray-200">
                                    Pop</th>
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-purple-600 bg-purple-50 border-r border-gray-200">
                                    Rat</th>
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-purple-600 bg-purple-50 border-r border-gray-200">
                                    Hrg</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-purple-600 bg-purple-50 border-r border-gray-200"
                                    id="rawDistHeader" style="display: none;">Jrk</th>
                                <!-- Normalized -->
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-blue-600 bg-blue-50 border-r border-gray-200">
                                    Pop</th>
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-blue-600 bg-blue-50 border-r border-gray-200">
                                    Rat</th>
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-blue-600 bg-blue-50 border-r border-gray-200">
                                    Hrg</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-blue-600 bg-blue-50 border-r border-gray-200"
                                    id="normDistHeader" style="display: none;">Jrk</th>
                                <!-- Weighted -->
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-green-600 bg-green-50 border-r border-gray-200">
                                    Pop</th>
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-green-600 bg-green-50 border-r border-gray-200">
                                    Rat</th>
                                <th
                                    class="px-3 py-2 text-center text-xs font-semibold text-green-600 bg-green-50 border-r border-gray-200">
                                    Hrg</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-green-600 bg-green-50 border-r border-gray-200"
                                    id="weightDistHeader" style="display: none;">Jrk</th>
                            </tr>
                        </thead>
                        <tbody id="calculationTableBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Formula Info -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-bold text-gray-900 mb-2">Formula SAW</h3>
                    <div class="text-sm text-gray-700">
                        <p class="mb-2"><strong>1. Normalisasi:</strong></p>
                        <ul class="ml-4 mb-3 space-y-1">
                            <li>• <strong>Benefit (Popularity, Rating):</strong> r<sub>ij</sub> = x<sub>ij</sub> /
                                max(x<sub>ij</sub>)</li>
                            <li>• <strong>Cost (Price, Distance):</strong> r<sub>ij</sub> = min(x<sub>ij</sub>) /
                                x<sub>ij</sub></li>
                        </ul>
                        <p class="mb-2"><strong>2. Skor SAW:</strong></p>
                        <p class="ml-4 font-mono">V<sub>i</sub> = Σ (w<sub>j</sub> × r<sub>ij</sub>)</p>
                        <p class="mt-2 text-xs text-gray-600">Dimana: V<sub>i</sub> = Skor alternatif ke-i, w<sub>j</sub> =
                            Bobot kriteria ke-j, r<sub>ij</sub> = Nilai normalisasi</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-100 px-6 py-4 flex justify-end">
                <button onclick="closeSAWCalculation()"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // SAW data from server (will be updated by AJAX)
        let sawCalculationData = @json([
            'weights' => $weights ?? [],
            'calculations' => $calculations ?? [],
        ]);

        // SAW Calculation Modal Functions
        function showSAWCalculation() {
            if (!sawCalculationData.calculations || sawCalculationData.calculations.length === 0) {
                showNotification('Belum ada hasil perhitungan SAW. Silakan terapkan filter SAW terlebih dahulu.', 'error');
                return;
            }

            // Show loading overlay
            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';

            // Small delay to show loading
            setTimeout(() => {
                populateSAWModal(sawCalculationData);

                // Hide loading overlay
                loadingOverlay.style.display = 'none';

                // Show modal with flex display
                const modal = document.getElementById('sawCalculationModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }, 300);
        }

        function closeSAWCalculation() {
            const modal = document.getElementById('sawCalculationModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function populateSAWModal(sawData) {
            // Populate weights info
            const weightsInfo = document.getElementById('weightsInfo');
            const weights = sawData.weights || {};

            const criteriaLabels = {
                'popularity': 'Popularitas',
                'rating': 'Rating',
                'price': 'Harga',
                'distance': 'Jarak'
            };

            weightsInfo.innerHTML = '';

            // Display all weights
            Object.keys(weights).forEach(key => {
                const label = criteriaLabels[key] || key;
                const weight = (weights[key] * 100).toFixed(0);
                weightsInfo.innerHTML += `
                <div class="flex items-center">
                    <span class="font-semibold text-gray-700 mr-2">${label}:</span>
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold">${weight}%</span>
                </div>
            `;
            });

            // Populate calculation table
            const tableBody = document.getElementById('calculationTableBody');
            tableBody.innerHTML = '';

            // Check if distance is used
            const hasDistance = sawData.calculations.some(item => item.raw_values.distance !== undefined);

            // Show/hide distance headers
            const distanceHeaders = ['rawDistHeader', 'normDistHeader', 'weightDistHeader'];
            distanceHeaders.forEach(id => {
                const header = document.getElementById(id);
                if (header) {
                    header.style.display = hasDistance ? '' : 'none';
                }
            });

            sawData.calculations.forEach((item, index) => {
                const rank = index + 1;
                const rawValues = item.raw_values;
                const normalized = item.normalized;
                const weighted = item.weighted;

                // Create row with ranking badge
                let rankBadge = '';
                if (rank === 1) {
                    rankBadge =
                        '<span class="inline-flex items-center justify-center w-10 h-10 bg-yellow-400 text-yellow-900 rounded-full font-bold text-lg">🥇</span>';
                } else if (rank === 2) {
                    rankBadge =
                        '<span class="inline-flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-700 rounded-full font-bold text-lg">🥈</span>';
                } else if (rank === 3) {
                    rankBadge =
                        '<span class="inline-flex items-center justify-center w-10 h-10 bg-orange-300 text-orange-700 rounded-full font-bold text-lg">🥉</span>';
                } else {
                    rankBadge =
                        `<span class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-600 rounded-full font-bold">${rank}</span>`;
                }

                const row = `
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="px-3 py-4 text-center border-r border-gray-200">${rankBadge}</td>
                    <td class="px-4 py-4 border-r border-gray-200">
                        <div class="font-semibold text-gray-900">${item.tourism.name}</div>
                        <div class="text-xs text-gray-500">${item.city || ''}</div>
                    </td>
                    <!-- Raw Values -->
                    <td class="px-3 py-4 text-center text-sm bg-purple-50 border-r border-gray-200">${rawValues.popularity || 0}</td>
                    <td class="px-3 py-4 text-center text-sm bg-purple-50 border-r border-gray-200">${parseFloat(rawValues.rating || 0).toFixed(1)}</td>
                    <td class="px-3 py-4 text-center text-sm bg-purple-50 border-r border-gray-200">${parseInt(rawValues.price || 0).toLocaleString('id-ID')}</td>
                    ${hasDistance ? `<td class="px-3 py-4 text-center text-sm bg-purple-50 border-r border-gray-200">${rawValues.distance ? parseFloat(rawValues.distance).toFixed(2) : '-'}</td>` : ''}
                    <!-- Normalized -->
                    <td class="px-3 py-4 text-center text-sm bg-blue-50 border-r border-gray-200">${parseFloat(normalized.popularity || 0).toFixed(4)}</td>
                    <td class="px-3 py-4 text-center text-sm bg-blue-50 border-r border-gray-200">${parseFloat(normalized.rating || 0).toFixed(4)}</td>
                    <td class="px-3 py-4 text-center text-sm bg-blue-50 border-r border-gray-200">${parseFloat(normalized.price || 0).toFixed(4)}</td>
                    ${hasDistance ? `<td class="px-3 py-4 text-center text-sm bg-blue-50 border-r border-gray-200">${normalized.distance ? parseFloat(normalized.distance).toFixed(4) : '-'}</td>` : ''}
                    <!-- Weighted -->
                    <td class="px-3 py-4 text-center text-sm bg-green-50 border-r border-gray-200">${parseFloat(weighted.popularity || 0).toFixed(4)}</td>
                    <td class="px-3 py-4 text-center text-sm bg-green-50 border-r border-gray-200">${parseFloat(weighted.rating || 0).toFixed(4)}</td>
                    <td class="px-3 py-4 text-center text-sm bg-green-50 border-r border-gray-200">${parseFloat(weighted.price || 0).toFixed(4)}</td>
                    ${hasDistance ? `<td class="px-3 py-4 text-center text-sm bg-green-50 border-r border-gray-200">${weighted.distance ? parseFloat(weighted.distance).toFixed(4) : '-'}</td>` : ''}
                    <!-- SAW Score -->
                    <td class="px-4 py-4 text-center bg-yellow-50 border-gray-200">
                        <div class="font-bold text-lg text-yellow-900">${parseFloat(item.saw_score).toFixed(4)}</div>
                    </td>
                </tr>
            `;

                tableBody.innerHTML += row;
            });
        }

        // Drag and Drop Functionality
        let draggedElement = null;
        let advancedMode = false;
        let distanceEnabled = false;
        let isLoading = false;

        // Pagination variables
        let currentPage = 1;
        let itemsPerPage = 9;
        let totalItems = 0;

        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
            updateWeights();
            updateMoveUpButtons();

            // Filter functionality with AJAX
            document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 500));
            document.getElementById('categoryFilter').addEventListener('change', applyFilters);

            // SAW Form with AJAX
            document.getElementById('sawForm').addEventListener('submit', handleSAWSubmit);

            // Monitor location inputs
            document.getElementById('latitudeInput').addEventListener('input', checkLocationInputs);
            document.getElementById('longitudeInput').addEventListener('input', checkLocationInputs);

            // Detect location button
            document.getElementById('detectLocationBtn').addEventListener('click', detectLocation);

            // Advanced mode weight inputs
            document.querySelectorAll('.criteria-item').forEach(item => {
                const input = item.querySelector('.weight-input-field');
                if (input) {
                    input.addEventListener('input', handleWeightInputChange);
                }
            });

            // Initialize pagination
            initializePagination();
        });

        function checkLocationInputs() {
            const lat = document.getElementById('latitudeInput').value.trim();
            const lng = document.getElementById('longitudeInput').value.trim();
            const distanceCriteria = document.getElementById('distanceCriteria');

            if (lat && lng) {
                // Show distance criteria
                if (!distanceEnabled) {
                    distanceEnabled = true;
                    distanceCriteria.style.display = 'block';

                    // Add animation
                    setTimeout(() => {
                        distanceCriteria.style.opacity = '0';
                        distanceCriteria.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            distanceCriteria.style.transition = 'all 0.3s ease';
                            distanceCriteria.style.opacity = '1';
                            distanceCriteria.style.transform = 'translateY(0)';
                        }, 10);
                    }, 10);

                    // Re-init drag and drop
                    initializeDragAndDrop();
                    updateWeights();
                    updateMoveUpButtons();
                }
            } else {
                // Hide distance criteria
                if (distanceEnabled) {
                    distanceEnabled = false;
                    distanceCriteria.style.display = 'none';

                    updateWeights();
                    updateMoveUpButtons();
                }
            }
        }

        function toggleAdvancedMode() {
            advancedMode = !advancedMode;
            const advancedIcon = document.getElementById('advancedIcon');
            const advancedText = document.getElementById('advancedText');
            const advancedInfo = document.getElementById('advancedInfo');
            const advancedWarning = document.getElementById('advancedWarning');

            if (advancedMode) {
                // Show advanced mode
                advancedIcon.style.transform = 'rotate(90deg)';
                advancedText.textContent = 'Mode Normal (Otomatis)';
                advancedInfo.style.display = 'block';
                advancedWarning.style.display = 'block';

                // Show input fields, hide labels (only for visible criteria)
                document.querySelectorAll('.criteria-item').forEach(item => {
                    if (item.style.display !== 'none') {
                        const label = item.querySelector('.weight-label-text');
                        const input = item.querySelector('.weight-input-field');
                        if (label) label.classList.add('hidden');
                        if (input) input.classList.remove('hidden');
                    }
                });

                // Disable drag and drop
                document.querySelectorAll('.criteria-item').forEach(item => {
                    if (item.getAttribute('draggable') === 'true') {
                        item.setAttribute('draggable', 'false');
                        item.classList.remove('cursor-move');
                        item.classList.add('cursor-default');
                    }
                });
            } else {
                // Show normal mode
                advancedIcon.style.transform = 'rotate(0deg)';
                advancedText.textContent = 'Mode Lanjutan (Atur Nilai Manual)';
                advancedInfo.style.display = 'none';
                advancedWarning.style.display = 'none';

                // Show labels, hide input fields
                document.querySelectorAll('.criteria-item').forEach(item => {
                    const label = item.querySelector('.weight-label-text');
                    const input = item.querySelector('.weight-input-field');
                    if (label) label.classList.add('hidden');
                    if (input) input.classList.add('hidden');
                });

                // Enable drag and drop (only for visible criteria)
                document.querySelectorAll('.criteria-item').forEach(item => {
                    if (item.style.display !== 'none') {
                        item.setAttribute('draggable', 'true');
                        item.classList.remove('cursor-default');
                        item.classList.add('cursor-move');
                    }
                });

                // Recalculate weights based on order
                updateWeights();
                updateMoveUpButtons();
            }
        }

        function handleWeightInputChange(e) {
            const input = e.target;
            let value = parseInt(input.value) || 0;

            // Clamp value between 0-100
            if (value < 0) value = 0;
            if (value > 100) value = 100;
            input.value = value;

            // Update the corresponding hidden input
            const criteriaItem = input.closest('.criteria-item');
            const criterion = criteriaItem.dataset.criterion;
            const weight = value / 100;
            document.getElementById('weight_' + criterion).value = weight;

            // Check if total is 100%
            checkTotalWeight();
        }

        function checkTotalWeight() {
            // Only count visible criteria
            const visibleInputs = Array.from(document.querySelectorAll('.criteria-item')).filter(item => {
                return item.style.display !== 'none';
            }).map(item => item.querySelector('.weight-input-field'));

            const total = visibleInputs.reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            const warning = document.getElementById('advancedWarning');
            const submitButton = document.querySelector('button[type="submit"]');

            if (total !== 100) {
                warning.textContent = `⚠️ Total: ${total}% (harus 100%)`;
                warning.style.color = '#dc2626';
                submitButton.disabled = true;
                submitButton.style.opacity = '0.5';
            } else {
                warning.textContent = '✓ Total: 100% (Benar!)';
                warning.style.color = '#059669';
                submitButton.disabled = false;
                submitButton.style.opacity = '1';
            }
        }

        function initializeDragAndDrop() {
            const criteriaItems = document.querySelectorAll('.criteria-item');

            criteriaItems.forEach(item => {
                // Skip hidden items
                if (item.style.display === 'none') {
                    return;
                }

                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragover', handleDragOver);
                item.addEventListener('drop', handleDrop);
                item.addEventListener('dragend', handleDragEnd);
                item.addEventListener('dragenter', handleDragEnter);
                item.addEventListener('dragleave', handleDragLeave);
            });
        }

        function handleDragStart(e) {
            draggedElement = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
        }

        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        function handleDragEnter(e) {
            if (this !== draggedElement) {
                this.classList.add('drag-over');
            }
        }

        function handleDragLeave(e) {
            this.classList.remove('drag-over');
        }

        function handleDrop(e) {
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            if (draggedElement !== this) {
                const container = document.getElementById('criteriaContainer');
                const allItems = Array.from(container.querySelectorAll('.criteria-item'));
                const draggedIndex = allItems.indexOf(draggedElement);
                const targetIndex = allItems.indexOf(this);

                if (draggedIndex < targetIndex) {
                    this.parentNode.insertBefore(draggedElement, this.nextSibling);
                } else {
                    this.parentNode.insertBefore(draggedElement, this);
                }

                updateWeights();
                updateMoveUpButtons();
            }

            this.classList.remove('drag-over');
            return false;
        }

        function handleDragEnd(e) {
            this.classList.remove('dragging');
            document.querySelectorAll('.criteria-item').forEach(item => {
                item.classList.remove('drag-over');
            });
        }

        function moveUp(button) {
            const criteriaItem = button.closest('.criteria-item');
            const container = document.getElementById('criteriaContainer');
            const allItems = Array.from(container.querySelectorAll('.criteria-item'));

            // Filter only visible items
            const visibleItems = allItems.filter(item => item.style.display !== 'none');
            const currentIndex = visibleItems.indexOf(criteriaItem);

            // Can't move up if already at the top
            if (currentIndex <= 0) {
                return;
            }

            // Add animation class
            criteriaItem.style.transition = 'all 0.3s ease';
            criteriaItem.style.transform = 'translateY(-10px)';

            setTimeout(() => {
                // Move the item before its previous sibling (visible item)
                const previousVisibleItem = visibleItems[currentIndex - 1];
                container.insertBefore(criteriaItem, previousVisibleItem);

                // Reset animation
                criteriaItem.style.transform = 'translateY(0)';

                // Update weights and button states
                updateWeights();
                updateMoveUpButtons();
            }, 150);
        }

        function updateMoveUpButtons() {
            const container = document.getElementById('criteriaContainer');
            const allItems = Array.from(container.querySelectorAll('.criteria-item'));
            const visibleItems = allItems.filter(item => item.style.display !== 'none');

            // Update all buttons
            visibleItems.forEach((item, index) => {
                const button = item.querySelector('.move-up-btn');
                if (button) {
                    if (index === 0) {
                        // First item - disable button
                        button.classList.add('disabled');
                    } else {
                        // Other items - enable button
                        button.classList.remove('disabled');
                    }
                }
            });
        }

        function updateWeights() {
            if (advancedMode) {
                // In advanced mode, don't auto-update weights
                checkTotalWeight();
                return;
            }

            const criteriaItems = Array.from(document.querySelectorAll('.criteria-item'));

            // Filter out hidden/disabled criteria
            const activeCriteria = criteriaItems.filter(item => {
                return item.style.display !== 'none';
            });

            // Calculate weights based on rank (descending priority)
            const totalActive = activeCriteria.length;
            const weights = [];

            // Generate weights: 50%, 30%, 20%, 0% for 4 criteria
            if (totalActive === 3) {
                weights.push(0.5, 0.3, 0.2); // 50%, 30%, 20%
            } else if (totalActive === 4) {
                weights.push(0.5, 0.3, 0.2, 0.0); // 50%, 30%, 20%, 0%
            } else if (totalActive === 2) {
                weights.push(0.7, 0.3); // 70%, 30%
            } else if (totalActive === 1) {
                weights.push(1.0); // 100%
            }

            // Reset all criteria weights to 0 first
            criteriaItems.forEach((item) => {
                const criterion = item.dataset.criterion;
                const weightLabel = item.querySelector('.weight-label-text');
                const weightInput = item.querySelector('.weight-input-field');

                if (weightLabel) weightLabel.textContent = '0%';
                if (weightInput) weightInput.value = 0;
                document.getElementById('weight_' + criterion).value = 0;
            });

            // Apply weights to active criteria based on their order
            activeCriteria.forEach((item, index) => {
                const criterion = item.dataset.criterion;
                const weight = weights[index] || 0;
                const weightPercent = Math.round(weight * 100);

                const weightLabel = item.querySelector('.weight-label-text');
                const weightInput = item.querySelector('.weight-input-field');

                if (weightLabel) weightLabel.textContent = weightPercent + '%';
                if (weightInput) weightInput.value = weightPercent;
                document.getElementById('weight_' + criterion).value = weight;

                // Update rank badge
                const rank = index + 1;
                const rankBadge = item.querySelector('.rank-badge');
                rankBadge.textContent = rank;

                // Remove all rank classes and add base classes
                rankBadge.className =
                    'absolute -top-2 -left-2 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-lg z-10 rank-badge';

                // Add specific gradient based on rank
                if (index === 0) {
                    rankBadge.classList.add('bg-gradient-to-br', 'from-amber-400', 'to-amber-500',
                        'text-amber-900');
                } else if (index === 1) {
                    rankBadge.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-blue-600', 'text-white');
                } else if (index === 2) {
                    rankBadge.classList.add('bg-gradient-to-br', 'from-green-500', 'to-green-600', 'text-white');
                } else if (index === 3) {
                    rankBadge.classList.add('bg-gradient-to-br', 'from-purple-500', 'to-purple-600', 'text-white');
                }

                // Update data attribute
                item.dataset.rank = rank;
            });

            // Update move up buttons
            updateMoveUpButtons();
        }

        function resetCriteria() {
            // Reset to normal mode
            if (advancedMode) {
                toggleAdvancedMode();
            }

            const container = document.getElementById('criteriaContainer');
            const items = Array.from(container.querySelectorAll('.criteria-item'));

            // Sort by original criterion order: popularity, rating, price, distance
            const order = ['popularity', 'rating', 'price', 'distance'];
            items.sort((a, b) => {
                return order.indexOf(a.dataset.criterion) - order.indexOf(b.dataset.criterion);
            });

            // Reappend in order
            items.forEach(item => container.appendChild(item));

            // Clear location inputs
            document.getElementById('latitudeInput').value = '';
            document.getElementById('longitudeInput').value = '';

            // Trigger location check to disable distance
            checkLocationInputs();

            updateWeights();
            updateMoveUpButtons();
        }

        // Detect GPS Location
        function detectLocation(event) {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda.');
                return;
            }

            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mendeteksi...
        `;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude.toFixed(6);
                    const longitude = position.coords.longitude.toFixed(6);

                    document.getElementById('latitudeInput').value = latitude;
                    document.getElementById('longitudeInput').value = longitude;

                    // Trigger location check to show distance criteria
                    checkLocationInputs();

                    button.disabled = false;
                    button.innerHTML = originalHTML;

                    showNotification('Lokasi berhasil terdeteksi!', 'success');
                },
                function(error) {
                    button.disabled = false;
                    button.innerHTML = originalHTML;

                    let errorMessage = 'Gagal mendapatkan lokasi. ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Izin akses lokasi ditolak.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Waktu permintaan lokasi habis.';
                            break;
                    }
                    alert(errorMessage);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        // AJAX Filter functions
        function applyFilters() {
            if (isLoading) return;

            const search = document.getElementById('searchInput').value;
            const category = document.getElementById('categoryFilter').value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (category) params.append('category', category);

            // Add current weights to maintain SAW ranking
            params.append('weight_popularity', document.getElementById('weight_popularity').value);
            params.append('weight_rating', document.getElementById('weight_rating').value);
            params.append('weight_price', document.getElementById('weight_price').value);
            params.append('weight_distance', document.getElementById('weight_distance').value);

            const lat = document.getElementById('latitudeInput').value.trim();
            const lng = document.getElementById('longitudeInput').value.trim();
            if (lat && lng) {
                params.append('latitude', lat);
                params.append('longitude', lng);
            }

            loadTourismData('{{ route('tourism.index') }}?' + params.toString(), 'Filter berhasil diterapkan!');
        }

        function handleSAWSubmit(e) {
            e.preventDefault();

            if (isLoading) return;

            const formData = new FormData(e.target);
            const params = new URLSearchParams(formData);

            // Add current search and category filters
            const search = document.getElementById('searchInput').value;
            const category = document.getElementById('categoryFilter').value;
            if (search) params.set('search', search);
            if (category) params.set('category', category);

            loadTourismData('{{ route('tourism.index') }}?' + params.toString(), 'Prioritas berhasil diterapkan!');
        }

        function loadTourismData(url, successMessage = 'Data berhasil diperbarui!') {
            if (isLoading) return;

            isLoading = true;
            const tourismCardsContainer = document.getElementById('tourismCardsContainer');
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Show loading overlay
            loadingOverlay.style.display = 'flex';

            // Fetch new data using AJAX
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(data) {
                    // Update SAW calculation data for modal
                    sawCalculationData = {
                        weights: data.weights || {},
                        calculations: data.calculations || []
                    };

                    // Update tourism cards HTML
                    if (data.html && tourismCardsContainer) {
                        // Fade out old content
                        $(tourismCardsContainer).css({
                            'opacity': '0',
                            'transition': 'opacity 0.3s ease'
                        });

                        setTimeout(function() {
                            tourismCardsContainer.innerHTML = data.html;

                            // Reinitialize trip cart buttons
                            $('.add-to-trip-cart').off('click').on('click', handleAddToTripCart);

                            // Reinitialize pagination
                            currentPage = 1;
                            initializePagination();

                            // Update total count
                            const totalCount = document.getElementById('totalCount');
                            if (totalCount && data.total) {
                                totalCount.textContent = data.total;
                            }

                            // Fade in new content
                            $(tourismCardsContainer).css('opacity', '1');
                        }, 150);
                    }

                    // Show success notification
                    showNotification(successMessage, 'success');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    showNotification('Gagal memuat data. Silakan coba lagi.', 'error');
                },
                complete: function() {
                    isLoading = false;
                    loadingOverlay.style.display = 'none';
                    $('#resultsSection').removeClass('hidden');
                }
            });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('categoryFilter').value = '';
            applyFilters();
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // ==================== PAGINATION FUNCTIONS ====================

        function initializePagination() {
            const cards = document.querySelectorAll('.tourism-card');
            totalItems = cards.length;
            currentPage = 1;

            if (totalItems > 0) {
                updatePagination();
            }
        }

        function updatePagination() {
            const cards = document.querySelectorAll('.tourism-card');
            totalItems = cards.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            // Update counts
            document.getElementById('totalCount').textContent = totalItems;
            document.getElementById('pageTotal').textContent = totalItems;

            // Show/hide cards based on current page
            cards.forEach((card, index) => {
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                if (index >= start && index < end) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Update showing count
            const start = (currentPage - 1) * itemsPerPage + 1;
            const end = Math.min(currentPage * itemsPerPage, totalItems);
            document.getElementById('showingCount').textContent = end;
            document.getElementById('pageStart').textContent = start;
            document.getElementById('pageEnd').textContent = end;

            // Generate pagination buttons
            generatePaginationButtons(totalPages);

            // Show/hide pagination container
            const paginationContainer = document.getElementById('paginationContainer');
            if (totalPages <= 1) {
                paginationContainer.style.display = 'none';
            } else {
                paginationContainer.style.display = 'block';
            }

            // Scroll to top smoothly
            // document.querySelector('#tourismCardsContainer').scrollIntoView({ 
            //     behavior: 'smooth', 
            //     block: 'start' 
            // });
        }

        function generatePaginationButtons(totalPages) {
            const container = document.getElementById('paginationButtons');
            container.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous button
            const prevBtn = createPaginationButton('‹ Prev', currentPage - 1, currentPage === 1);
            container.appendChild(prevBtn);

            // Page numbers
            const maxButtons = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            let endPage = Math.min(totalPages, startPage + maxButtons - 1);

            if (endPage - startPage < maxButtons - 1) {
                startPage = Math.max(1, endPage - maxButtons + 1);
            }

            // First page + ellipsis
            if (startPage > 1) {
                container.appendChild(createPaginationButton('1', 1, false));
                if (startPage > 2) {
                    container.appendChild(createEllipsis());
                }
            }

            // Page number buttons
            for (let i = startPage; i <= endPage; i++) {
                container.appendChild(createPaginationButton(i.toString(), i, false, i === currentPage));
            }

            // Ellipsis + last page
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    container.appendChild(createEllipsis());
                }
                container.appendChild(createPaginationButton(totalPages.toString(), totalPages, false));
            }

            // Next button
            const nextBtn = createPaginationButton('Next ›', currentPage + 1, currentPage === totalPages);
            container.appendChild(nextBtn);
        }

        function createPaginationButton(text, page, disabled = false, active = false) {
            const button = document.createElement('button');
            button.textContent = text;
            button.className = 'px-4 py-2 rounded-lg font-semibold text-sm transition-all duration-200 ';

            if (disabled) {
                button.className += 'bg-gray-200 text-gray-400 cursor-not-allowed';
                button.disabled = true;
            } else if (active) {
                button.className += 'bg-blue-600 text-white shadow-lg';
            } else {
                button.className += 'bg-white text-blue-600 border border-blue-600 hover:bg-blue-50';
                button.onclick = () => goToPage(page);
            }

            return button;
        }

        function createEllipsis() {
            const span = document.createElement('span');
            span.textContent = '...';
            span.className = 'px-2 py-2 text-gray-400';
            return span;
        }

        function goToPage(page) {
            currentPage = page;
            updatePagination();
        }

        // ==================== END PAGINATION FUNCTIONS ====================

        // Trip Cart functionality
        $(document).ready(function() {
            // Use event delegation for dynamically loaded content
            // $(document).on('click', '.add-to-trip-cart', handleAddToTripCart);
            // applyFilters(); // Initial load with filters applied
        });

        function handleAddToTripCart(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const tourismId = $button.data('tourism-id');
            const tourismName = $button.data('tourism-name');
            const $buttonText = $button.find('.button-text');
            const $icon = $button.find('svg');
            const originalText = $buttonText.text();

            $button.prop('disabled', true);
            $buttonText.text('Menambahkan...');

            $.ajax({
                url: '{{ route('trip-cart.add') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    tourism_id: tourismId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Change button to "Already in Cart" state permanently
                        $button.removeClass(
                                'add-to-trip-cart from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700'
                            )
                            .addClass('from-gray-400 to-gray-500 cursor-not-allowed');

                        // Change icon to checkmark
                        $icon.html(
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        );

                        // Change text
                        $buttonText.text('Sudah di Trip');

                        showNotification('Destinasi "' + tourismName + '" berhasil ditambahkan!', 'success');
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                },
                error: function(xhr) {
                    $buttonText.text(originalText);
                    $button.prop('disabled', false);

                    let errorMessage = 'Gagal menambahkan destinasi';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification(errorMessage, 'error');
                }
            });
        }
    </script>
@endsection
