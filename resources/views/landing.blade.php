@extends('layout')

@section('title', 'Wisata Surabaya - Rekomendasi & Penjadwalan Wisata Cerdas')

@section('content')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.8);
        }
    }
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    .animate-slide-in-left {
        animation: slideInFromLeft 0.8s ease-out;
    }
    .animate-slide-in-right {
        animation: slideInFromRight 0.8s ease-out;
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }
    .animate-pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }
    .group-hover\:animate-pulse:hover {
        animation: pulse-glow 2s ease-in-out infinite;
    }
</style>

<!-- Hero Section -->
<section class="hero-section w-full relative overflow-hidden bg-gradient-to-br from-blue-50 via-indigo-50 to-white py-4 md:py-8">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Top Left Gradient -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>

        <!-- Center Right Gradient -->
        <div class="absolute top-1/2 -right-32 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-float" style="animation-delay: 2s;"></div>

        <!-- Bottom Left Gradient -->
        <div class="absolute -bottom-40 left-1/4 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-float" style="animation-delay: 4s;"></div>

        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 opacity-5" style="background-image: linear-gradient(0deg, transparent 24%, rgba(59, 130, 246, .05) 25%, rgba(59, 130, 246, .05) 26%, transparent 27%, transparent 74%, rgba(59, 130, 246, .05) 75%, rgba(59, 130, 246, .05) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(59, 130, 246, .05) 25%, rgba(59, 130, 246, .05) 26%, transparent 27%, transparent 74%, rgba(59, 130, 246, .05) 75%, rgba(59, 130, 246, .05) 76%, transparent 77%, transparent); background-size: 50px 50px;"></div>
    </div>

    <!-- Content Container -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Left Side - Text Content -->
            <div class="animate-slide-in-left">
                <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-4 leading-tight">
                    Optimalkan Liburanmu dengan <span class="bg-linear-to-r text-8xl from-blue-600 to-indigo-600 bg-clip-text text-transparent">GoSBY</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 font-semibold mb-2 leading-relaxed">
                    Kamu cukup pilih preferensimu — sisanya aplikasi yang kerjakan. Dari rekomendasi hingga itinerary lengkap, semuanya otomatis.
                </p>

                <!-- Features List -->
                <div class="space-y-4 mb-10">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-linear-to-r from-blue-600 to-indigo-600">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Rekomendasi Pintar (SAW)</h3>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-linear-to-r from-green-600 to-teal-600">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Rute Otomatis dan Efisien (Dynamic Programming)</h3>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <a href="#cta-start" class="inline-block bg-linear-to-r from-blue-600 to-indigo-600 hover:shadow-lg hover:shadow-blue-500/50 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                        Mulai Sekarang
                    </a>
                    <a href="#how-it-works" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-900 font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105 border border-gray-200">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <!-- Right Side - Stacked Images -->
            <div class="relative h-96 animate-slide-in-right" style="animation-delay: 0.2s;">
                <!-- Image 1 - Center (largest) -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-86 h-64 rounded-2xl overflow-hidden shadow-2xl hover:shadow-3xl transition duration-300 transform hover:scale-105 z-30">
                    <img src="https://picsum.photos/400/350?random=10" alt="Travel Planning" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>

                <!-- Image 2 - Top Left -->
                <div class="absolute top-0 left-0 w-56 h-48 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 z-20 border-4 border-white">
                    <img src="https://picsum.photos/350/300?random=11" alt="Destinasi 1" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-600/40 to-transparent"></div>
                </div>

                <!-- Image 3 - Bottom Right -->
                <div class="absolute bottom-0 right-0 w-56 h-48 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 z-20 border-4 border-white">
                    <img src="https://picsum.photos/350/300?random=12" alt="Destinasi 2" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-indigo-600/40 to-transparent"></div>
                </div>

                <!-- Decorative Circle -->
                <div class="absolute -bottom-8 -right-8 w-40 h-40 bg-linear-to-br from-blue-400 to-indigo-500 rounded-full opacity-20 blur-2xl z-0"></div>
            </div>
        </div>
    </div>
</section>

<!-- About the App Section -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">
            Tentang Aplikasi Kami
        </h2>

        <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
            <div class="animate-fade-in-up">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">
                    Solusi Cerdas untuk Liburan Anda
                </h3>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Aplikasi Rekomendasi & Penjadwalan Wisata Surabaya adalah platform inovatif yang menggabungkan
                    kecerdasan buatan dengan teknologi terkini untuk memberikan pengalaman liburan yang tak terlupakan.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Kami memahami bahwa merencanakan perjalanan bisa menjadi rumit. Oleh karena itu, kami menghadirkan
                    solusi yang mengotomatisasi seluruh proses perencanaan, mulai dari memilih destinasi yang sesuai
                    dengan preferensi Anda hingga menemukan rute tercepat dan paling efisien.
                </p>
            </div>
            <div class="relative animate-slide-in-right">
                <img src="https://picsum.photos/500/400?random=1" alt="Teknologi Rekomendasi" class="rounded-lg shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-105">
                <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-linear-to-br from-blue-400 to-indigo-600 rounded-lg shadow-lg opacity-50"></div>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-600 p-8 rounded-r-lg mb-12">
            <h3 class="text-2xl font-bold mb-4 text-gray-900">
                Bagaimana Algoritma Kami Bekerja?
            </h3>

            <div class="space-y-6">
                <div>
                    <h4 class="text-lg font-semibold text-blue-600 mb-2">🎯 Metode SAW (Simple Additive Weighting)</h4>
                    <p class="text-gray-700 leading-relaxed">
                        Algoritma SAW menganalisis setiap tempat wisata berdasarkan kriteria yang Anda pilih
                        (rating, harga, jarak, kategori, dll). Sistem memberikan skor pada setiap destinasi
                        dengan mempertimbangkan bobot pentingnya setiap kriteria. Hasilnya? Rekomendasi yang
                        paling sesuai dengan preferensi unik Anda, tidak generic dan benar-benar personal.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-blue-600 mb-2">🚀 Metode Dynamic Programming (DP)</h4>
                    <p class="text-gray-700 leading-relaxed">
                        Setelah wisata terpilih, Dynamic Programming mengoptimalkan rute perjalanan dengan
                        menghitung kombinasi terbaik dari seluruh destinasi pilihan. Teknik ini memecah masalah
                        besar menjadi sub-masalah kecil dan menemukan solusi optimal. Hasilnya: itinerary dengan
                        jarak terdekat, waktu tempuh paling singkat, dan efisiensi waktu maksimal untuk mengunjungi
                        semua tempat yang Anda inginkan.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-linear-to-br from-green-50 to-teal-50 p-6 rounded-lg border border-green-200">
                <h4 class="text-xl font-bold text-green-700 mb-3">💰 Hemat Waktu & Biaya</h4>
                <p class="text-gray-700">
                    Tidak perlu lagi menghabiskan jam untuk merencanakan. Algoritma kami melakukan riset dan
                    optimasi untuk Anda dalam hitungan detik.
                </p>
            </div>
            <div class="bg-linear-to-br from-purple-50 to-pink-50 p-6 rounded-lg border border-purple-200">
                <h4 class="text-xl font-bold text-purple-700 mb-3">🎯 Rekomendasi Personal</h4>
                <p class="text-gray-700">
                    Setiap saran disesuaikan dengan budget, preferensi kategori, dan durasi wisata Anda secara
                    menyeluruh dan akurat.
                </p>
            </div>
            <div class="bg-linear-to-br from-orange-50 to-red-50 p-6 rounded-lg border border-orange-200">
                <h4 class="text-xl font-bold text-orange-700 mb-3">🗺️ Rute Optimal</h4>
                <p class="text-gray-700">
                    Nikmati perjalanan yang efisien dengan rute tercerdas yang meminimalkan perjalanan sia-sia
                    dan maksimalkan waktu menikmati wisata.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Key Features Section -->
<section class="py-16 bg-gray-50" id="features">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">
            Fitur-Fitur Unggulan
        </h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1: Smart Recommendations -->
            <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl hover:border-blue-300 transition duration-300 border border-gray-200 group cursor-pointer transform hover:scale-105 hover:-translate-y-1 animate-fade-in-up">
                <div class="bg-blue-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:shadow-lg group-hover:shadow-blue-500/50 transition duration-300">
                    <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-blue-600 transition duration-300">Rekomendasi Tempat Wisata</h3>
                <p class="text-gray-700 leading-relaxed">
                    Sistem cerdas kami menganalisis ribuan data tempat wisata Surabaya dan memberikan rekomendasi
                    yang paling relevan berdasarkan preferensi rating, budget, kategori, dan lokasi Anda.
                </p>
            </div>

            <!-- Feature 2: Automatic Scheduling -->
            <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl hover:border-green-300 transition duration-300 border border-gray-200 group cursor-pointer transform hover:scale-105 hover:-translate-y-1 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-green-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-500 group-hover:shadow-lg group-hover:shadow-green-500/50 transition duration-300">
                    <svg class="w-8 h-8 text-green-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-green-600 transition duration-300">Penjadwalan Otomatis</h3>
                <p class="text-gray-700 leading-relaxed">
                    Masukkan durasi kunjungan Anda dan sistem secara otomatis membuat jadwal yang terstruktur,
                    mempertimbangkan jam operasional, waktu tempuh, dan waktu istirahat yang ideal.
                </p>
            </div>

            <!-- Feature 3: Distance & Duration -->
            <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl hover:border-purple-300 transition duration-300 border border-gray-200 group cursor-pointer transform hover:scale-105 hover:-translate-y-1 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="bg-purple-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:shadow-lg group-hover:shadow-purple-500/50 transition duration-300">
                    <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-purple-600 transition duration-300">Perhitungan Jarak & Durasi</h3>
                <p class="text-gray-700 leading-relaxed">
                    Dapatkan informasi akurat tentang jarak antar destinasi, estimasi waktu tempuh, dan total
                    durasi perjalanan. Data real-time memastikan perhitungan selalu sesuai kondisi jalan terkini.
                </p>
            </div>

            <!-- Feature 4: Category Filtering -->
            <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl hover:border-orange-300 transition duration-300 border border-gray-200 group cursor-pointer transform hover:scale-105 hover:-translate-y-1 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-orange-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4 group-hover:bg-orange-500 group-hover:shadow-lg group-hover:shadow-orange-500/50 transition duration-300">
                    <svg class="w-8 h-8 text-orange-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-orange-600 transition duration-300">Filter Kategori Wisata</h3>
                <p class="text-gray-700 leading-relaxed">
                    Cari destinasi sesuai minat Anda: taman hiburan, museum, pantai, kuliner, belanja, sejarah,
                    alam, dan masih banyak lagi. Filter canggih membantu Anda menemukan destinasi yang benar-benar sesuai.
                </p>
            </div>

            <!-- Feature 5: Interactive Map -->
            <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl hover:border-red-300 transition duration-300 border border-gray-200 group cursor-pointer transform hover:scale-105 hover:-translate-y-1 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="bg-red-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4 group-hover:bg-red-500 group-hover:shadow-lg group-hover:shadow-red-500/50 transition duration-300">
                    <svg class="w-8 h-8 text-red-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 16.382V5.618a1 1 0 00-1.553-.894L15 7m0 13V7m0 0L9 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-red-600 transition duration-300">Peta Interaktif</h3>
                <p class="text-gray-700 leading-relaxed">
                    Visualisasi rute perjalanan Anda pada peta interaktif. Lihat lokasi setiap destinasi,
                    rute yang direkomendasikan, dan semua detail penting dalam satu tampilan yang mudah dipahami.
                </p>
            </div>

            <!-- Feature 6: Export Itinerary -->
            <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl hover:border-indigo-300 transition duration-300 border border-gray-200 group cursor-pointer transform hover:scale-105 hover:-translate-y-1 animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="bg-indigo-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4 group-hover:bg-indigo-500 group-hover:shadow-lg group-hover:shadow-indigo-500/50 transition duration-300">
                    <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-indigo-600 transition duration-300">Ekspor Itinerary</h3>
                <p class="text-gray-700 leading-relaxed">
                    Dapatkan itinerary lengkap dalam format PDF atau gambar yang dapat dibagikan dengan teman dan keluarga.
                    Sertakan peta rute, jadwal, alamat, dan kontak untuk referensi offline.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 bg-linear-to-b from-white to-blue-50">
    <div class="max-w-6xl mx-auto px-4" id="how-it-works">
        <div class="text-center mb-16 animate-fade-in-up">
            <div class="inline-block mb-4">
                <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-bold">PROSES MUDAH</span>
            </div>
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                Dari Pilihan Menuju <span class="text-linear-to-r from-blue-600 to-indigo-600 bg-clip-text">Itinerary Sempurna</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Hanya 6 langkah sederhana untuk menciptakan rencana wisata impian Anda dengan teknologi AI terdepan
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 relative">
            <!-- Step 1 -->
            <div class="group relative bg-linear-to-br from-blue-50 to-white p-8 rounded-2xl border border-blue-200 hover:border-blue-400 hover:shadow-2xl transition duration-300 animate-fade-in-up cursor-pointer">
                <!-- Top number badge -->
                <div class="absolute -top-4 -right-4 bg-linear-to-r from-blue-600 to-indigo-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg group-hover:scale-110 transition duration-300">
                    1
                </div>

                <div class="mb-6">
                    <div class="bg-linear-to-r from-blue-500 to-blue-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-blue-500/50 group-hover:shadow-2xl transition duration-300 transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-3">Masukkan Preferensi</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Berikan informasi tentang budget, durasi kunjungan, kategori wisata yang Anda sukai, lokasi awal, dan kriteria khusus lainnya.
                </p>
                <div class="flex items-center text-blue-600 font-semibold group-hover:translate-x-2 transition duration-300">
                    <span>Mulai di sini</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                <!-- Animated border -->
                <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none"
                     style="background: linear-gradient(45deg, transparent 30%, rgba(59,130,246,0.1) 50%, transparent 70%); background-size: 200% 200%;"></div>
            </div>

            <!-- Step 2 -->
            <div class="group relative bg-linear-to-br from-green-50 to-white p-8 rounded-2xl border border-green-200 hover:border-green-400 hover:shadow-2xl transition duration-300 animate-fade-in-up cursor-pointer" style="animation-delay: 0.1s;">
                <div class="absolute -top-4 -right-4 bg-linear-to-r from-green-600 to-emerald-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg group-hover:scale-110 transition duration-300">
                    2
                </div>

                <div class="mb-6">
                    <div class="bg-linear-to-r from-green-500 to-emerald-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-green-500/50 group-hover:shadow-2xl transition duration-300 transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-3">Analisis SAW</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Algoritma SAW menganalisis ribuan tempat wisata dan memberikan penilaian berdasarkan preferensi Anda dengan presisi tinggi.
                </p>
                <div class="flex items-center text-green-600 font-semibold group-hover:translate-x-2 transition duration-300">
                    <span>Sistem Bekerja</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="group relative bg-linear-to-br from-purple-50 to-white p-8 rounded-2xl border border-purple-200 hover:border-purple-400 hover:shadow-2xl transition duration-300 animate-fade-in-up cursor-pointer" style="animation-delay: 0.2s;">
                <div class="absolute -top-4 -right-4 bg-linear-to-r from-purple-600 to-pink-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg group-hover:scale-110 transition duration-300">
                    3
                </div>

                <div class="mb-6">
                    <div class="bg-linear-to-r from-purple-500 to-pink-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-purple-500/50 group-hover:shadow-2xl transition duration-300 transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m0 0l-2-1m2 1v2.5M14 4l-2 1m0 0L10 4m2 1v2.5"></path>
                        </svg>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-3">Pilih Destinasi</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Lihat rekomendasi destinasi terbaik dan pilih wisata mana saja yang ingin Anda kunjungi sesuai keinginan Anda.
                </p>
                <div class="flex items-center text-purple-600 font-semibold group-hover:translate-x-2 transition duration-300">
                    <span>Pilih Destinasi</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="group relative bg-linear-to-br from-orange-50 to-white p-8 rounded-2xl border border-orange-200 hover:border-orange-400 hover:shadow-2xl transition duration-300 animate-fade-in-up cursor-pointer" style="animation-delay: 0.3s;">
                <div class="absolute -top-4 -right-4 bg-linear-to-r from-orange-600 to-red-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg group-hover:scale-110 transition duration-300">
                    4
                </div>

                <div class="mb-6">
                    <div class="bg-linear-to-r from-orange-500 to-red-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-orange-500/50 group-hover:shadow-2xl transition duration-300 transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7 1l-4.35-4.35m0 0a2 2 0 113.536 0L9 12m-4 0l4.35 4.35m0 0a2 2 0 113.536-3.536L15 12"></path>
                        </svg>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-3">Sesuaikan Manual</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Personalisasi itinerary sepenuhnya! Pilih titik awal dan akhir perjalanan, ubah urutan destinasi, tambah/hapus tempat, dan sesuaikan waktu kunjungan sesuai keinginan Anda.
                </p>
                <div class="flex items-center text-orange-600 font-semibold group-hover:translate-x-2 transition duration-300">
                    <span>Personalisasi</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                <!-- Animated border -->
                <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none"
                     style="background: linear-gradient(45deg, transparent 30%, rgba(234,88,12,0.1) 50%, transparent 70%); background-size: 200% 200%;"></div>
            </div>

            <!-- Step 5 (Optimasi Rute) -->
            <div class="group relative bg-linear-to-br from-indigo-50 to-white p-8 rounded-2xl border border-indigo-200 hover:border-indigo-400 hover:shadow-2xl transition duration-300 animate-fade-in-up cursor-pointer" style="animation-delay: 0.4s;">
                <div class="absolute -top-4 -right-4 bg-linear-to-r from-indigo-600 to-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg group-hover:scale-110 transition duration-300">
                    5
                </div>

                <div class="mb-6">
                    <div class="bg-linear-to-r from-indigo-500 to-blue-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-indigo-500/50 group-hover:shadow-2xl transition duration-300 transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 16.382V5.618a1 1 0 00-1.553-.894L15 7m0 13V7m0 0L9 4"></path>
                        </svg>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-3">Optimasi Rute (DP)</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Dynamic Programming menghitung rute paling efisien dengan jarak terdekat dan waktu tempuh tercepat untuk semua destinasi yang telah Anda sesuaikan.
                </p>
                <div class="flex items-center text-indigo-600 font-semibold group-hover:translate-x-2 transition duration-300">
                    <span>Rute Optimal</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Step 6 (Dapatkan Itinerary) -->
            <div class="group relative bg-linear-to-br from-pink-50 to-white p-8 rounded-2xl border border-pink-200 hover:border-pink-400 hover:shadow-2xl transition duration-300 animate-fade-in-up cursor-pointer" style="animation-delay: 0.5s;">
                <div class="absolute -top-4 -right-4 bg-linear-to-r from-pink-600 to-rose-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg group-hover:scale-110 transition duration-300">
                    6
                </div>

                <div class="mb-6">
                    <div class="bg-linear-to-r from-pink-500 to-rose-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-pink-500/50 group-hover:shadow-2xl transition duration-300 transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-3">Dapatkan Itinerary</h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Terima itinerary lengkap dengan jadwal, peta rute, dan informasi detail semua destinasi dalam format profesional yang siap digunakan.
                </p>
                <div class="flex items-center text-pink-600 font-semibold group-hover:translate-x-2 transition duration-300">
                    <span>Siap Bepergian</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Destinations Showcase -->
<section class="py-16 bg-gray-50" id="destinations">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">
            Destinasi Populer Surabaya
        </h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            Jelajahi beberapa destinasi wisata terbaik di Surabaya yang telah dipilih oleh ribuan pengunjung.
            Setiap lokasi menawarkan pengalaman unik dan tak terlupakan.
        </p>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Destination 1: Taman Hiburan Jawa Timur -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 animate-fade-in-up group">
                <div class="overflow-hidden h-48 bg-gray-200 relative">
                    <img src="https://picsum.photos/400/300?random=2" alt="Taman Hiburan Jawa Timur Park" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 m-2 rounded-full text-sm font-bold">Populer</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Taman Hiburan Jawa Timur Park</h3>
                    <p class="text-gray-600 mb-4">
                        Taman hiburan terbesar di Jawa Timur dengan wahana permainan seru untuk segala usia.
                        Nikmati permainan anak-anak, roller coaster mendebarkan, dan pertunjukan spektakuler setiap hari.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-500 font-semibold">⭐ 4.7/5</span>
                        <span class="text-gray-500 text-sm">Rp 150.000/orang</span>
                    </div>
                </div>
            </div>

            <!-- Destination 2: Monumen Jalesveva Jayamahe -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 animate-fade-in-up group" style="animation-delay: 0.1s;">
                <div class="overflow-hidden h-48 bg-gray-200 relative">
                    <img src="https://picsum.photos/400/300?random=3" alt="Monumen Jalesveva Jayamahe" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute top-0 left-0 bg-green-600 text-white px-3 py-1 m-2 rounded-full text-sm font-bold">Sejarah</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Monumen Jalesveva Jayamahe</h3>
                    <p class="text-gray-600 mb-4">
                        Monumen bersejarah yang melambangkan kejayaan armada nasional Indonesia.
                        Lokasi strategis dengan pemandangan laut yang indah dan nilai edukatif sejarah tinggi.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-500 font-semibold">⭐ 4.5/5</span>
                        <span class="text-gray-500 text-sm">Gratis</span>
                    </div>
                </div>
            </div>

            <!-- Destination 3: Pantai Kenjeran -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 animate-fade-in-up group" style="animation-delay: 0.2s;">
                <div class="overflow-hidden h-48 bg-gray-200 relative">
                    <img src="https://picsum.photos/400/300?random=4" alt="Pantai Kenjeran" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute top-0 left-0 bg-teal-600 text-white px-3 py-1 m-2 rounded-full text-sm font-bold">Pantai</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pantai Kenjeran</h3>
                    <p class="text-gray-600 mb-4">
                        Pantai pasir putih dengan suasana santai dan fasilitas lengkap untuk bersantai.
                        Tempat populer untuk menikmati sunset, makan seafood segar, dan berbagai aktivitas pantai.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-500 font-semibold">⭐ 4.6/5</span>
                        <span class="text-gray-500 text-sm">Rp 25.000/orang</span>
                    </div>
                </div>
            </div>

            <!-- Destination 4: Museum House of Sampoerna -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 animate-fade-in-up group" style="animation-delay: 0.3s;">
                <div class="overflow-hidden h-48 bg-gray-200 relative">
                    <img src="https://picsum.photos/400/300?random=5" alt="House of Sampoerna" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute top-0 left-0 bg-amber-600 text-white px-3 py-1 m-2 rounded-full text-sm font-bold">Museum</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">House of Sampoerna</h3>
                    <p class="text-gray-600 mb-4">
                        Museum dan pusat budaya di bangunan bersejarah Belanda. Pelajari sejarah rokok,
                        nikmati arsitektur klasik, dan kunjungi galeri seni lokal dengan koleksi menarik.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-500 font-semibold">⭐ 4.8/5</span>
                        <span class="text-gray-500 text-sm">Rp 50.000/orang</span>
                    </div>
                </div>
            </div>

            <!-- Destination 5: Kebun Binatang Surabaya -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 animate-fade-in-up group" style="animation-delay: 0.4s;">
                <div class="overflow-hidden h-48 bg-gray-200 relative">
                    <img src="https://picsum.photos/400/300?random=6" alt="Kebun Binatang Surabaya" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute top-0 left-0 bg-green-500 text-white px-3 py-1 m-2 rounded-full text-sm font-bold">Keluarga</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kebun Binatang Surabaya</h3>
                    <p class="text-gray-600 mb-4">
                        Kebun binatang terbesar kedua di Indonesia dengan ribuan hewan dari berbagai belahan dunia.
                        Tempat edukasi dan rekreasi keluarga dengan berbagai atraksi hewan sepanjang hari.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-500 font-semibold">⭐ 4.7/5</span>
                        <span class="text-gray-500 text-sm">Rp 80.000/orang</span>
                    </div>
                </div>
            </div>

            <!-- Destination 6: Dolly Tradisional -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition duration-300 transform hover:scale-105 hover:-translate-y-2 animate-fade-in-up group" style="animation-delay: 0.5s;">
                <div class="overflow-hidden h-48 bg-gray-200 relative">
                    <img src="https://picsum.photos/400/300?random=7" alt="Dolly Tradisional" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <div class="absolute top-0 left-0 bg-purple-600 text-white px-3 py-1 m-2 rounded-full text-sm font-bold">Budaya</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Dolly Tradisional</h3>
                    <p class="text-gray-600 mb-4">
                        Kawasan bersejarah dengan arsitektur unik dan jalanan yang penuh warna budaya lokal.
                        Tempat menarik untuk fotografi, memahami sejarah sosial, dan menikmati suasana autentik Surabaya.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-500 font-semibold">⭐ 4.4/5</span>
                        <span class="text-gray-500 text-sm">Gratis</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-linear-to-r from-blue-600 to-indigo-600 text-white relative overflow-hidden" id="cta-start">
    <!-- Background decoration -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 3s;"></div>

    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-slide-in-left">
            Siap Mengeksplorasi Surabaya?
        </h2>
        <p class="text-xl md:text-2xl mb-8 opacity-90 animate-slide-in-right" style="animation-delay: 0.2s;">
            Jangan lewatkan kesempatan untuk menciptakan pengalaman liburan yang sempurna.
            Mulai rencanakan itinerary wisata Anda hari ini dengan teknologi cerdas kami.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style="animation-delay: 0.4s;">
            <a href="/app" class="inline-block bg-white text-blue-600 font-bold py-4 px-10 rounded-lg hover:bg-gray-100 hover:shadow-xl transition duration-300 transform hover:scale-105 text-lg">
                Buat Itinerary Sekarang
            </a>
            <a href="#how-it-works" class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-bold py-4 px-10 rounded-lg transition duration-300 text-lg border-2 border-white hover:shadow-xl transform hover:scale-105">
                Pelajari Lebih Lanjut
            </a>
        </div>
        <p class="text-sm opacity-75 mt-8">
            Gratis untuk mencoba • Tidak perlu kartu kredit • Dapatkan itinerary dalam 5 menit
        </p>
    </div>
</section>

<!-- Stats Section (Optional) -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 text-center">
            <div class="p-6 rounded-lg bg-linear-to-br from-blue-50 to-indigo-50 hover:shadow-lg transition duration-300 animate-fade-in-up transform hover:-translate-y-2">
                <div class="text-4xl font-bold bg-linear-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2 animate-pulse">500+</div>
                <p class="text-gray-600 font-semibold">Destinasi Wisata</p>
            </div>
            <div class="p-6 rounded-lg bg-linear-to-br from-green-50 to-teal-50 hover:shadow-lg transition duration-300 animate-fade-in-up transform hover:-translate-y-2" style="animation-delay: 0.1s;">
                <div class="text-4xl font-bold bg-linear-to-r from-green-600 to-teal-600 bg-clip-text text-transparent mb-2 animate-pulse" style="animation-delay: 0.1s;">10K+</div>
                <p class="text-gray-600 font-semibold">Pengguna Aktif</p>
            </div>
            <div class="p-6 rounded-lg bg-linear-to-br from-purple-50 to-pink-50 hover:shadow-lg transition duration-300 animate-fade-in-up transform hover:-translate-y-2" style="animation-delay: 0.2s;">
                <div class="text-4xl font-bold bg-linear-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2 animate-pulse" style="animation-delay: 0.2s;">50K+</div>
                <p class="text-gray-600 font-semibold">Itinerary Dibuat</p>
            </div>
            <div class="p-6 rounded-lg bg-linear-to-br from-orange-50 to-red-50 hover:shadow-lg transition duration-300 animate-fade-in-up transform hover:-translate-y-2" style="animation-delay: 0.3s;">
                <div class="text-4xl font-bold bg-linear-to-r from-orange-600 to-red-600 bg-clip-text text-transparent mb-2 animate-pulse" style="animation-delay: 0.3s;">4.8★</div>
                <p class="text-gray-600 font-semibold">Rating Rata-rata</p>
            </div>
        </div>
    </div>
</section>
@endsection
