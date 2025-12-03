@extends('admin.layout')

@section('title', 'Kelola Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Fasilitas</h1>
            <p class="text-gray-600 mt-1">Manajemen fasilitas wisata</p>
        </div>
        <button onclick="createFacility()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>
            Tambah Fasilitas
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6">
            <table id="facilitiesTable" class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Nama Fasilitas</th>
                        <th class="text-left">Deskripsi</th>
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

<!-- Modal Create/Edit -->
<div id="facilityModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50" onclick="closeModalOnBackdrop(event)">
    <div class="relative top-20 mx-auto p-5 w-full max-w-2xl">
        <div class="relative bg-white rounded-xl shadow-2xl" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">
                    Tambah Fasilitas
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="facilityForm" class="p-6">
                <input type="hidden" id="facilityId" name="id">

                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="facilityName" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Fasilitas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="facilityName" name="name"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Contoh: WiFi" required>
                        <p id="nameError" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="facilityDescription" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="facilityDescription" name="description" rows="4"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan deskripsi fasilitas..."></textarea>
                        <p id="descriptionError" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let table;

$(document).ready(function() {
    // Initialize DataTable with server-side processing
    table = $('#facilitiesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.facilities.index') }}",
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
                width: '25%'
            },
            {
                data: 'description',
                name: 'description',
                width: '40%',
                render: function(data) {
                    if (!data) return '<span class="text-gray-400 italic">Tidak ada deskripsi</span>';
                    return data.length > 100 ? data.substr(0, 100) + '...' : data;
                }
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
                width: '15%'
            }
        ],
        order: [[3, 'desc']],
        language: {
            processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat data...</div>',
            emptyTable: "Tidak ada data fasilitas",
            zeroRecords: "Tidak ada data yang cocok"
        }
    });

    // Handle form submission
    $('#facilityForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('.text-red-600').addClass('hidden');

        const id = $('#facilityId').val();
        const url = id ? `/admin/facilities/${id}` : '{{ route("admin.facilities.store") }}';
        const method = id ? 'PUT' : 'POST';

        const formData = {
            name: $('#facilityName').val(),
            description: $('#facilityDescription').val()
        };

        // Disable submit button
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    table.ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.name) {
                        $('#nameError').text(errors.name[0]).removeClass('hidden');
                    }
                    if (errors.description) {
                        $('#descriptionError').text(errors.description[0]).removeClass('hidden');
                    }
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Simpan');
            }
        });
    });
});

// Create new facility
function createFacility() {
    $('#modalTitle').text('Tambah Fasilitas');
    $('#facilityForm')[0].reset();
    $('#facilityId').val('');
    $('.text-red-600').addClass('hidden');
    $('#facilityModal').removeClass('hidden');
}

// Edit facility
function editFacility(id) {
    $.ajax({
        url: `/admin/facilities/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#modalTitle').text('Edit Fasilitas');
                $('#facilityId').val(response.data.id);
                $('#facilityName').val(response.data.name);
                $('#facilityDescription').val(response.data.description);
                $('.text-red-600').addClass('hidden');
                $('#facilityModal').removeClass('hidden');
            }
        },
        error: function(xhr) {
            Toast.fire({
                icon: 'error',
                title: xhr.responseJSON?.message || 'Gagal memuat data'
            });
        }
    });
}

// Delete facility
function deleteFacility(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Fasilitas yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/facilities/${id}`,
                type: 'DELETE',
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

// Close modal
function closeModal() {
    $('#facilityModal').addClass('hidden');
    $('#facilityForm')[0].reset();
    $('.text-red-600').addClass('hidden');
}

// Close modal when clicking on backdrop
function closeModalOnBackdrop(event) {
    if (event.target.id === 'facilityModal') {
        closeModal();
    }
}

// Close modal with ESC key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endpush