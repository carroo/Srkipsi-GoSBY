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
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Pengguna</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Pengguna terdaftar</p>
        </div>
    </div>

    <!-- Total Itinerary -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center shadow-lg">
                <i class="fas fa-route text-white text-xl"></i>
            </div>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Itinerary</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalItineraries ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Rencana perjalanan dibuat</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
</script>
@endpush
