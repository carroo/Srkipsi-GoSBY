@extends('layout')

@section('title', $tourism->name . ' - Detail Wisata Surabaya')

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
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    .animate-slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }
    .gallery-image {
        transition: transform 0.3s ease, filter 0.3s ease;
        cursor: pointer;
    }
    .gallery-image:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }
    /* Ensure consistent height for all images */
    .gallery-container img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
</style>

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('landing') }}" class="text-gray-500 hover:text-blue-600 transition duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-700 font-medium">{{ Str::limit($tourism->name, 40) }}</span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Hero Section with Image Gallery -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $fileCount = $tourism->files->count();
            $displayFiles = $tourism->files->take(5);
        @endphp

        @if($fileCount == 0)
            <!-- No images - Single placeholder -->
            <div class="mb-8 animate-fade-in-up">
                <img src="https://picsum.photos/1200/600?random={{ $tourism->id }}"
                     alt="{{ $tourism->name }}"
                     class="w-full h-96 object-cover rounded-2xl shadow-xl">
            </div>

        @elseif($fileCount == 1)
            <!-- 1 image - Full width -->
            <div class="mb-8 animate-fade-in-up">
                <img src="{{ filter_var($displayFiles->first()->file_path, FILTER_VALIDATE_URL) ? $displayFiles->first()->file_path : asset('storage/' . $displayFiles->first()->file_path) }}"
                     alt="{{ $tourism->name }}"
                     class="w-full h-[500px] object-cover rounded-2xl shadow-xl">
            </div>

        @elseif($fileCount == 2)
            <!-- 2 images - Side by side -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                @foreach($displayFiles as $index => $file)
                    <div class="animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        <img src="{{ filter_var($file->file_path, FILTER_VALIDATE_URL) ? $file->file_path : asset('storage/' . $file->file_path) }}"
                             alt="{{ $tourism->name }}"
                             class="w-full h-96 object-cover rounded-2xl shadow-xl gallery-image">
                    </div>
                @endforeach
            </div>

        @elseif($fileCount == 3)
            <!-- 3 images - 1 large + 2 stacked -->
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="animate-fade-in-up col-span-2">
                    <img src="{{ filter_var($displayFiles->first()->file_path, FILTER_VALIDATE_URL) ? $displayFiles->first()->file_path : asset('storage/' . $displayFiles->first()->file_path) }}"
                         alt="{{ $tourism->name }}"
                         class="w-full h-full min-h-[400px] object-cover rounded-2xl shadow-xl gallery-image">
                </div>
                <div class="grid grid-rows-2 gap-6">
                    @foreach($displayFiles->skip(1) as $index => $file)
                        <div class="animate-fade-in-up" style="animation-delay: {{ ($index + 1) * 0.1 }}s;">
                            <img src="{{ filter_var($file->file_path, FILTER_VALIDATE_URL) ? $file->file_path : asset('storage/' . $file->file_path) }}"
                                 alt="{{ $tourism->name }}"
                                 class="w-full h-full min-h-[190px] object-cover rounded-2xl shadow-xl gallery-image">
                        </div>
                    @endforeach
                </div>
            </div>

        @elseif($fileCount == 4)
            <!-- 4 images - Grid 2x2 -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                @foreach($displayFiles as $index => $file)
                    <div class="animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        <img src="{{ filter_var($file->file_path, FILTER_VALIDATE_URL) ? $file->file_path : asset('storage/' . $file->file_path) }}"
                             alt="{{ $tourism->name }}"
                             class="w-full h-64 object-cover rounded-2xl shadow-xl gallery-image">
                    </div>
                @endforeach
            </div>

        @else
            <!-- 5+ images - 1 large + 4 grid -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- Main Image -->
                <div class="animate-fade-in-up">
                    <img src="{{ filter_var($displayFiles->first()->file_path, FILTER_VALIDATE_URL) ? $displayFiles->first()->file_path : asset('storage/' . $displayFiles->first()->file_path) }}"
                         alt="{{ $tourism->name }}"
                         class="w-full h-full min-h-[400px] object-cover rounded-2xl shadow-xl gallery-image">
                </div>

                <!-- Image Gallery Grid 2x2 -->
                <div class="grid grid-cols-2 gap-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                    @foreach($displayFiles->skip(1)->take(4) as $index => $file)
                        <div class="overflow-hidden rounded-xl shadow-lg {{ $index >= 2 ? 'hidden md:block' : '' }}">
                            <img src="{{ filter_var($file->file_path, FILTER_VALIDATE_URL) ? $file->file_path : asset('storage/' . $file->file_path) }}"
                                 alt="{{ $tourism->name }}"
                                 class="w-full h-44 object-cover gallery-image">
                        </div>
                    @endforeach

                    @if($fileCount > 5)
                        <!-- More Images Indicator -->
                        <div class="hidden md:flex overflow-hidden rounded-xl shadow-lg relative">
                            <img src="{{ filter_var($displayFiles->last()->file_path, FILTER_VALIDATE_URL) ? $displayFiles->last()->file_path : asset('storage/' . $displayFiles->last()->file_path) }}"
                                 alt="{{ $tourism->name }}"
                                 class="w-full h-44 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">+{{ $fileCount - 5 }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Title and Rating Section -->
        <div class="mb-8 animate-slide-in-left">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">{{ $tourism->name }}</h1>

                    <!-- Categories -->
                    @if($tourism->categories->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($tourism->categories as $index => $category)
                                @php
                                    $colors = ['bg-blue-100 text-blue-700', 'bg-green-100 text-green-700', 'bg-purple-100 text-purple-700', 'bg-orange-100 text-orange-700', 'bg-pink-100 text-pink-700'];
                                    $color = $colors[$index % count($colors)];
                                @endphp
                                <span class="{{ $color }} px-4 py-2 rounded-full text-sm font-bold">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Rating and Location -->
                    <div class="flex flex-wrap items-center gap-6 text-gray-700">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($tourism->rating, 1) }}</span>
                            <span class="ml-1 text-gray-600">/5</span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600">{{ $tourism->location }}</span>
                        </div>
                    </div>
                </div>

                <!-- Price Card -->
                @if($tourism->prices->isNotEmpty())
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-4xl p-6 shadow-lg">
                        <p class="text-gray-600 text-sm mb-2">Harga Mulai Dari</p>
                        @php
                            $minPrice = $tourism->prices->min('price');
                        @endphp
                        @if($minPrice == 0)
                            <p class="text-4xl font-black text-blue-600">GRATIS</p>
                        @else
                            <p class="text-3xl font-black text-blue-600">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 mt-1">per orang</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Description Section -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 animate-fade-in-up">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Deskripsi
                    </h2>
                    <p class="text-gray-700 leading-relaxed text-lg">{{ $tourism->description }}</p>
                </div>

                <!-- Operating Hours -->
                @if($tourism->hours->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Jam Operasional
                        </h2>
                        <div class="space-y-3">
                            @foreach($tourism->hours as $hour)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                                    <span class="font-semibold text-gray-800 w-32">{{ $hour->day }}</span>
                                    @if($hour->is_open)
                                        <span class="text-gray-700 flex-1">
                                            {{ \Carbon\Carbon::parse($hour->open_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($hour->close_time)->format('H:i') }}
                                        </span>
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">Buka</span>
                                    @else
                                        <span class="text-gray-500 flex-1">-</span>
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold">Tutup</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Price Details -->
                @if($tourism->prices->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.3s;">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Rincian Harga
                        </h2>
                        <div class="space-y-3">
                            @foreach($tourism->prices as $price)
                                <div class="flex justify-between items-center py-4 px-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <span class="font-semibold text-gray-800">{{ $price->type }}</span>
                                    @if($price->price == 0)
                                        <span class="text-xl font-bold text-green-600">GRATIS</span>
                                    @else
                                        <span class="text-xl font-bold text-blue-600">Rp {{ number_format($price->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Contact & Info -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- Contact Information -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 border-2 border-blue-200 animate-fade-in-up" style="animation-delay: 0.2s;">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Kontak
                        </h3>

                        <div class="space-y-4">
                            @if($tourism->phone)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Telepon</p>
                                        <a href="tel:{{ $tourism->phone }}" class="text-gray-900 font-semibold hover:text-blue-600 transition duration-200">{{ $tourism->phone }}</a>
                                    </div>
                                </div>
                            @endif

                            @if($tourism->email)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Email</p>
                                        <a href="mailto:{{ $tourism->email }}" class="text-gray-900 font-semibold hover:text-blue-600 transition duration-200 break-all">{{ $tourism->email }}</a>
                                    </div>
                                </div>
                            @endif

                            @if($tourism->website)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Website</p>
                                        <a href="{{ $tourism->website }}" target="_blank" class="text-gray-900 font-semibold hover:text-blue-600 transition duration-200 break-all">{{ Str::limit($tourism->website, 30) }}</a>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Alamat</p>
                                    <p class="text-gray-900 font-semibold">{{ $tourism->location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Preview -->
                    @if($tourism->latitude && $tourism->longitude)
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.3s;">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 16.382V5.618a1 1 0 00-1.553-.894L15 7m0 13V7m0 0L9 4"></path>
                                </svg>
                                Lokasi
                            </h3>
                            <div class="aspect-w-16 aspect-h-9 mb-4">
                                <iframe
                                    src="https://www.google.com/maps?q={{ $tourism->latitude }},{{ $tourism->longitude }}&hl=id&z=15&output=embed"
                                    class="w-full h-64 rounded-xl border-2 border-gray-200"
                                    loading="lazy"
                                    allowfullscreen>
                                </iframe>
                            </div>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $tourism->latitude }},{{ $tourism->longitude }}"
                               target="_blank"
                               class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-300">
                                Buka di Google Maps
                            </a>
                        </div>
                    @endif

                    <!-- Action Button -->
                    <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl shadow-lg p-6 border-2 border-green-200 animate-fade-in-up" style="animation-delay: 0.4s;">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Tertarik Berkunjung?</h3>
                        <p class="text-gray-600 text-sm mb-4">Tambahkan destinasi ini ke dalam trip cart Anda!</p>
                        
                        @auth
                            @if($tourism->isInTripCart())
                                <!-- Already in Cart -->
                                <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-300 shadow-md cursor-not-allowed" disabled>
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Sudah di Trip Cart</span>
                                </button>
                            @else
                                <!-- Add to Cart -->
                                <button id="addToTripCart" data-tourism-id="{{ $tourism->id }}" class="block w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-300 shadow-md">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span id="buttonText">Tambah ke Trip Cart</span>
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-300 shadow-md">
                                Login untuk Menambahkan
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Tourism Section -->
@if($relatedTourism->isNotEmpty())
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Wisata Serupa</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($relatedTourism as $index => $related)
                    <a href="{{ route('tourism.show', $related->id) }}" class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 group block">
                        <div class="overflow-hidden h-48 bg-gray-200 relative">
                            @if($related->files->isNotEmpty())
                                <img src="{{ filter_var($related->files->first()->file_path, FILTER_VALIDATE_URL) ? $related->files->first()->file_path : asset('storage/' . $related->files->first()->file_path) }}"
                                     alt="{{ $related->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <img src="https://picsum.photos/400/300?random={{ $related->id }}"
                                     alt="{{ $related->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @endif

                            @if($related->categories->isNotEmpty())
                                <div class="absolute top-0 left-0 m-2">
                                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                        {{ $related->categories->first()->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition duration-200">{{ $related->name }}</h3>
                            <p class="text-gray-600 mb-4 text-sm">
                                {{ Str::limit($related->description, 100) }}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-500 font-semibold">⭐ {{ number_format($related->rating, 1) }}/5</span>
                                @if($related->prices->isNotEmpty())
                                    @php
                                        $minPrice = $related->prices->min('price');
                                    @endphp
                                    <span class="text-gray-500 text-sm">
                                        @if($minPrice == 0)
                                            Gratis
                                        @else
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#addToTripCart').on('click', function() {
            const tourismId = $(this).data('tourism-id');
            const $buttonText = $('#buttonText');
            const $button = $(this);
            const $icon = $button.find('svg');
            
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
                        // Change button to "Already in Cart" state permanently
                        $button.removeClass('from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700')
                               .addClass('from-gray-400 to-gray-500 cursor-not-allowed');
                        
                        // Change icon to checkmark
                        $icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>');
                        
                        // Change text permanently
                        $buttonText.text('Sudah di Trip Cart');
                        
                        // Show success message
                        showNotification('Destinasi berhasil ditambahkan ke trip cart!', 'success');
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $button.prop('disabled', false);
                    
                    let errorMessage = 'Gagal menambahkan destinasi';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification(errorMessage, 'error');
                }
            });
        });
    });
</script>
@endsection
