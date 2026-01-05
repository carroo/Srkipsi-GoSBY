<aside id="sidebar"
       class="w-64 bg-white border-r border-gray-200 min-h-screen fixed lg:relative z-40 shadow-lg lg:shadow-none transition-all duration-300 -translate-x-full lg:translate-x-0">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="flex items-center space-x-2">
            <div class="bg-white rounded-lg p-2">
                <img src="{{ asset('img/logo.png') }}" class="h-8" alt="">
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-3">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link flex items-center px-4 py-3 mb-1 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-tachometer-alt w-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>

        <!-- Divider -->
        <div class="my-4 border-t border-gray-200"></div>
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Data Master</p>

        <!-- Tourism -->
        <a href="{{ route('admin.tourism.index') }}"
           class="sidebar-link flex items-center px-4 py-3 mb-1 rounded-lg {{ request()->routeIs('admin.tourism.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-map-marked-alt w-5 {{ request()->routeIs('admin.tourism.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ml-3 font-medium">Wisata</span>
        </a>

        <!-- Categories -->
        <a href="{{ route('admin.categories.index') }}"
           class="sidebar-link flex items-center px-4 py-3 mb-1 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-tags w-5 {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ml-3 font-medium">Kategori</span>
        </a>

        <!-- Distance Matrix -->
        {{-- <a href="{{ route('admin.distance-matrix.index') }}"
           class="sidebar-link flex items-center px-4 py-3 mb-1 rounded-lg {{ request()->routeIs('admin.distance-matrix.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-table w-5 {{ request()->routeIs('admin.distance-matrix.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ml-3 font-medium">Matriks Jarak</span>
        </a> --}}

        <!-- Itinerary -->
        <a href="{{ route('admin.itinerary.index') }}"
           class="sidebar-link flex items-center px-4 py-3 mb-1 rounded-lg {{ request()->routeIs('admin.itinerary.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-route w-5 {{ request()->routeIs('admin.itinerary.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ml-3 font-medium">Itinerary</span>
        </a>

        <!-- Divider -->
        <div class="my-4 border-t border-gray-200"></div>
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Manajemen</p>

        <!-- Users -->
        <a href="{{ route('admin.users.index') }}"
           class="sidebar-link flex items-center px-4 py-3 mb-1 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-users w-5 {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ml-3 font-medium">Pengguna</span>
        </a>


    </nav>

    <!-- User Info at Bottom -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center space-x-3">
            <img src="https://ui-avatars.com/api/?name={{ Auth::guard('admin')->user()->name }}&background=667eea&color=fff"
                 alt="Admin"
                 class="w-10 h-10 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::guard('admin')->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">Administrator</p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile overlay -->
<div id="sidebarOverlay"
     onclick="toggleSidebar()"
     class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 lg:hidden hidden">
</div>
