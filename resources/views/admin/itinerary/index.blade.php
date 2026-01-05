@extends('admin.layout')

@section('title', 'Manajemen Itinerary')

@section('page-title', 'Manajemen Itinerary')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Itinerary</h1>
            <p class="text-gray-600 mt-1">Kelola semua penjadwalan perjalanan pengguna</p>
        </div>
    </div>

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
            <div class="p-6">
                <table id="itinerariesTable" class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="text-left">No</th>
                            <th class="text-left">Nama Itinerary</th>
                            <th class="text-left">Pengguna</th>
                            <th class="text-left">Tanggal Perjalanan</th>
                            <th class="text-left">Destinasi</th>
                            <th class="text-left">Jarak</th>
                            <th class="text-left">Tanggal Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let table;

$(document).ready(function() {
    // Initialize DataTable with server-side processing
    table = $('#itinerariesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.itinerary.index') }}",
            type: 'GET'
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                width: '5%'
            },
            {
                data: 'name',
                name: 'name',
                width: '20%'
            },
            {
                data: 'user_name',
                name: 'user_name',
                width: '15%',
                render: function(data, type, row) {
                    return '<div class="flex items-center gap-2">' +
                        '<img src="https://ui-avatars.com/api/?name=' + data + '&background=667eea&color=fff" alt="' + data + '" class="w-6 h-6 rounded-full">' +
                        data +
                        '</div>';
                }
            },
            {
                data: 'travel_date',
                name: 'travel_date',
                width: '15%'
            },
            {
                data: 'destination_count',
                name: 'destination_count',
                orderable: false,
                searchable: false,
                width: '10%',
                render: function(data) {
                    return data + ' destinasi';
                }
            },
            {
                data: 'total_distance',
                name: 'total_distance',
                width: '10%'
            },
            {
                data: 'created_at',
                name: 'created_at',
                width: '15%'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                width: '10%'
            }
        ],
        order: [[6, 'desc']],
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat data...</div>',
            emptyTable: "Tidak ada data itinerary",
            zeroRecords: "Tidak ada data yang cocok"
        }
    });
});

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
            $.ajax({
                url: `/admin/itinerary/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON?.message || 'Gagal menghapus data'
                    });
                }
            });
        }
    });
}
</script>
@endpush
