<header class="bg-white border-b border-gray-200 sticky top-0 z-20">
    <div class="h-16 flex items-center justify-between px-4 lg:px-8">
        <!-- Left: Mobile Menu & Page Title -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Page Title (Hidden on mobile) -->
            <div class="hidden lg:block">
                <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            </div>
        </div>

        <!-- Right: Actions & Profile -->
        <div class="flex items-center space-x-3">
            <!-- Search Button (Optional) -->
            <button class="hidden sm:flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 hover:bg-gray-100">
                <i class="fas fa-search"></i>
            </button>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="relative flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 hover:bg-gray-100">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-semibold">3</span>
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
                     style="display: none;">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 transition">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">User baru mendaftar</p>
                                <p class="text-xs text-gray-500 mt-1">2 menit yang lalu</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 transition">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-ticket-alt text-green-600"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">Pemesanan baru masuk</p>
                                <p class="text-xs text-gray-500 mt-1">15 menit yang lalu</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 transition">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-star text-yellow-600"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">Review baru untuk wisata</p>
                                <p class="text-xs text-gray-500 mt-1">1 jam yang lalu</p>
                            </div>
                        </a>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-200">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat semua notifikasi</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::guard('admin')->user()->name }}&background=667eea&color=fff"
                         alt="{{ Auth::guard('admin')->user()->name }}"
                         class="w-9 h-9 rounded-full border-2 border-blue-200">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </button>

                <!-- Profile Dropdown Menu -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
                     style="display: none;">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ Auth::guard('admin')->user()->email }}</p>
                    </div>
                    <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-user w-5 text-gray-400"></i>
                        <span class="ml-3">Profil Saya</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-cog w-5 text-gray-400"></i>
                        <span class="ml-3">Pengaturan</span>
                    </a>
                    <div class="border-t border-gray-200 my-2"></div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-3">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
