@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Wisata -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                <i class="fas fa-map-marked-alt text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+12%</span>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Wisata</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalTourism ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Destinasi wisata aktif</p>
        </div>
    </div>

    <!-- Total Kategori -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                <i class="fas fa-tags text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">+5%</span>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Kategori</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalCategories ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Kategori wisata</p>
        </div>
    </div>

    <!-- Total Pengguna -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+23%</span>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Pengguna</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Pengguna terdaftar</p>
        </div>
    </div>

    <!-- Total Pemesanan -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center shadow-lg">
                <i class="fas fa-ticket-alt text-white text-xl"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+18%</span>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Pemesanan</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalBookings ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Pemesanan bulan ini</p>
        </div>
    </div>
</div>

<!-- Charts & Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Statistik Pemesanan</h3>
                <select class="text-sm border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option>7 Hari Terakhir</option>
                    <option>30 Hari Terakhir</option>
                    <option>3 Bulan Terakhir</option>
                </select>
            </div>
        </div>
        <div class="p-6">
            <canvas id="bookingChart" height="80"></canvas>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-eye text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Views</p>
                        <p class="text-lg font-bold text-gray-900">1,234</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Completed</p>
                        <p class="text-lg font-bold text-gray-900">{{ $totalBookings ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pending</p>
                        <p class="text-lg font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Cancelled</p>
                        <p class="text-lg font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Wisata Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Wisata Terbaru</h3>
                <a href="{{ route('admin.tourism.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            <table id="recentTourismTable" class="w-full display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-left">Nama</th>
                        <th class="text-left">Lokasi</th>
                        <th class="text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via DataTables -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pemesanan Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Pemesanan Terbaru</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            <table id="recentBookingsTable" class="w-full display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-left">User</th>
                        <th class="text-left">Wisata</th>
                        <th class="text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    // Initialize Booking Chart
    const ctx = document.getElementById('bookingChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Pemesanan',
                    data: [12, 19, 8, 15, 22, 18, 25],
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // Initialize DataTables for recent tourism
    $('#recentTourismTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        columns: [
            { data: 'name' },
            { data: 'location' },
            { data: 'status' }
        ],
        // Sample data - replace with AJAX call in production
        data: [
            { name: 'Pantai Kuta', location: 'Bali', status: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>' },
            { name: 'Candi Borobudur', location: 'Yogyakarta', status: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>' },
            { name: 'Gunung Bromo', location: 'Jawa Timur', status: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>' }
        ]
    });

    // Initialize DataTables for recent bookings
    $('#recentBookingsTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        columns: [
            { data: 'user' },
            { data: 'tourism' },
            { data: 'status' }
        ],
        // Sample data - replace with AJAX call in production
        data: [
            { user: 'John Doe', tourism: 'Pantai Kuta', status: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>' },
            { user: 'Jane Smith', tourism: 'Candi Borobudur', status: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>' },
            { user: 'Bob Wilson', tourism: 'Gunung Bromo', status: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>' }
        ]
    });
});
</script>
@endpush
