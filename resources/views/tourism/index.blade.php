@extends('layout')

@section('title', 'Jelajahi Wisata Surabaya')

@section('content')
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
    .modal-backdrop {
        backdrop-filter: blur(4px);
    }
</style>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Error Alert -->
        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-lg animate-slide-down" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-lg">Validasi Gagal!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="text-center animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black mb-4">Jelajahi Wisata Surabaya</h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Temukan destinasi wisata terbaik di Surabaya atau dapatkan rekomendasi personal sesuai preferensi Anda
            </p>

            <!-- Main CTA Button -->
            <button onclick="openRecommendationModal()" class="inline-flex items-center bg-white text-blue-600 hover:bg-gray-100 font-bold py-4 px-8 rounded-xl shadow-2xl transform hover:scale-105 transition duration-300">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Cari Rekomendasi
            </button>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        @if(isset($sawMode) && $sawMode)
            <!-- SAW Mode Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Hasil Rekomendasi SAW</h2>
                        <p class="text-sm text-gray-600">Menampilkan {{ $tourisms->count() }} wisata berdasarkan preferensi Anda</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button onclick="openCalculationModal()" class="inline-flex items-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-2.5 px-5 rounded-lg hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition duration-300 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Lihat Perhitungan Detail
                    </button>
                    <a href="{{ route('tourism.index') }}" class="inline-flex items-center bg-gray-600 text-white font-bold py-2.5 px-5 rounded-lg hover:bg-gray-700 transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </div>
        @else
            <!-- Normal Filter Form -->
            <form method="GET" action="{{ route('tourism.index') }}" class="grid md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari wisata atau lokasi..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div>
                <select name="sort" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                </select>
            </div>

            <!-- Hidden submit button for form -->
            <button type="submit" class="hidden">Filter</button>
        </form>
        @endif
    </div>
</section>

<!-- Results Info -->
<section class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            @if(isset($sawMode) && $sawMode)
                <p class="text-gray-600">
                    Menampilkan <span class="font-bold text-gray-900">{{ $tourisms->count() }}</span>
                    rekomendasi teratas dari <span class="font-bold text-gray-900">{{ $totalResults }}</span> wisata
                </p>
            @else
                <p class="text-gray-600">
                    Menampilkan <span class="font-bold text-gray-900">{{ $tourisms->count() }}</span> dari
                    <span class="font-bold text-gray-900">{{ $tourisms->total() }}</span> wisata
                </p>
            @endif

            @if(request('search') || request('category'))
                <a href="{{ route('tourism.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                    Reset Filter
                </a>
            @endif
        </div>
    </div>
</section>

<!-- Tourism Cards Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($tourisms->count() > 0)
            @if(isset($sawMode) && $sawMode)
                <!-- SAW Results Badge -->
                <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <div>
                            <p class="font-bold text-blue-900">Hasil diurutkan berdasarkan Skor SAW (Simple Additive Weighting)</p>
                            <p class="text-sm text-blue-700">Wisata dengan skor tertinggi paling sesuai dengan preferensi Anda</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($tourisms as $index => $tourism)
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 group animate-fade-in-up"
                       style="animation-delay: {{ ($index % 12) * 0.05 }}s;">
                        <a href="{{ route('tourism.show', $tourism->id) }}" class="block">

                        <!-- Image -->
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($tourism->files->isNotEmpty())
                                <img src="{{ filter_var($tourism->files->first()->file_path, FILTER_VALIDATE_URL) ? $tourism->files->first()->file_path : asset('storage/' . $tourism->files->first()->file_path) }}"
                                     alt="{{ $tourism->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <img src="https://picsum.photos/400/300?random={{ $tourism->id }}"
                                     alt="{{ $tourism->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @endif

                            <!-- SAW Ranking Badge (if SAW mode) -->
                            @if(isset($sawMode) && $sawMode)
                                <div class="absolute top-2 left-2">
                                    @if($index < 3)
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $index == 0 ? 'bg-yellow-400' : ($index == 1 ? 'bg-gray-300' : 'bg-orange-400') }} text-gray-900 font-black text-lg shadow-lg">
                                            {{ $index + 1 }}
                                        </div>
                                    @else
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-black text-lg shadow-lg">
                                            {{ $index + 1 }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Categories Badge -->
                            @if($tourism->categories->isNotEmpty())
                                <div class="absolute {{ isset($sawMode) && $sawMode ? 'top-14' : 'top-2' }} left-2 flex flex-wrap gap-1">
                                    @foreach($tourism->categories->take(2) as $catIndex => $category)
                                        @php
                                            $colors = ['bg-blue-600', 'bg-green-600', 'bg-purple-600', 'bg-orange-600', 'bg-pink-600'];
                                            $color = $colors[$catIndex % count($colors)];
                                        @endphp
                                        <span class="{{ $color }} text-white px-2 py-1 rounded-full text-xs font-bold shadow-md">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                    @if($tourism->categories->count() > 2)
                                        <span class="bg-gray-800 bg-opacity-80 text-white px-2 py-1 rounded-full text-xs font-bold shadow-md">
                                            +{{ $tourism->categories->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Rating Badge -->
                            <div class="absolute bottom-2 right-2 bg-white bg-opacity-95 px-2.5 py-1 rounded-full shadow-md">
                                <span class="text-yellow-500 text-sm font-bold">★ {{ number_format($tourism->rating, 1) }}</span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition duration-200 line-clamp-2">
                                {{ $tourism->name }}
                            </h3>

                            <div class="flex items-start text-gray-600 text-sm mb-3">
                                <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="line-clamp-1">{{ $tourism->location }}</span>
                            </div>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $tourism->description }}
                            </p>

                            <!-- Price -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                @if($tourism->prices->isNotEmpty())
                                    @php
                                        $minPrice = $tourism->prices->min('price');
                                    @endphp
                                    @if($minPrice == 0)
                                        <span class="text-green-600 font-bold text-sm">GRATIS</span>
                                    @else
                                        <div>
                                            <span class="text-xs text-gray-500">Mulai dari</span>
                                            <p class="text-blue-600 font-bold">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif

                                <span class="text-blue-600 group-hover:text-blue-700 font-semibold text-sm">
                                    Lihat Detail →
                                </span>
                            </div>
                        </div>
                    </a>

                    <!-- Trip Cart Button -->
                    <div class="px-4 pb-4">
                        @auth
                            <button class="add-to-trip-cart w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-300 shadow-md flex items-center justify-center"
                                    data-tourism-id="{{ $tourism->id }}"
                                    data-tourism-name="{{ $tourism->name }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="button-text">Tambah ke Trip Cart</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-semibold py-2 px-4 rounded-lg text-sm text-center transition duration-300 shadow-md">
                                Login untuk Menambahkan
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination (only for normal mode) -->
            @if(!isset($sawMode) || !$sawMode)
                <div class="mt-12">
                    {{ $tourisms->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Tidak ada wisata ditemukan</h3>
                <p class="text-gray-600 mb-6">Coba ubah filter atau kata kunci pencarian Anda</p>
                <a href="{{ route('tourism.index') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-300">
                    Reset Filter
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Floating Action Button (Mobile) -->
<button onclick="openRecommendationModal()" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-4 rounded-full shadow-2xl hover:shadow-blue-500/50 transform hover:scale-110 transition duration-300 z-50 md:hidden">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
    </svg>
</button>

<!-- Recommendation Modal -->
<div id="recommendationModal" class="hidden fixed inset-0 bg-transparent bg-opacity-90 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden animate-slide-down">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Cari Rekomendasi Wisata</h2>
                    <p class="text-blue-100">Dapatkan rekomendasi wisata terbaik sesuai preferensi Anda</p>
                </div>
                <button onclick="closeRecommendationModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 200px);">
            <form id="recommendationForm" method="POST" action="{{ route('tourism.saw') }}">
                @csrf

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-blue-800 font-semibold">Petunjuk Pengisian</p>
                            <p class="text-sm text-blue-700 mt-1">Tentukan bobot kepentingan untuk setiap kriteria. Total persentase harus <strong>100%</strong></p>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Kriteria 1: Rating -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-5 rounded-xl border-2 border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-500 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-900 mb-1">Rating Wisata</label>
                                <p class="text-xs text-gray-600">Pentingnya rating tinggi</p>
                            </div>
                        </div>
                        <div class="relative">
                            <input type="number" name="weight_rating" min="0" max="100" step="1" value="20"
                                   class="w-full px-4 py-3 border-2 border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent font-bold text-lg text-center"
                                   onchange="calculateTotal()">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-yellow-600 font-bold">%</span>
                        </div>
                    </div>

                    <!-- Kriteria 2: Harga -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-5 rounded-xl border-2 border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-500 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-900 mb-1">Harga Tiket</label>
                                <p class="text-xs text-gray-600">Pentingnya harga murah</p>
                            </div>
                        </div>
                        <div class="relative">
                            <input type="number" name="weight_price" min="0" max="100" step="1" value="20"
                                   class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-bold text-lg text-center"
                                   onchange="calculateTotal()">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-green-600 font-bold">%</span>
                        </div>
                    </div>

                    <!-- Kriteria 3: Jarak -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 p-5 rounded-xl border-2 border-blue-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-500 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-900 mb-1">Jarak Lokasi</label>
                                <p class="text-xs text-gray-600">Pentingnya lokasi dekat</p>
                            </div>
                        </div>

                        <!-- Latitude Longitude Input -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs text-gray-600">Lokasi Anda</label>
                                <button type="button" onclick="detectLocation()" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Deteksi Lokasi GPS
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Latitude</label>
                                    <input type="text" id="latitudeInput" name="latitude" placeholder="-7.2575"
                                           class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Longitude</label>
                                    <input type="text" id="longitudeInput" name="longitude" placeholder="112.7521"
                                           class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Weight Percentage -->
                        <div class="relative">
                            <input type="number" name="weight_distance" min="0" max="100" step="1" value="20"
                                   class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-lg text-center"
                                   onchange="calculateTotal()">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-blue-600 font-bold">%</span>
                        </div>
                    </div>

                    <!-- Kriteria 5: Kategori (Full Width) -->
                    <div class="md:col-span-2 bg-gradient-to-br from-indigo-50 to-purple-50 p-5 rounded-xl border-2 border-indigo-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-indigo-500 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-900 mb-1">Kategori Wisata</label>
                                <p class="text-xs text-gray-600">Pilih kategori dan tentukan bobot masing-masing (%)</p>
                            </div>
                        </div>

                        <!-- Category with Individual Percentage -->
                        <div class="space-y-3">
                            @foreach($categories as $category)
                                <div class="flex items-center gap-4 p-4 bg-white border-2 border-indigo-200 rounded-lg hover:border-indigo-400 transition duration-200">
                                    <label class="flex items-center flex-1 cursor-pointer">
                                        <input type="checkbox"
                                               name="categories[]"
                                               value="{{ $category->id }}"
                                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 category-checkbox"
                                               data-category-id="{{ $category->id }}"
                                               onchange="toggleCategoryWeight({{ $category->id }})">
                                        <span class="ml-3 text-sm font-bold text-gray-900">{{ $category->name }}</span>
                                    </label>

                                    <div class="relative w-32">
                                        <input type="number"
                                               name="weight_category_{{ $category->id }}"
                                               id="weight_category_{{ $category->id }}"
                                               min="0"
                                               max="100"
                                               step="1"
                                               value="0"
                                               disabled
                                               class="w-full px-3 py-2 pr-8 border-2 border-gray-300 rounded-lg text-center font-bold text-sm disabled:bg-gray-100 disabled:text-gray-400 enabled:border-indigo-300 enabled:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent category-weight"
                                               onchange="calculateTotal()">
                                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm font-bold">%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 p-3 bg-indigo-100 border border-indigo-300 rounded-lg">
                            <p class="text-xs text-indigo-800">
                                <strong>💡 Tip:</strong> Centang kategori yang Anda minati, lalu atur bobotnya. Contoh: Pantai 30%, Gunung 20%, Museum 10%.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Percentage Display -->
                <div class="mt-6 p-4 bg-gray-50 border-2 border-gray-300 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-lg font-bold text-gray-900">Total Bobot:</span>
                        </div>
                        <div class="flex items-center">
                            <span id="totalPercentage" class="text-3xl font-black text-gray-900 mr-2">100</span>
                            <span class="text-2xl font-bold text-gray-600">%</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div id="totalBar" class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300" style="width: 100%"></div>
                        </div>
                        <p id="totalMessage" class="text-sm text-green-600 font-semibold mt-2 text-center">✓ Total bobot sudah benar (100%)</p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
            <button type="button" onclick="closeRecommendationModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-200">
                Batal
            </button>
            <button type="submit" form="recommendationForm" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200">
                Cari Rekomendasi
            </button>
        </div>
    </div>
</div>

<!-- SAW Calculation Modal -->
@if(isset($sawMode) && $sawMode && isset($sawCalculation))
<div id="calculationModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-h-[95vh] overflow-hidden animate-slide-down" style="max-width: 95vw;">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-6 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Detail Perhitungan SAW</h2>
                    <p class="text-purple-100">Simple Additive Weighting - Analisis Lengkap</p>
                </div>
                <button onclick="closeCalculationModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto" style="max-height: calc(95vh - 120px);">
            @include('tourism.saw-calculation-content')
        </div>
    </div>
</div>
@endif
@endsection
@section('scripts')
<script>
    // Auto submit form on filter change
    document.querySelectorAll('select[name="category"], select[name="sort"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Modal Functions
    function openRecommendationModal() {
        document.getElementById('recommendationModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRecommendationModal() {
        document.getElementById('recommendationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openCalculationModal() {
        document.getElementById('calculationModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCalculationModal() {
        document.getElementById('calculationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on outside click
    document.getElementById('recommendationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRecommendationModal();
        }
    });

    @if($sawMode && $sawCalculation)
    document.getElementById('calculationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCalculationModal();
        }
    });
    @endif

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRecommendationModal();
            @if($sawMode && $sawCalculation)
            closeCalculationModal();
            @endif
        }
    });

    // Toggle Category Weight Input
    function toggleCategoryWeight(categoryId) {
        const checkbox = document.querySelector(`input[data-category-id="${categoryId}"]`);
        const weightInput = document.getElementById(`weight_category_${categoryId}`);

        if (checkbox.checked) {
            weightInput.disabled = false;
            weightInput.classList.remove('disabled:bg-gray-100', 'disabled:text-gray-400');
            weightInput.classList.add('bg-white', 'text-gray-900');
        } else {
            weightInput.disabled = true;
            weightInput.value = 0;
            weightInput.classList.add('disabled:bg-gray-100', 'disabled:text-gray-400');
            weightInput.classList.remove('bg-white', 'text-gray-900');
        }

        calculateTotal();
    }

    // Calculate Total Percentage
    function calculateTotal() {
        const rating = parseInt(document.querySelector('input[name="weight_rating"]').value) || 0;
        const price = parseInt(document.querySelector('input[name="weight_price"]').value) || 0;
        const distance = parseInt(document.querySelector('input[name="weight_distance"]').value) || 0;

        // Sum all category weights
        let categoryTotal = 0;
        document.querySelectorAll('.category-weight').forEach(input => {
            if (!input.disabled) {
                categoryTotal += parseInt(input.value) || 0;
            }
        });

        const total = rating + price + distance + categoryTotal;

        // Update display
        document.getElementById('totalPercentage').textContent = total;
        document.getElementById('totalBar').style.width = Math.min(total, 100) + '%';

        // Update message and styling
        const messageEl = document.getElementById('totalMessage');
        const barEl = document.getElementById('totalBar');

        if (total === 100) {
            messageEl.textContent = '✓ Total bobot sudah benar (100%)';
            messageEl.className = 'text-sm text-green-600 font-semibold mt-2 text-center';
            barEl.className = 'h-full bg-gradient-to-r from-green-500 to-emerald-600 transition-all duration-300';
        } else if (total < 100) {
            messageEl.textContent = '⚠ Total bobot kurang dari 100% (masih kurang ' + (100 - total) + '%)';
            messageEl.className = 'text-sm text-yellow-600 font-semibold mt-2 text-center';
            barEl.className = 'h-full bg-gradient-to-r from-yellow-500 to-orange-600 transition-all duration-300';
        } else {
            messageEl.textContent = '✗ Total bobot lebih dari 100% (kelebihan ' + (total - 100) + '%)';
            messageEl.className = 'text-sm text-red-600 font-semibold mt-2 text-center';
            barEl.className = 'h-full bg-gradient-to-r from-red-500 to-rose-600 transition-all duration-300';
        }
    }

    // Form Validation on Submit
    document.getElementById('recommendationForm').addEventListener('submit', function(e) {
        const rating = parseInt(document.querySelector('input[name="weight_rating"]').value) || 0;
        const price = parseInt(document.querySelector('input[name="weight_price"]').value) || 0;
        const distance = parseInt(document.querySelector('input[name="weight_distance"]').value) || 0;

        // Sum all category weights
        let categoryTotal = 0;
        document.querySelectorAll('.category-weight').forEach(input => {
            if (!input.disabled) {
                categoryTotal += parseInt(input.value) || 0;
            }
        });

        const total = rating + price + distance + categoryTotal;

        if (total !== 100) {
            e.preventDefault();

            // Show alert with appropriate message
            let message = '';
            if (total < 100) {
                message = `Total bobot tidak valid!\n\nTotal bobot saat ini: ${total}%\nMasih kurang: ${100 - total}%\n\nTotal bobot harus tepat 100% untuk melanjutkan.`;
            } else {
                message = `Total bobot tidak valid!\n\nTotal bobot saat ini: ${total}%\nKelebihan: ${total - 100}%\n\nTotal bobot harus tepat 100% untuk melanjutkan.`;
            }

            alert(message);

            // Scroll to total display
            document.getElementById('totalPercentage').scrollIntoView({ behavior: 'smooth', block: 'center' });

            return false;
        }
    });

    // Detect GPS Location
    function detectLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda.');
            return;
        }

        // Show loading state
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
                // Success - populate the input fields
                const latitude = position.coords.latitude.toFixed(6);
                const longitude = position.coords.longitude.toFixed(6);
                
                document.getElementById('latitudeInput').value = latitude;
                document.getElementById('longitudeInput').value = longitude;

                // Reset button
                button.disabled = false;
                button.innerHTML = originalHTML;

                // Show success message
                const messageDiv = document.createElement('div');
                messageDiv.className = 'text-xs text-green-600 mt-1 flex items-center';
                messageDiv.innerHTML = `
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Lokasi berhasil terdeteksi!
                `;
                
                // Insert message and remove after 3 seconds
                button.parentElement.appendChild(messageDiv);
                setTimeout(() => messageDiv.remove(), 3000);
            },
            function(error) {
                // Error handling
                button.disabled = false;
                button.innerHTML = originalHTML;

                let errorMessage = 'Gagal mendapatkan lokasi. ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Izin akses lokasi ditolak. Mohon aktifkan izin lokasi di browser Anda.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Waktu permintaan lokasi habis.';
                        break;
                    default:
                        errorMessage += 'Terjadi kesalahan yang tidak diketahui.';
                        break;
                }
                alert(errorMessage);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();

        // Auto open modal if there's an error (from backend validation)
        @if(session('error'))
            openRecommendationModal();
        @endif
    });

    // Add to Trip Cart functionality
    $(document).ready(function() {
        $('.add-to-trip-cart').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $button = $(this);
            const tourismId = $button.data('tourism-id');
            const tourismName = $button.data('tourism-name');
            const $buttonText = $button.find('.button-text');
            const originalText = $buttonText.text();
            
            // Disable button and show loading
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
                        // Success state
                        $buttonText.text('✓ Berhasil Ditambahkan!');
                        $button.removeClass('from-green-600 to-teal-600')
                               .addClass('from-blue-600 to-blue-700');
                        
                        // Show success notification
                        showNotification('Destinasi "' + tourismName + '" berhasil ditambahkan ke trip cart!', 'success');
                        
                        // Reset button after 2 seconds
                        setTimeout(function() {
                            $buttonText.text(originalText);
                            $button.prop('disabled', false)
                                   .removeClass('from-blue-600 to-blue-700')
                                   .addClass('from-green-600 to-teal-600');
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $buttonText.text(originalText);
                    $button.prop('disabled', false);
                    
                    let errorMessage = 'Gagal menambahkan destinasi';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification(errorMessage, 'error');
                }
            });
        });
        
        // Notification function
        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            const icon = type === 'success' 
                ? '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                : '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            
            const $notification = $('<div></div>')
                .addClass('fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-semibold transform transition-all duration-300 flex items-center ' + bgColor)
                .css({
                    'transform': 'translateY(-100%)',
                    'opacity': '0'
                })
                .html(icon + message);
            
            $('body').append($notification);
            
            // Animate in
            setTimeout(function() {
                $notification.css({
                    'transform': 'translateY(0)',
                    'opacity': '1'
                });
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(function() {
                $notification.css({
                    'transform': 'translateY(-100%)',
                    'opacity': '0'
                });
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    });
</script>
@endsection