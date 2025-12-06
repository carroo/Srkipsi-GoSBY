<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Skripsi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-100 top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Title (Left) -->
                <a href="/" class="flex items-center space-x-3 shrink-0 group">
                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-linear-to-r from-blue-600 to-indigo-600 rounded-lg blur opacity-75 group-hover:opacity-100 transition duration-300">
                        </div>
                        <div class="relative bg-linear-to-r from-blue-600 to-indigo-600 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold bg-linear-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            GoSBY</h1>
                        <p class="text-xs font-medium text-gray-500 hidden sm:block">Smart Travel Planning</p>
                    </div>
                </a>

                <!-- Right Side - Auth Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <!-- Rekomendasikan Button -->
                    <a href="{{ route('tourism.index') }}"
                        class="relative flex items-center space-x-2 px-3 sm:px-4 py-2 text-gray-700 font-medium hover:bg-blue-50 hover:text-blue-600 rounded-lg transition duration-300 group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                        <span class="hidden sm:inline text-sm">Rekomendasikan</span>
                    </a>

                    <!-- Jadwalkan Button -->
                    <a href="{{ route('itinerary.create') }}"
                        class="hidden sm:flex items-center space-x-2 px-4 py-2 bg-linear-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-purple-500/30 transition duration-300 transform hover:-translate-y-0.5 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Jadwalkan</span>
                    </a>

                    @auth('web')
                        <div class="relative">
                            <button id="userMenuButton"
                                class="flex items-center space-x-2 px-3 sm:px-4 py-2 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="hidden sm:inline text-sm">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="userDropdown"
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100">
                                <a href="{{ route('tourism.index') }}"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                        </path>
                                    </svg>
                                    <span>Rekomendasikan</span>
                                </a>
                                <a href="{{ route('itinerary.create') }}"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Jadwalkan</span>
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Profil</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Login Button -->
                        <a href="{{ route('login') }}"
                            class="hidden sm:inline-block px-4 py-2 text-gray-700 font-medium hover:text-blue-600 hover:bg-gray-50 rounded-lg transition duration-300">
                            Masuk
                        </a>

                        <!-- Primary CTA Button -->
                        <a href="{{ route('register') }}"
                            class="hidden sm:inline-block bg-linear-to-r from-blue-600 to-indigo-600 text-white font-semibold py-2 px-5 rounded-lg hover:shadow-lg hover:shadow-blue-500/30 transition duration-300 transform hover:-translate-y-0.5 whitespace-nowrap text-sm">
                            Daftar Sekarang
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn"
                        class="md:hidden inline-flex p-2 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2 border-t border-gray-100 mt-2">
                <!-- Rekomendasikan Button -->
                <a href="{{ route('tourism.index') }}"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg font-medium transition duration-300">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                    <span>Rekomendasikan</span>
                </a>

                <!-- Jadwalkan Button -->
                <a href="#"
                    class="flex items-center px-4 py-3 text-white bg-linear-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 rounded-lg font-semibold transition duration-300">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Jadwalkan</span>
                </a>

                @auth('web')
                    <!-- Authenticated User Menu -->
                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition duration-300">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Profil</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium transition duration-300">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Guest Menu -->
                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <a href="{{ route('login') }}"
                            class="block w-full px-4 py-2.5 text-gray-700 font-medium hover:bg-gray-100 rounded-lg transition duration-300 text-center">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="block w-full bg-linear-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold py-2.5 text-center hover:shadow-lg transition duration-300">
                            Daftar Sekarang
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking on links
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });

        // User dropdown toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
    </script>

    <!-- Main Content -->
    <main class="">
        <div class="w-full">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-white font-bold mb-4">Tentang Kami</h3>
                    <p class="text-sm text-gray-400">
                        Platform cerdas untuk merencanakan liburan wisata Surabaya dengan teknologi AI terdepan.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Navigasi</h3>
                    <ul class="text-sm text-gray-400 space-y-2">
                        <li><a href="{{ route('tourism.index') }}"
                                class="hover:text-white transition">Rekomendasikan</a></li>
                        <li><a href="#" class="hover:text-white transition">Jadwalkan</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Hubungi Kami</h3>
                    <ul class="text-sm text-gray-400 space-y-2">
                        <li><a href="mailto:info@wisatasurabaya.com"
                                class="hover:text-white transition">info@wisatasurabaya.com</a></li>
                        <li><a href="tel:+6231123456" class="hover:text-white transition">+62 31 123 456</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Media Sosial</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20v-7.21H5.5V9.25h2.79V7.07c0-2.77 1.69-4.28 4.16-4.28 1.18 0 2.2.09 2.5.13v2.9h-1.72c-1.35 0-1.61.64-1.61 1.58v2.07h3.21l-4.18 3.54V20H8.29z">
                                </path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.466.182-.8.398-1.15.748-.35.35-.566.684-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.398.8.748 1.15.35.35.684.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.684.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z">
                                </path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20v-7.21H5.5V9.25h2.79V7.07c0-2.77 1.69-4.28 4.16-4.28 1.18 0 2.2.09 2.5.13v2.9h-1.72c-1.35 0-1.61.64-1.61 1.58v2.07h3.21l-4.18 3.54V20H8.29z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2025 Wisata Surabaya. All rights reserved. | <a href="#"
                        class="hover:text-white transition">Kebijakan Privasi</a> | <a href="#"
                        class="hover:text-white transition">Syarat Layanan</a></p>
            </div>
        </div>
    </footer>
    <script>
        // Notification function using SweetAlert2 Toast
        function showNotification(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }
    </script>

    @yield('scripts')

</body>

</html>
