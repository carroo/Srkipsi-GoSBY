# Admin Template - GoSBY

## 📁 Struktur Template Admin

Template admin telah dibuat dengan struktur berikut:

### Layout & Partials
- `resources/views/admin/layout.blade.php` - Layout utama admin
- `resources/views/admin/partials/sidebar.blade.php` - Sidebar navigasi
- `resources/views/admin/partials/header.blade.php` - Header dengan user menu
- `resources/views/admin/partials/footer.blade.php` - Footer

### Pages
- `resources/views/admin/dashboard.blade.php` - Halaman dashboard
- `resources/views/admin/tourism/index.blade.php` - Halaman CRUD wisata (contoh)

### Controllers
- `app/Http/Controllers/Admin/DashboardController.php` - Controller dashboard
- `app/Http/Controllers/Admin/TourismController.php` - Controller CRUD wisata

## 🎨 Library JavaScript

Template sudah terintegrasi dengan library berikut:

### 1. **jQuery 3.7.1**
```javascript
// Sudah tersedia secara global
$(document).ready(function() {
    // Your code here
});
```

### 2. **DataTables 1.13.7**
```javascript
// Inisialisasi DataTable
$('#myTable').DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
        url: '/admin/data-endpoint',
        type: 'GET'
    },
    columns: [
        { data: 'id' },
        { data: 'name' }
    ]
});
```

### 3. **SweetAlert2**
```javascript
// Toast notification (sudah tersedia sebagai global Toast)
Toast.fire({
    icon: 'success',
    title: 'Berhasil!'
});

// Alert dialog
Swal.fire({
    title: 'Apakah Anda yakin?',
    text: "Data yang dihapus tidak dapat dikembalikan!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#DC2626',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
}).then((result) => {
    if (result.isConfirmed) {
        // Hapus data
    }
});
```

### 4. **Alpine.js 3.x**
Digunakan untuk dropdown interaktif di header

### 5. **Font Awesome 6.5.1**
Untuk icon di seluruh template

## 🔒 Autentikasi Admin

### Login Admin
- URL: `/admin/login`
- Guard: `admin`
- Redirect setelah login: `/admin/dashboard`

### Logout Admin
- Method: `POST`
- URL: `/admin/logout`
- Redirect: `/admin/login`

### Middleware
Semua route admin dilindungi dengan middleware `auth:admin`

## 🚀 Cara Menggunakan

### 1. Membuat Halaman Baru
```blade
@extends('admin.layout')

@section('title', 'Judul Halaman')
@section('page-title', 'Judul Halaman')
@section('page-subtitle', 'Subtitle halaman')

@section('content')
    <!-- Konten halaman Anda -->
@endsection

@push('styles')
    <!-- CSS tambahan -->
@endpush

@push('scripts')
    <!-- JavaScript tambahan -->
@endpush
```

### 2. Menggunakan DataTables dengan AJAX

**Controller:**
```php
public function data()
{
    $data = Model::all();
    return response()->json(['data' => $data]);
}
```

**View:**
```javascript
$('#myTable').DataTable({
    ajax: {
        url: "{{ route('admin.model.data') }}",
        type: 'GET'
    },
    columns: [
        { data: 'id' },
        { data: 'name' }
    ]
});
```

### 3. CRUD dengan AJAX

**CREATE:**
```javascript
$.ajax({
    url: '/admin/endpoint',
    type: 'POST',
    data: formData,
    success: function(response) {
        Toast.fire({
            icon: 'success',
            title: response.message
        });
    }
});
```

**UPDATE:**
```javascript
$.ajax({
    url: `/admin/endpoint/${id}`,
    type: 'PUT',
    data: formData,
    success: function(response) {
        Toast.fire({
            icon: 'success',
            title: response.message
        });
    }
});
```

**DELETE:**
```javascript
$.ajax({
    url: `/admin/endpoint/${id}`,
    type: 'DELETE',
    success: function(response) {
        Toast.fire({
            icon: 'success',
            title: response.message
        });
    }
});
```

## 📝 Contoh Implementasi

Lihat file `resources/views/admin/tourism/index.blade.php` untuk contoh lengkap implementasi CRUD dengan DataTables dan SweetAlert2.

## 🎯 Route Admin

Semua route admin berada dalam group:
```php
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    // Routes here
});
```

## 💡 Tips

1. **CSRF Token**: Sudah dikonfigurasi otomatis untuk AJAX requests
2. **Toast Global**: Gunakan `window.Toast` untuk notifikasi cepat
3. **DataTables Responsive**: Sudah diaktifkan secara default
4. **Tailwind CSS**: Digunakan untuk styling

## 🔧 Kustomisasi

### Mengubah Warna Theme
Edit di `resources/views/admin/partials/sidebar.blade.php` untuk sidebar
Edit di `resources/views/admin/layout.blade.php` untuk global styles

### Menambah Menu Sidebar
Edit di `resources/views/admin/partials/sidebar.blade.php`

### Mengubah Logo
Edit di `resources/views/admin/partials/sidebar.blade.php` bagian header
