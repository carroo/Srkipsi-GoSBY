@extends('admin.layout')

@section('title', 'Matriks Jarak')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    #map {
        height: 500px;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .distance-matrix-wrapper {
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
        -webkit-overflow-scrolling: touch;
        display: block;
        width: 100%;
        min-width: 0;
    }

    .distance-matrix-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .distance-matrix-wrapper::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .distance-matrix-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .distance-matrix-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Ensure table doesn't expand beyond scroll container */
    .distance-matrix-wrapper table {
        width: auto !important;
        table-layout: auto;
        border-collapse: collapse;
    }

    /* Fix sticky column styling */
    .distance-matrix-wrapper th:first-child,
    .distance-matrix-wrapper td:first-child {
        position: sticky;
        left: 0;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Matriks Jarak</h1>
            <p class="text-gray-500 mt-1">Tampilan peta lokasi wisata dan matriks jarak antar lokasi</p>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white rounded-lg p-6 shadow-sm mb-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Peta Lokasi Wisata</h2>
        <div id="map"></div>
        <div class="flex flex-wrap gap-8 mt-4">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full" style="background-color: #667eea;"></div>
                <span class="text-sm text-slate-600">Lokasi Wisata</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full" style="background-color: #ff6b6b;"></div>
                <span class="text-sm text-slate-600">Titik Peta</span>
            </div>
        </div>
    </div>

    <!-- Distance Matrix Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden max-w-full w-full" style="min-width: 0;">
        <div class="px-6 pt-6 pb-4 border-b-2 border-slate-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-800">Matriks Jarak (meter)</h2>
                <div class="text-sm text-slate-600">
                    Total Lokasi: <strong>{{ count($tourisms) }}</strong>
                </div>
            </div>
        </div>
        <div class="distance-matrix-wrapper">
            <table class="border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-600 to-purple-600">
                        <th class="sticky left-0 z-10 bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-left font-semibold text-sm text-white border border-white border-opacity-20">ID</th>
                        @foreach($tourisms as $to)
                            <th class="px-4 py-3 text-center font-semibold text-sm text-white border border-white border-opacity-20 whitespace-nowrap" style="width: 140px; min-width: 140px;">
                                <div class="relative inline-block group">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-xs cursor-help hover:scale-110 transition-transform duration-200 hover:shadow-lg hover:shadow-indigo-400/40">
                                        {{ $to->id }}
                                    </span>
                                    <div class="absolute bottom-[125%] left-1/2 -translate-x-1/2 z-[9999] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 pointer-events-none">
                                        <div class="bg-slate-900 text-white text-xs font-medium px-3 py-2 rounded-md whitespace-normal w-44 text-center">
                                            {{ $to->name }}
                                        </div>
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></div>
                                    </div>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tourisms as $from)
                        <tr class="even:bg-slate-50 hover:bg-blue-50 transition-colors duration-200">
                            <td class="sticky left-0 z-10 px-4 py-3 border border-slate-200 font-semibold text-slate-900 whitespace-nowrap bg-white even:bg-slate-50 hover:bg-blue-50">
                                <div class="relative inline-block group">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-xs cursor-help hover:scale-110 transition-transform duration-200 hover:shadow-lg hover:shadow-indigo-400/40">
                                        {{ $from->id }}
                                    </span>
                                    <div class="absolute bottom-[125%] left-1/2 -translate-x-1/2 z-[9999] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 pointer-events-none">
                                        <div class="bg-slate-900 text-white text-xs font-medium px-3 py-2 rounded-md whitespace-normal w-44 text-center">
                                            {{ $from->name }}
                                        </div>
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></div>
                                    </div>
                                </div>
                            </td>
                            @foreach($matrix[$from->id] as $distance)
                                <td class="px-4 py-3 border border-slate-200 text-center text-sm whitespace-nowrap" style="width: 140px; min-width: 140px;">
                                    @if($distance['distance'] !== null)
                                        <div class="text-slate-600 font-medium">{{ number_format($distance['distance']) }} m</div>
                                        <div class="text-xs text-slate-400">
                                            {{ intval($distance['duration'] / 60) }} menit
                                        </div>
                                    @else
                                        <div class="text-slate-400 italic">-</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 pb-6"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        const map = L.map('map').setView([-6.2088, 106.8456], 10);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Get tourism data
        fetch('{{ route("admin.distance-matrix.data") }}')
            .then(response => response.json())
            .then(data => {
                const tourisms = data.tourisms;

                // Calculate bounds to fit all markers
                let bounds = L.latLngBounds();

                // Add markers for each tourism location
                tourisms.forEach((tourism, index) => {
                    const lat = parseFloat(tourism.latitude);
                    const lng = parseFloat(tourism.longitude);

                    if (lat && lng) {
                        const latlng = L.latLng(lat, lng);
                        bounds.extend(latlng);

                        // Create custom icon with different colors
                        const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b'];
                        const color = colors[index % colors.length];

                        const icon = L.divIcon({
                            html: `<div style="
                                background-color: ${color};
                                width: 32px;
                                height: 32px;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                font-weight: bold;
                                font-size: 14px;
                                border: 3px solid white;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                            ">${index + 1}</div>`,
                            iconSize: [32, 32],
                            className: 'custom-marker'
                        });

                        const marker = L.marker(latlng, { icon: icon }).addTo(map);
                        marker.bindPopup(`
                            <div class="marker-popup">
                                <strong>${tourism.name}</strong>
                                <div>Lat: ${lat.toFixed(6)}</div>
                                <div>Lng: ${lng.toFixed(6)}</div>
                            </div>
                        `);
                    }
                });

                // Fit map to bounds
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            })
            .catch(error => {
                console.error('Error fetching tourism data:', error);
                // Set default view if error occurs
                map.setView([-6.2088, 106.8456], 10);
            });
    });
</script>
@endpush
