@if($tourisms->count() > 0)
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="tourismCardsGrid">
@foreach($tourisms as $index => $tourism)
    <div class="tourism-card bg-white rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 group animate-fade-in-up"
       style="animation-delay: {{ ($index % 9) * 0.05 }}s;"
       data-index="{{ $index }}">
        <a href="{{ route('tourism.show', $tourism->id) }}" class="block">

        <!-- Image -->
        <div class="relative h-40 bg-gray-200 overflow-hidden">
            @if($tourism->files->isNotEmpty())
                <img src="{{ filter_var($tourism->files->first()->file_path, FILTER_VALIDATE_URL) ? $tourism->files->first()->file_path : asset('storage/' . $tourism->files->first()->file_path) }}"
                     alt="{{ $tourism->name }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            @else
                <img src="https://picsum.photos/400/300?random={{ $tourism->id }}"
                     alt="{{ $tourism->name }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            @endif

            <!-- SAW Ranking Badge -->
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

            <!-- Categories Badge -->
            @if($tourism->categories->isNotEmpty())
                <div class="absolute top-14 left-2 flex flex-wrap gap-1">
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
        <div class="p-3">
            <h3 class="text-base font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition duration-200 line-clamp-1">
                {{ $tourism->name }}
            </h3>

            <div class="flex items-start text-gray-600 text-xs mb-2">
                <svg class="w-3 h-3 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="line-clamp-1">{{ $tourism->location }}</span>
            </div>

            <!-- Price -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                @if($tourism->prices->isNotEmpty())
                    @php
                        $minPrice = $tourism->prices->min('price');
                    @endphp
                    @if($minPrice == 0)
                        <span class="text-green-600 font-bold text-xs">GRATIS</span>
                    @else
                        <div>
                            <span class="text-xs text-gray-500">Mulai dari</span>
                            <p class="text-blue-600 font-bold text-sm">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                        </div>
                    @endif
                @else
                        <span class="text-green-600 font-bold text-xs">GRATIS</span>
                @endif

                <span class="text-blue-600 group-hover:text-blue-700 font-semibold text-xs">
                    Detail →
                </span>
            </div>
        </div>
    </a>

    <!-- Trip Cart Button -->
    <div class="px-3 pb-3">
        @auth
            @if($tourism->isInTripCart())
                <!-- Already in Cart -->
                <button class="w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white font-semibold py-1.5 px-3 rounded-lg text-xs transition duration-300 shadow-md flex items-center justify-center cursor-not-allowed"
                        disabled>
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Sudah di Trip</span>
                </button>
            @else
                <!-- Add to Cart -->
                <button class="add-to-trip-cart w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-semibold py-1.5 px-3 rounded-lg text-xs transition duration-300 shadow-md flex items-center justify-center"
                        data-tourism-id="{{ $tourism->id }}"
                        data-tourism-name="{{ $tourism->name }}">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="button-text">Tambah ke Trip</span>
                </button>
            @endif
        @else
            <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-semibold py-1.5 px-3 rounded-lg text-xs text-center transition duration-300 shadow-md">
                Login
            </a>
        @endauth
    </div>
    </div>
@endforeach
</div>

<!-- JS-based Pagination -->
<div class="mt-8" id="paginationContainer">
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Menampilkan <span id="pageStart">1</span> - <span id="pageEnd">12</span> dari <span id="pageTotal">{{ $tourisms->count() }}</span> hasil
        </div>
        <div class="flex gap-2" id="paginationButtons">
            <!-- Generated by JS -->
        </div>
    </div>
</div>

@else
    <!-- Empty State -->
    <div class="text-center py-16 bg-white rounded-xl shadow-md">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada wisata ditemukan</h3>
        <p class="text-gray-600 mb-4">Coba ubah filter pencarian Anda</p>
        <button onclick="resetFilters()" class="inline-block bg-blue-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-300">
            Reset Filter
        </button>
    </div>
@endif