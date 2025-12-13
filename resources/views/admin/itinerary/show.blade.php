@extends('admin.layout')

@section('title', 'Detail Itinerary - ' . $itinerary->name)

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.itinerary.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-chevron-left mr-2"></i>Kembali
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $itinerary->name }}</h1>
            <p class="text-gray-600 mt-1">Detail penjadwalan perjalanan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Umum</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nama Penjadwalan</label>
                        <p class="text-gray-900 font-medium mt-1">{{ $itinerary->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Pengguna</label>
                        <div class="flex items-center gap-2 mt-1">
                            <img src="https://ui-avatars.com/api/?name={{ $itinerary->user->name }}&background=667eea&color=fff"
                                 alt="{{ $itinerary->user->name }}"
                                 class="w-8 h-8 rounded-full">
                            <p class="text-gray-900 font-medium">{{ $itinerary->user->name }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Tanggal Perjalanan</label>
                        <p class="text-gray-900 font-medium mt-1">{{ \Carbon\Carbon::parse($itinerary->travel_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Waktu Mulai</label>
                        <p class="text-gray-900 font-medium mt-1">{{ $itinerary->start_time ? \Carbon\Carbon::parse($itinerary->start_time)->format('H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Route Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Destinasi</p>
                            <p class="text-3xl font-bold mt-1">{{ $itinerary->details->count() }}</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-map-marker-alt text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Jarak</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($itinerary->total_distance / 1000, 1) }} km</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-road text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Durasi</p>
                            <p class="text-3xl font-bold mt-1">{{ intdiv($itinerary->total_duration, 60) }}h {{ $itinerary->total_duration % 60 }}m</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-hourglass-end text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Route Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Rute</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Order</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Destinasi</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Waktu Tiba</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Durasi Berkunjung</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jarak dari Sebelumnya</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Durasi dari Sebelumnya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($itinerary->details as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ $detail->order }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                        @if ($detail->tourism)
                                            {{ $detail->tourism->name }}
                                        @else
                                            Lokasi Awal
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $detail->arrival_time ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        @if ($detail->stay_duration)
                                            {{ intdiv($detail->stay_duration, 60) }}h {{ $detail->stay_duration % 60 }}m
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ number_format($detail->distance_from_previous / 1000, 2) }} km
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ intdiv($detail->duration_from_previous, 60) }}h {{ $detail->duration_from_previous % 60 }}m
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h2>
                <div class="space-y-3">
                    <a href="#" class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Lihat di User
                    </a>
                    <button onclick="deleteItinerary({{ $itinerary->id }})"
                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Lainnya</h2>
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-gray-600 font-medium">Email Pengguna</label>
                        <p class="text-gray-900 mt-1">{{ $itinerary->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 font-medium">Dibuat Pada</label>
                        <p class="text-gray-900 mt-1">{{ $itinerary->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 font-medium">Diperbarui Pada</label>
                        <p class="text-gray-900 mt-1">{{ $itinerary->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Start Point Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Lokasi Awal</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <label class="text-gray-600 font-medium">Tipe</label>
                        <p class="text-gray-900 mt-1">
                            @if ($itinerary->start_point_id)
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Wisata</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Koordinat Custom</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-gray-600 font-medium">Nama</label>
                        <p class="text-gray-900 mt-1">
                            {{ $itinerary->startPoint->name ?? 'Lokasi Custom' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-gray-600 font-medium">Koordinat</label>
                        <p class="text-gray-900 mt-1 font-mono text-xs">
                            {{ $itinerary->start_point_lat }}, {{ $itinerary->start_point_long }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function deleteItinerary(id) {
        Swal.fire({
            title: 'Hapus Itinerary?',
            text: 'Itinerary yang dihapus tidak dapat dikembalikan. Lanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/itinerary/${id}`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection
