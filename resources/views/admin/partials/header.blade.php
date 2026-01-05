<header class="bg-white border-b border-gray-200  z-20">
    <div class="h-16 flex items-center justify-between px-4 lg:px-8">
        <!-- Left: Mobile Menu & Page Title -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button onclick="toggleSidebar()"
                    class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Right: Actions & Profile -->
        <div class="flex items-center space-x-3">

            <!-- Profile Dropdown -->
            <div class="relative">
                <button onclick="toggleProfileMenu()"
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
                <div id="profileDropdown"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 hidden">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ Auth::guard('admin')->user()->email }}</p>
                    </div>
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
