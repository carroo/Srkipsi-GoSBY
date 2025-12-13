@extends('layout')

@section('title', 'Penjadwalan Tersimpan')

@section('styles')
    <style>
        .table-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table-row:hover {
            background-color: #f9fafb;
            transition: all 0.2s ease;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .action-button {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view {
            background-color: #2563eb;
            color: white;
        }

        .btn-view:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-state-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1.5rem;
            opacity: 0.5;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }

        .pagination-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            color: #2563eb;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-link:hover {
            background-color: #e5e7eb;
        }

        .pagination-link.active {
            background-color: #2563eb;
            color: white;
            border-color: #2563eb;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Penjadwalan Tersimpan</h1>
            <p class="text-gray-600">Kelola dan lihat semua penjadwalan perjalanan Anda</p>
        </div>

        @if ($itineraries->count() > 0)
            <!-- Table -->
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr class="table-header">
                            <th class="px-6 py-4 text-left font-semibold">Nama Penjadwalan</th>
                            <th class="px-6 py-4 text-left font-semibold">Tanggal Perjalanan</th>
                            <th class="px-6 py-4 text-left font-semibold">Waktu Mulai</th>
                            <th class="px-6 py-4 text-left font-semibold">Destinasi</th>
                            <th class="px-6 py-4 text-left font-semibold">Total Jarak</th>
                            <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itineraries as $itinerary)
                            <tr class="table-row border-b border-gray-100">
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $itinerary->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ \Carbon\Carbon::parse($itinerary->travel_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $itinerary->start_time ? \Carbon\Carbon::parse($itinerary->start_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $itinerary->details->count() }} destinasi
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ number_format($itinerary->total_distance / 1000, 1) }} km
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('itinerary.result', $itinerary->id) }}"
                                            class="action-button btn-view">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Lihat
                                        </a>
                                        <button onclick="deleteItinerary({{ $itinerary->id }})"
                                            class="action-button btn-delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($itineraries->hasPages())
                <div class="pagination-container">
                    {{-- Previous Page Link --}}
                    @if ($itineraries->onFirstPage())
                        <span class="pagination-link opacity-50 cursor-not-allowed">&laquo; Sebelumnya</span>
                    @else
                        <a href="{{ $itineraries->previousPageUrl() }}"
                            class="pagination-link">&laquo; Sebelumnya</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($itineraries->getUrlRange(1, $itineraries->lastPage()) as $page => $url)
                        @if ($page == $itineraries->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($itineraries->hasMorePages())
                        <a href="{{ $itineraries->nextPageUrl() }}" class="pagination-link">Selanjutnya &raquo;</a>
                    @else
                        <span class="pagination-link opacity-50 cursor-not-allowed">Selanjutnya &raquo;</span>
                    @endif
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-md p-8">
                <div class="empty-state">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Penjadwalan</h3>
                    <p class="text-gray-600 mb-6">Anda belum membuat penjadwalan perjalanan. Mari mulai merencanakan perjalanan Anda!</p>
                    <a href="{{ route('itinerary.create') }}"
                        class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Penjadwalan
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function deleteItinerary(id) {
            showConfirmation(
                'Hapus Penjadwalan?',
                'Penjadwalan yang dihapus tidak dapat dikembalikan. Lanjutkan?',
                'Hapus',
                'Batal'
            ).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit for deletion
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/itinerary/${id}`;

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
