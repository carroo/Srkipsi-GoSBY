@extends('admin.layout')

@section('title', 'Manajemen Itinerary')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Itinerary</h1>
            <p class="text-gray-600 mt-1">Kelola semua penjadwalan perjalanan pengguna</p>
        </div>
    </div>

    <!-- Stats Cards -->
    {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Itinerary</p>
                    <p class="text-3xl font-bold mt-1">{{ $itineraries->total() }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <i class="fas fa-route text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Halaman Saat Ini</p>
                    <p class="text-3xl font-bold mt-1">{{ $itineraries->count() }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <i class="fas fa-list text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Halaman</p>
                    <p class="text-3xl font-bold mt-1">{{ $itineraries->currentPage() }}/{{ $itineraries->lastPage() }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <i class="fas fa-layer-group text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Per Halaman</p>
                    <p class="text-3xl font-bold mt-1">15</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <i class="fas fa-expand text-2xl"></i>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Itinerary</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Pengguna</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Perjalanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Destinasi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Jarak</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Dibuat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($itineraries as $index => $itinerary)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ ($itineraries->currentPage() - 1) * $itineraries->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $itinerary->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ $itinerary->user->name }}&background=667eea&color=fff"
                                         alt="{{ $itinerary->user->name }}"
                                         class="w-6 h-6 rounded-full">
                                    {{ $itinerary->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($itinerary->travel_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $itinerary->details->count() }} destinasi
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ number_format($itinerary->total_distance / 1000, 1) }} km
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $itinerary->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('itinerary.result', $itinerary->id) }}"
                                       class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                       title="Lihat Detail"
                                       target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button onclick="deleteItinerary({{ $itinerary->id }})"
                                            class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                    <p class="text-gray-600 font-medium">Belum ada itinerary</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($itineraries->hasPages())
        <div class="flex justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($itineraries->onFirstPage())
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-2"></i>Sebelumnya
                </span>
            @else
                <a href="{{ $itineraries->previousPageUrl() }}"
                   class="px-4 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-chevron-left mr-2"></i>Sebelumnya
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($itineraries->getUrlRange(1, $itineraries->lastPage()) as $page => $url)
                @if ($page == $itineraries->currentPage())
                    <span class="px-3 py-2 text-white bg-blue-600 rounded-lg font-medium">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($itineraries->hasMorePages())
                <a href="{{ $itineraries->nextPageUrl() }}"
                   class="px-4 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    Selanjutnya<i class="fas fa-chevron-right ml-2"></i>
                </a>
            @else
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Selanjutnya<i class="fas fa-chevron-right ml-2"></i>
                </span>
            @endif
        </div>
    @endif
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
                // Create form and submit for deletion
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
