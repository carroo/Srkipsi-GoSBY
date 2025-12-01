@extends('layout')

@section('title', 'Hasil Rekomendasi SAW - GoSBY')

@section('content')
<style>
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
    .animate-slide-down {
        animation: slideDown 0.4s ease-out;
    }
    .table-wrapper {
        overflow-x: auto;
    }
    table {
        border-collapse: separate;
        border-spacing: 0;
    }
    th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
</style>

<!-- Header Section -->
<section class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="animate-slide-down">
            <a href="{{ route('tourism.index') }}" class="inline-flex items-center text-blue-100 hover:text-white mb-4 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Wisata
            </a>
            <h1 class="text-4xl font-black mb-3">Hasil Rekomendasi Wisata</h1>
            <p class="text-xl text-blue-100">Perhitungan menggunakan Metode SAW (Simple Additive Weighting)</p>
        </div>
    </div>
</section>

<!-- Input Summary Section -->
<section class="bg-white border-b border-gray-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Data Input Kriteria
        </h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Rating Weight -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-yellow-500 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900">Rating</span>
                    </div>
                    <span class="text-2xl font-black text-yellow-600">{{ number_format($input['weights']['rating'] * 100, 0) }}%</span>
                </div>
            </div>

            <!-- Price Weight -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-green-500 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900">Harga</span>
                    </div>
                    <span class="text-2xl font-black text-green-600">{{ number_format($input['weights']['price'] * 100, 0) }}%</span>
                </div>
            </div>

            <!-- Facility Weight -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-purple-500 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900">Fasilitas</span>
                    </div>
                    <span class="text-2xl font-black text-purple-600">{{ number_format($input['weights']['facility'] * 100, 0) }}%</span>
                </div>
            </div>

            <!-- Distance Weight -->
            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-500 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900">Jarak</span>
                    </div>
                    <span class="text-2xl font-black text-blue-600">{{ number_format($input['weights']['distance'] * 100, 0) }}%</span>
                </div>
            </div>

            <!-- Category Weight -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-lg p-4 md:col-span-2">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="bg-indigo-500 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900">Kategori</span>
                    </div>
                    <span class="text-2xl font-black text-indigo-600">{{ number_format($input['weights']['category_total'] * 100, 0) }}%</span>
                </div>
                @if(!empty($input['selected_categories']))
                    <div class="flex flex-wrap gap-2">
                        @foreach($input['selected_categories'] as $catId => $weight)
                            @php
                                $category = $categories->firstWhere('id', $catId);
                            @endphp
                            @if($category)
                                <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $category->name }} ({{ number_format($weight * 100, 0) }}%)
                                </span>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Tidak ada kategori dipilih</p>
                @endif
            </div>
        </div>

        <!-- User Location -->
        <div class="mt-4 bg-gray-50 border border-gray-300 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-bold text-gray-900 mr-2">Lokasi Anda:</span>
                <span class="text-gray-700">
                    Lat: <span class="font-mono font-bold">{{ $input['user_coordinates']['lat'] }}</span>,
                    Lon: <span class="font-mono font-bold">{{ $input['user_coordinates']['lon'] }}</span>
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Min/Max Values Section -->
<section class="bg-gray-50 border-b border-gray-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-7 h-7 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Nilai Min & Max (Untuk Normalisasi)
        </h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-bold text-gray-600 mb-2">RATING</h3>
                <div class="space-y-1">
                    <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">{{ number_format($minMaxValues['max']['rating'], 2) }}</span></p>
                    <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">{{ number_format($minMaxValues['min']['rating'], 2) }}</span></p>
                </div>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-bold text-gray-600 mb-2">HARGA</h3>
                <div class="space-y-1">
                    <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">Rp {{ number_format($minMaxValues['max']['price'], 0, ',', '.') }}</span></p>
                    <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">Rp {{ number_format($minMaxValues['min']['price'], 0, ',', '.') }}</span></p>
                </div>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-bold text-gray-600 mb-2">FASILITAS</h3>
                <div class="space-y-1">
                    <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">{{ $minMaxValues['max']['facility'] }} item</span></p>
                    <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">{{ $minMaxValues['min']['facility'] }} item</span></p>
                </div>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-bold text-gray-600 mb-2">JARAK</h3>
                <div class="space-y-1">
                    <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">{{ number_format($minMaxValues['max']['distance'], 2) }} km</span></p>
                    <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">{{ number_format($minMaxValues['min']['distance'], 2) }} km</span></p>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        @if(!empty($input['selected_categories']))
            <div class="bg-white border-2 border-indigo-200 rounded-lg p-4">
                <h3 class="text-sm font-bold text-indigo-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    KATEGORI (Binary: 0 atau 1)
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($input['selected_categories'] as $catId => $weight)
                        @php
                            $category = $categories->firstWhere('id', $catId);
                        @endphp
                        <div class="bg-indigo-50 border border-indigo-300 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-indigo-900">{{ $category ? $category->name : 'Category ' . $catId }}</span>
                                <span class="bg-indigo-600 text-white px-2 py-1 rounded text-xs font-bold">{{ number_format($weight * 100, 0) }}%</span>
                            </div>
                            <div class="mt-2 text-sm text-indigo-700">
                                <span class="font-semibold">Max: 1</span> (ada kategori) |
                                <span class="font-semibold">Min: 0</span> (tidak ada)
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-3 text-xs text-gray-600 italic">
                    <strong>Catatan:</strong> Nilai kategori sudah binary (0/1), tidak perlu normalisasi. Nilai 1 jika wisata memiliki kategori tersebut, 0 jika tidak.
                </p>
            </div>
        @endif
    </div>
</section>

<!-- Top 5 Recommendations Section -->
<section class="bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-7 h-7 mr-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            Top 5 Rekomendasi Wisata
        </h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            @foreach($topRecommendations as $index => $result)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl p-4 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-blue-600 text-white font-black text-lg px-3 py-1 rounded-full">{{ $index + 1 }}</span>
                        <span class="bg-yellow-400 text-gray-900 font-black text-sm px-2 py-1 rounded">
                            {{ number_format($result['saw_score'], 4) }}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-2 line-clamp-2">{{ $result['tourism_name'] }}</h3>
                    <a href="{{ route('tourism.show', $result['tourism_id']) }}" class="text-blue-600 hover:text-blue-700 font-semibold text-xs">
                        Lihat Detail →
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Full Calculation Table -->
<section class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Detail Perhitungan SAW - Semua Wisata
        </h2>

        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6 rounded-r-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm text-blue-800 font-semibold">Keterangan Tabel:</p>
                    <ul class="text-sm text-blue-700 mt-1 space-y-1">
                        <li>• <strong>Nilai Raw:</strong> Data asli dari database</li>
                        <li>• <strong>Nilai Ternormalisasi:</strong> Hasil normalisasi (Benefit: nilai/max, Cost: min/nilai)</li>
                        <li>• <strong>Skor SAW:</strong> Hasil penjumlahan (nilai ternormalisasi × bobot)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Table wrapper with scroll -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="table-wrapper" style="max-height: 600px; overflow-y: auto; overflow-x: auto;">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <tr>
                            <th rowspan="3" class="px-3 py-3 text-center text-xs font-black uppercase tracking-wider border-r border-blue-400 sticky left-0 bg-blue-600 z-20">Rank</th>
                            <th rowspan="3" class="px-4 py-3 text-left text-xs font-black uppercase tracking-wider border-r border-blue-400 sticky left-12 bg-blue-600 z-20" style="min-width: 180px;">Nama Wisata</th>

                            <!-- Raw Values -->
                            <th colspan="{{ 4 + count($input['selected_categories']) }}" class="px-3 py-2 text-center text-xs font-black uppercase tracking-wider border-r border-blue-400 bg-yellow-500 text-gray-900">Nilai Raw</th>

                            <!-- Normalized Values -->
                            <th colspan="{{ 4 + count($input['selected_categories']) }}" class="px-3 py-2 text-center text-xs font-black uppercase tracking-wider border-r border-blue-400 bg-green-500 text-white">Nilai Ternormalisasi</th>

                            <!-- SAW Score -->
                            <th rowspan="3" class="px-4 py-3 text-center text-xs font-black uppercase tracking-wider bg-purple-600 text-white" style="min-width: 100px;">Skor SAW</th>
                        </tr>
                        <tr>
                            <!-- Raw Values Section Headers -->
                            <th colspan="4" class="px-2 py-1 text-center text-xs font-bold bg-yellow-400 text-gray-900 border-r border-yellow-300">Kriteria Utama</th>
                            @if(count($input['selected_categories']) > 0)
                                <th colspan="{{ count($input['selected_categories']) }}" class="px-2 py-1 text-center text-xs font-bold bg-yellow-400 text-gray-900 border-r border-blue-400">Kategori</th>
                            @endif

                            <!-- Normalized Values Section Headers -->
                            <th colspan="4" class="px-2 py-1 text-center text-xs font-bold bg-green-400 text-gray-900 border-r border-green-300">Kriteria Utama</th>
                            @if(count($input['selected_categories']) > 0)
                                <th colspan="{{ count($input['selected_categories']) }}" class="px-2 py-1 text-center text-xs font-bold bg-green-400 text-gray-900 border-r border-blue-400">Kategori</th>
                            @endif
                        </tr>
                        <tr>
                            <!-- Raw Values Detail Headers -->
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 70px;">Rating</th>
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 90px;">Harga (Rp)</th>
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 70px;">Fasilitas</th>
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 80px;">Jarak (km)</th>

                            <!-- Category Headers (Raw) -->
                            @foreach($input['selected_categories'] as $catId => $weight)
                                @php
                                    $category = $categories->firstWhere('id', $catId);
                                    $shortName = $category ? (strlen($category->name) > 6 ? substr($category->name, 0, 6) . '.' : $category->name) : 'C' . $catId;
                                @endphp
                                <th class="px-2 py-2 text-center text-xs font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 60px;" title="{{ $category ? $category->name : 'Category ' . $catId }}">
                                    {{ $shortName }}
                                </th>
                            @endforeach

                            <!-- Normalized Values Detail Headers -->
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 70px;">R</th>
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 70px;">H</th>
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 70px;">F</th>
                            <th class="px-2 py-2 text-center text-xs font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 70px;">J</th>

                            <!-- Category Headers (Normalized) -->
                            @foreach($input['selected_categories'] as $catId => $weight)
                                @php
                                    $category = $categories->firstWhere('id', $catId);
                                    $shortName = $category ? (strlen($category->name) > 6 ? substr($category->name, 0, 6) . '.' : $category->name) : 'C' . $catId;
                                @endphp
                                <th class="px-2 py-2 text-center text-xs font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 60px;" title="{{ $category ? $category->name : 'Category ' . $catId }}">
                                    {{ $shortName }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($results as $index => $result)
                            <tr class="hover:bg-blue-50 {{ $index < 5 ? 'bg-blue-50 font-semibold' : '' }}">
                                <!-- Rank -->
                                <td class="px-3 py-3 text-center border-r border-gray-200 sticky left-0 {{ $index < 5 ? 'bg-blue-50' : 'bg-white' }} z-10">
                                    @if($index < 3)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full {{ $index == 0 ? 'bg-yellow-400' : ($index == 1 ? 'bg-gray-300' : 'bg-orange-400') }} text-gray-900 font-black text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    @else
                                        <span class="text-gray-700 font-bold text-sm">{{ $index + 1 }}</span>
                                    @endif
                                </td>

                                <!-- Tourism Name -->
                                <td class="px-4 py-3 border-r border-gray-200 sticky left-12 {{ $index < 5 ? 'bg-blue-50' : 'bg-white' }} z-10">
                                    <a href="{{ route('tourism.show', $result['tourism_id']) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold text-sm">
                                        {{ $result['tourism_name'] }}
                                    </a>
                                </td>

                                <!-- Raw Values -->
                                <td class="px-2 py-3 text-center text-xs bg-yellow-50 border-r border-gray-200">{{ number_format($result['raw_data']['rating'], 2) }}</td>
                                <td class="px-2 py-3 text-center text-xs bg-yellow-50 border-r border-gray-200">{{ number_format($result['raw_data']['price'], 0) }}</td>
                                <td class="px-2 py-3 text-center text-xs bg-yellow-50 border-r border-gray-200">{{ $result['raw_data']['facility'] }}</td>
                                <td class="px-2 py-3 text-center text-xs bg-yellow-50 border-r border-gray-200">{{ number_format($result['raw_data']['distance'], 2) }}</td>

                                <!-- Category Raw Values (Individual) -->
                                @foreach($input['selected_categories'] as $catId => $weight)
                                    <td class="px-2 py-3 text-center text-xs bg-yellow-50 border-r border-gray-200">
                                        @if(isset($result['raw_data']['categories'][$catId]) && $result['raw_data']['categories'][$catId] == 1)
                                            <span class="text-green-600 font-bold text-base">✓</span>
                                        @else
                                            <span class="text-red-600 font-bold">✗</span>
                                        @endif
                                    </td>
                                @endforeach

                                <!-- Normalized Values -->
                                <td class="px-2 py-3 text-center text-xs bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['rating'], 4) }}</td>
                                <td class="px-2 py-3 text-center text-xs bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['price'], 4) }}</td>
                                <td class="px-2 py-3 text-center text-xs bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['facility'], 4) }}</td>
                                <td class="px-2 py-3 text-center text-xs bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['distance'], 4) }}</td>

                                <!-- Category Normalized Values (Individual) -->
                                @foreach($input['selected_categories'] as $catId => $weight)
                                    <td class="px-2 py-3 text-center text-xs bg-green-50 border-r border-gray-200 font-mono">
                                        {{ number_format($result['normalized']['categories'][$catId] ?? 0, 4) }}
                                    </td>
                                @endforeach

                                <!-- SAW Score -->
                                <td class="px-4 py-3 text-center bg-purple-50 border-r border-gray-200">
                                    <span class="text-purple-700 font-black text-base">{{ number_format($result['saw_score'], 4) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-4 bg-white border border-gray-300 rounded-lg p-4">
            <h3 class="font-bold text-gray-900 mb-2">Legend:</h3>
            <div class="grid md:grid-cols-3 gap-2 text-sm">
                <div><span class="font-mono font-bold text-gray-700">R</span> = Rating</div>
                <div><span class="font-mono font-bold text-gray-700">H</span> = Harga</div>
                <div><span class="font-mono font-bold text-gray-700">F</span> = Fasilitas</div>
                <div><span class="font-mono font-bold text-gray-700">J</span> = Jarak</div>

                @foreach($input['selected_categories'] as $catId => $weight)
                    @php
                        $category = $categories->firstWhere('id', $catId);
                    @endphp
                    <div>
                        <span class="font-mono font-bold text-gray-700">{{ $category ? substr($category->name, 0, 3) : 'C' . $catId }}</span>
                        = {{ $category ? $category->name : 'Category ' . $catId }}
                        <span class="text-indigo-600 font-bold">({{ number_format($weight * 100, 0) }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Back to Tourism List Button -->
<section class="bg-white py-8 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <a href="{{ route('tourism.index') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition duration-300 shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Wisata
        </a>
    </div>
</section>

@endsection
