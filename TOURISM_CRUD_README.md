# CRUD Tourism - Dokumentasi

## 📋 Overview
CRUD Tourism telah berhasil dibuat dengan fitur lengkap untuk mengelola data destinasi wisata beserta semua relasinya.

## 🎯 Fitur yang Telah Dibuat

### 1. **Controller** (`App\Http\Controllers\Admin\TourismController`)

#### Methods:
- **index()** - Menampilkan list tourism dengan DataTables (server-side processing)
- **store()** - Membuat data tourism baru dengan semua relasinya
- **show()** - Menampilkan detail tourism
- **update()** - Mengupdate data tourism dan relasinya
- **destroy()** - Menghapus data tourism dan file terkait

#### Fitur Khusus:
- ✅ Upload multiple images
- ✅ Database transaction untuk data consistency
- ✅ Automatic file deletion ketika tourism dihapus
- ✅ Cascade handling untuk relasi many-to-many
- ✅ Validation lengkap dengan custom error messages
- ✅ Support untuk update dengan delete gambar lama

### 2. **Routes** (`routes/web.php`)
```php
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    // Tourism Management
    Route::get('/tourism', [AdminTourismController::class, 'index'])->name('tourism.index');
    Route::post('/tourism', [AdminTourismController::class, 'store'])->name('tourism.store');
    Route::get('/tourism/{id}', [AdminTourismController::class, 'show'])->name('tourism.show');
    Route::put('/tourism/{id}', [AdminTourismController::class, 'update'])->name('tourism.update');
    Route::delete('/tourism/{id}', [AdminTourismController::class, 'destroy'])->name('tourism.destroy');
});
```

### 3. **View** (`resources/views/admin/tourism/index.blade.php`)

#### Fitur UI:
- **DataTables** dengan server-side processing
- **Multi-tab form** untuk mengorganisir input data:
  - Tab 1: Informasi Dasar (nama, deskripsi, rating)
  - Tab 2: Lokasi & Kontak (alamat, koordinat, phone, email, website)
  - Tab 3: Kategori & Fasilitas (checkbox untuk multiple selection)
  - Tab 4: Harga & Jam Operasional (dynamic fields)
  - Tab 5: Upload Gambar (multiple upload dengan preview)

- **Modal Create/Edit** dengan form lengkap
- **Modal View Detail** untuk melihat detail tourism
- **AJAX handling** untuk semua operasi CRUD
- **SweetAlert2** untuk konfirmasi delete
- **Toast notification** untuk feedback

#### Fitur Dynamic Form:
- ✅ Add/remove price fields secara dynamic
- ✅ Add/remove operating hours secara dynamic
- ✅ Upload multiple images dengan preview
- ✅ Mark images for deletion saat edit
- ✅ Checkbox multiple select untuk categories dan facilities

## 🔗 Relasi yang Dihandle

### HasMany (One-to-Many):
1. **Tourism → TourismPrice** (prices)
2. **Tourism → TourismFile** (files/images)
3. **Tourism → TourismHour** (operating hours)

### BelongsToMany (Many-to-Many):
4. **Tourism ↔ Category** (via tourism_category pivot)
5. **Tourism ↔ Facility** (via tourism_facility pivot)

## 📝 Validasi

### Field Validation:
- **name**: required, max 255 karakter
- **description**: optional, text
- **location**: optional, max 255 karakter
- **latitude**: optional, numeric, between -90 to 90
- **longitude**: optional, numeric, between -180 to 180
- **phone**: optional, max 20 karakter
- **email**: optional, valid email format
- **website**: optional, valid URL format
- **rating**: optional, numeric, between 0 to 5
- **categories**: optional, array, must exist in category table
- **facilities**: optional, array, must exist in facility table
- **prices**: optional, array dengan validasi untuk type dan price
- **hours**: optional, array dengan validasi untuk day, open_time, close_time
- **images**: optional, array, each must be image (jpeg,png,jpg,gif), max 2MB

## 🎨 Teknologi yang Digunakan

### Backend:
- Laravel 11
- Eloquent ORM
- Laravel Validation
- Database Transactions
- File Storage (Storage facade)

### Frontend:
- Tailwind CSS
- jQuery
- DataTables (server-side)
- SweetAlert2
- Font Awesome Icons

### Database:
- MySQL with foreign key constraints
- Cascade deletion for related records

## 📂 Struktur Data

### Tourism Table:
```
- id
- name
- description
- location
- latitude
- longitude
- phone
- email
- website
- rating
- timestamps
```

### Related Tables:
- **tourism_price**: tourism_id, type, price
- **tourism_file**: tourism_id, file_path, file_type, original_name
- **tourism_hour**: tourism_id, day, open_time, close_time
- **tourism_category**: tourism_id, category_id (pivot)
- **tourism_facility**: tourism_id, facility_id (pivot)

## 🚀 Cara Penggunaan

### 1. Tambah Data Baru:
1. Klik tombol "Tambah Wisata"
2. Isi form di setiap tab sesuai kebutuhan
3. Tab 1: Isi nama wisata (required), deskripsi, dan rating
4. Tab 2: Isi informasi lokasi dan kontak
5. Tab 3: Pilih kategori dan fasilitas
6. Tab 4: Tambah harga tiket dan jam operasional
7. Tab 5: Upload gambar wisata
8. Klik "Simpan"

### 2. View Detail:
- Klik tombol mata (eye icon) di kolom aksi
- Modal akan menampilkan semua informasi lengkap

### 3. Edit Data:
1. Klik tombol edit (pencil icon) di kolom aksi
2. Ubah data yang diperlukan di tab yang sesuai
3. Untuk menghapus gambar lama: hover gambar dan klik tombol trash
4. Upload gambar baru jika diperlukan
5. Klik "Simpan"

### 4. Hapus Data:
1. Klik tombol hapus (trash icon) di kolom aksi
2. Konfirmasi penghapusan
3. Data dan semua file terkait akan terhapus

## ⚠️ Catatan Penting

1. **File Storage**: Pastikan symbolic link sudah dibuat
   ```bash
   php artisan storage:link
   ```

2. **Directory Permission**: Folder storage/app/public harus writable
   
3. **Database Migration**: Pastikan semua migration sudah dijalankan
   ```bash
   php artisan migrate
   ```

4. **Cascade Delete**: Ketika tourism dihapus, semua data terkait akan otomatis terhapus:
   - Tourism prices
   - Tourism files (dan file fisik dari storage)
   - Tourism hours
   - Pivot records (tourism_category, tourism_facility)

5. **CSRF Token**: Semua AJAX request sudah di-handle dengan CSRF token dari meta tag

## 🔧 Troubleshooting

### Gambar tidak muncul:
- Pastikan storage link sudah dibuat
- Cek permission folder storage
- Pastikan file path di database benar

### Upload gagal:
- Cek max upload size di php.ini
- Pastikan format file sesuai (jpeg, png, jpg, gif)
- Pastikan ukuran file tidak melebihi 2MB

### DataTables tidak load:
- Cek console browser untuk error JavaScript
- Pastikan route sudah benar
- Cek response dari AJAX request

## 📚 Dependencies

Pastikan package berikut sudah terinstall:
```json
{
    "yajra/laravel-datatables-oracle": "^10.0"
}
```

## ✅ Checklist Testing

- [ ] Create tourism dengan semua relasi
- [ ] Create tourism tanpa optional fields
- [ ] View detail tourism
- [ ] Edit tourism - update basic info
- [ ] Edit tourism - add/remove categories
- [ ] Edit tourism - add/remove facilities
- [ ] Edit tourism - update prices
- [ ] Edit tourism - update hours
- [ ] Edit tourism - add new images
- [ ] Edit tourism - delete old images
- [ ] Delete tourism dan verifikasi file terhapus
- [ ] Search dan filter di DataTables
- [ ] Pagination DataTables
- [ ] Responsive design di mobile

## 🎯 Future Improvements (Optional)

1. Image cropper untuk resize otomatis
2. Drag & drop untuk reorder images
3. Bulk upload images
4. Export data tourism ke Excel/PDF
5. Import data dari CSV
6. Image optimization otomatis
7. Map picker untuk koordinat
8. Preview peta dengan Google Maps/Leaflet
9. Status publish/draft untuk tourism
10. Featured tourism flag

---

**Created by**: AI Assistant
**Date**: December 3, 2025
**Version**: 1.0
