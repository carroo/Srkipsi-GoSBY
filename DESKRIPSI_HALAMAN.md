# Deskripsi Lengkap Halaman Website Sistem Rekomendasi dan Penjadwalan Pariwisata

## HALAMAN PENGGUNA (USER)

### 1. Halaman Homepage / Landing Page
**Route:** `/` (landing)

Halaman Homepage merupakan halaman utama yang pertama kali ditampilkan saat pengguna mengakses website sistem rekomendasi dan penjadwalan pariwisata. Halaman ini berisi penjelasan singkat dan jelas mengenai tujuan utama sistem, yaitu membantu wisatawan dalam menentukan destinasi wisata terbaik serta menyusun jadwal perjalanan yang optimal berdasarkan preferensi pengguna.

Homepage tidak hanya berfungsi sebagai pengenalan sistem, tetapi juga menyediakan informasi mengenai alur penggunaan website, mulai dari penginputan data preferensi hingga hasil rekomendasi dan jadwal wisata yang dihasilkan oleh sistem. Halaman ini menampilkan 6 destinasi wisata populer berdasarkan tingkat popularitas tertinggi, lengkap dengan informasi kategori, harga, dan foto destinasi. Selain itu, terdapat statistik umum yang menampilkan total jumlah destinasi wisata dan kategori yang tersedia dalam sistem, memberikan gambaran kepada pengguna tentang kelengkapan data yang dimiliki.

---

### 2. Halaman Daftar Wisata / Tourism Index
**Route:** `/tourism` (tourism.index)

Halaman Daftar Wisata merupakan halaman yang menampilkan seluruh destinasi wisata yang tersedia dalam sistem dengan fitur pencarian dan filtering yang lengkap. Halaman ini mengimplementasikan algoritma SAW (Simple Additive Weighting) untuk memberikan rekomendasi destinasi wisata terbaik berdasarkan kriteria yang ditentukan pengguna.

Pada halaman ini, pengguna dapat melakukan filtering berdasarkan kategori wisata (misalnya: wisata alam, kuliner, edukasi, dll) dan melakukan pencarian berdasarkan nama atau lokasi destinasi. Fitur utama halaman ini adalah sistem pembobotan kriteria yang dapat disesuaikan pengguna, meliputi:
- **Rating**: Bobot untuk rating pengunjung (0-5 bintang)
- **Popularity**: Bobot untuk tingkat popularitas destinasi
- **Price**: Bobot untuk rentang harga tiket masuk
- **Distance**: Bobot untuk jarak dari lokasi pengguna (jika diaktifkan)

Sistem akan menghitung nilai SAW untuk setiap destinasi berdasarkan normalisasi kriteria dan pembobotan yang diberikan, kemudian mengurutkan destinasi dari nilai tertinggi ke terendah. Setiap destinasi ditampilkan dalam bentuk card yang informatif dengan foto, nama, rating, kategori, rentang harga, dan tombol aksi untuk melihat detail atau menambahkan ke Trip Cart. Halaman ini mendukung request AJAX untuk memberikan pengalaman filtering yang responsif tanpa reload halaman.

---

### 3. Halaman Detail Wisata
**Route:** `/tourism/{id}` (tourism.show)

Halaman Detail Wisata menampilkan informasi lengkap dan komprehensif mengenai satu destinasi wisata tertentu. Halaman ini dirancang untuk memberikan gambaran detail kepada calon pengunjung sebelum memutuskan untuk mengunjungi destinasi tersebut atau menambahkannya ke dalam Trip Cart.

Informasi yang ditampilkan meliputi:
- **Informasi Dasar**: Nama destinasi, alamat lengkap, koordinat lokasi (latitude & longitude)
- **Rating & Review**: Rating rata-rata dan ulasan dari pengunjung sebelumnya
- **Kategori**: Klasifikasi destinasi (wisata alam, kuliner, edukasi, dll)
- **Harga Tiket**: Rincian harga untuk berbagai kategori pengunjung (dewasa, anak, wisatawan asing, dll) atau keterangan gratis jika tidak berbayar
- **Jam Operasional**: Jadwal buka tutup untuk setiap hari dalam seminggu
- **Galeri Foto**: Koleksi foto-foto destinasi wisata
- **Deskripsi Lengkap**: Penjelasan detail tentang destinasi, fasilitas, dan daya tarik
- **Peta Lokasi**: Visualisasi lokasi destinasi menggunakan Google Maps
- **Tombol Aksi**: Tombol untuk menambahkan destinasi ke Trip Cart

Halaman ini sangat penting sebagai sumber informasi utama bagi pengguna sebelum membuat keputusan perjalanan.

---

### 4. Halaman Login Pengguna
**Route:** `/login` (login)

Halaman Login Pengguna adalah halaman autentikasi yang memungkinkan pengguna terdaftar untuk masuk ke dalam sistem. Halaman ini menyediakan form login dengan field email dan password, serta opsi "Remember Me" untuk menyimpan sesi login lebih lama.

Fitur keamanan yang diterapkan:
- Validasi input email dan password
- Hashing password menggunakan bcrypt
- Session regeneration setelah login berhasil untuk mencegah session fixation
- Pesan error yang jelas jika kredensial salah
- Redirect otomatis ke halaman sebelumnya (intended page) setelah login berhasil

Halaman ini juga menyediakan link ke halaman registrasi bagi pengguna baru yang belum memiliki akun. Sistem menggunakan Laravel Authentication dengan guard 'web' untuk memisahkan autentikasi pengguna dan admin.

---

### 5. Halaman Registrasi Pengguna
**Route:** `/register` (register)

Halaman Registrasi Pengguna memungkinkan calon pengguna untuk membuat akun baru dalam sistem. Form registrasi dirancang sederhana namun aman dengan validasi yang ketat untuk memastikan data yang masuk valid dan terstandarisasi.

Field yang harus diisi meliputi:
- **Nama Lengkap**: Maksimal 255 karakter
- **Email**: Harus format email valid dan unik (belum terdaftar)
- **Password**: Minimal 8 karakter dengan aturan keamanan
- **Konfirmasi Password**: Harus sama dengan password

Setelah registrasi berhasil, sistem akan:
1. Menyimpan data pengguna dengan password yang di-hash
2. Otomatis login pengguna tersebut
3. Redirect ke homepage dengan pesan sukses
4. Pengguna langsung dapat menggunakan fitur-fitur sistem seperti Trip Cart dan Itinerary

Validasi menggunakan Laravel Validation Rules untuk memastikan keamanan dan integritas data.

---

### 6. Halaman Trip Cart
**Route:** `/trip-cart` (trip-cart.index)
**Auth Required:** Ya (middleware auth:web)

Halaman Trip Cart adalah halaman yang menampilkan daftar destinasi wisata yang telah dipilih oleh pengguna untuk direncanakan perjalanannya. Konsep Trip Cart mirip dengan shopping cart pada e-commerce, namun digunakan untuk mengumpulkan destinasi wisata yang ingin dikunjungi.

Fungsi utama halaman ini:
- Menampilkan semua destinasi yang telah ditambahkan pengguna
- Menyediakan tombol untuk menghapus destinasi dari cart
- Menampilkan informasi ringkas setiap destinasi (foto, nama, rating, kategori, harga)
- Menyediakan link navigasi ke halaman pembuatan itinerary
- Otomatis menghitung dan menyimpan cache jarak antar destinasi

Fitur penting di balik layar adalah sistem Distance Cache, dimana saat pengguna menambahkan destinasi ke Trip Cart, sistem otomatis menghitung dan menyimpan jarak serta durasi perjalanan antara destinasi yang baru ditambahkan dengan semua destinasi lain yang sudah ada di cart. Perhitungan ini menggunakan Google Distance Matrix API dan disimpan dalam database untuk efisiensi, sehingga tidak perlu menghitung ulang saat membuat itinerary. Cache disimpan untuk kedua arah (A ke B dan B ke A) karena bisa berbeda tergantung kondisi jalan.

Halaman ini merupakan langkah persiapan sebelum membuat itinerary perjalanan yang optimal.

---

### 7. Halaman Buat Itinerary
**Route:** `/itinerary/create` (itinerary.create)
**Auth Required:** Ya (middleware auth:web)

Halaman Buat Itinerary adalah halaman formulir untuk mengonfigurasi pembuatan jadwal perjalanan wisata yang optimal. Halaman ini menyediakan interface untuk pengguna menentukan parameter-parameter yang akan digunakan dalam algoritma optimasi rute.

Form konfigurasi meliputi:
- **Nama Itinerary**: Nama untuk identifikasi jadwal perjalanan
- **Tanggal Perjalanan**: Tanggal rencana kunjungan
- **Waktu Mulai**: Jam mulai perjalanan (default 07:00)
- **Pilihan Titik Awal**: 
  - Dari salah satu destinasi di Trip Cart, atau
  - Custom location dengan input latitude & longitude
- **Daftar Destinasi**: Menampilkan semua destinasi dari Trip Cart dengan checkbox untuk dipilih

Halaman ini juga menampilkan preview destinasi yang akan dikunjungi lengkap dengan informasi jam operasional, harga, dan estimasi waktu kunjungan. Informasi ini penting untuk perhitungan itinerary yang realistis.

Setelah form diisi lengkap, data akan dikirim ke algoritma TSP (Travelling Salesman Problem) dengan Dynamic Programming untuk menghasilkan rute optimal yang meminimalkan total jarak tempuh.

---

### 8. Halaman Generate & Save Itinerary
**Route:** `/itinerary/generate` (POST) & `/itinerary/save` (POST)
**Auth Required:** Ya (middleware auth:web)

Halaman ini sebenarnya adalah proses backend yang memproses pembuatan itinerary, namun memiliki peran sangat krusial. Proses ini mengimplementasikan algoritma TSP (Travelling Salesman Problem) menggunakan metode Dynamic Programming untuk mencari rute optimal.

**Algoritma TSP dengan Dynamic Programming:**

Proses yang dilakukan:
1. **Build Distance Matrix**: Membuat matriks jarak dari semua pasangan destinasi menggunakan data dari Distance Cache
2. **TSP Solver**: Menggunakan Dynamic Programming (DP) dengan bitmask untuk mencari rute dengan total jarak minimum
   - State DP: dp[mask][current] = jarak minimum untuk mengunjungi subset destinasi (mask) dan berakhir di destinasi current
   - Kompleksitas: O(2^n * n^2) dimana n adalah jumlah destinasi
   - Dapat menangani hingga 15-20 destinasi dengan performa baik
3. **Build Itinerary**: Menyusun jadwal detail per destinasi dengan:
   - Urutan kunjungan optimal
   - Waktu tiba dan waktu berangkat di setiap destinasi
   - Durasi kunjungan (berdasarkan data tourism_hours atau default 2 jam)
   - Jarak dan durasi perjalanan antar destinasi
4. **Route Geometry**: Mengambil polyline route dari OSRM API untuk visualisasi peta
5. **Save to Database**: Menyimpan itinerary dan detail per destinasi

Hasil yang disimpan:
- Header itinerary (nama, tanggal, total jarak, total durasi, polyline)
- Detail per destinasi (urutan, waktu tiba/berangkat, durasi kunjungan, jarak dari destinasi sebelumnya)

Setelah proses selesai, pengguna akan di-redirect ke halaman hasil itinerary.

---

### 9. Halaman Hasil Itinerary
**Route:** `/itinerary/result/{id}` (itinerary.result)

Halaman Hasil Itinerary menampilkan jadwal perjalanan wisata yang telah dibuat oleh sistem secara visual dan informatif. Halaman ini merupakan output utama dari sistem yang menunjukkan rute optimal hasil perhitungan algoritma TSP.

Informasi yang ditampilkan:
- **Header Itinerary**: 
  - Nama itinerary
  - Tanggal perjalanan
  - Total jarak yang akan ditempuh
  - Total durasi perjalanan
- **Peta Interaktif**: 
  - Visualisasi rute perjalanan menggunakan Google Maps atau Leaflet
  - Polyline yang menunjukkan jalur perjalanan
  - Marker untuk setiap destinasi dengan nomor urutan
  - Marker khusus untuk titik awal
- **Timeline Detail**: 
  - Daftar destinasi berurutan sesuai rute optimal
  - Waktu tiba dan berangkat di setiap destinasi
  - Durasi kunjungan di setiap destinasi
  - Jarak dan waktu tempuh dari destinasi sebelumnya
  - Informasi tambahan destinasi (rating, kategori, harga, jam operasional)
- **Tombol Aksi**:
  - Simpan itinerary (jika belum disimpan)
  - Download/Print itinerary
  - Edit/regenerate itinerary
  - Bagikan itinerary

Halaman ini memberikan panduan lengkap kepada wisatawan untuk melaksanakan perjalanan mereka dengan efisien.

---

### 10. Halaman Daftar Itinerary Saya
**Route:** `/itinerary/list` (itinerary.list)
**Auth Required:** Ya (middleware auth:web)

Halaman Daftar Itinerary Saya menampilkan riwayat semua jadwal perjalanan yang telah dibuat oleh pengguna yang sedang login. Halaman ini berfungsi sebagai arsip pribadi perjalanan wisata pengguna.

Fitur yang tersedia:
- **Daftar Itinerary**: Menampilkan semua itinerary dalam bentuk card atau tabel
- **Informasi per Itinerary**:
  - Nama itinerary
  - Tanggal perjalanan
  - Status (draft/confirmed/completed)
  - Jumlah destinasi yang dikunjungi
  - Total jarak dan durasi
  - Tanggal dibuat
- **Aksi per Itinerary**:
  - Lihat detail itinerary
  - Edit itinerary
  - Hapus itinerary
  - Duplikasi itinerary
- **Pagination**: Untuk menampilkan banyak itinerary dengan efisien (10 per halaman)
- **Filter & Sorting**: Berdasarkan tanggal, status, dll

Halaman ini memudahkan pengguna untuk mengelola berbagai rencana perjalanan mereka, baik yang sudah dilaksanakan maupun yang masih direncanakan.

---

### 11. Halaman Detail Itinerary
**Route:** `/itinerary/{id}` (itinerary.show)
**Auth Required:** Ya (middleware auth:web)

Halaman Detail Itinerary menampilkan informasi lengkap dari satu itinerary yang telah disimpan sebelumnya. Halaman ini mirip dengan halaman hasil itinerary, namun untuk itinerary yang sudah tersimpan dalam database.

Konten halaman:
- **Informasi Header**: Nama, tanggal, waktu mulai, status itinerary
- **Titik Awal**: Informasi lokasi/destinasi awal perjalanan
- **Statistik**: Total destinasi, total jarak, total durasi, estimasi waktu selesai
- **Peta Rute**: Visualisasi interaktif rute perjalanan dengan polyline dan markers
- **Timeline Perjalanan**: Jadwal detail waktu kunjungan per destinasi
- **Detail per Destinasi**:
  - Urutan kunjungan
  - Nama dan informasi destinasi
  - Waktu tiba dan berangkat
  - Durasi kunjungan
  - Jarak dari destinasi sebelumnya
  - Link ke detail destinasi
- **Tombol Aksi**:
  - Download PDF
  - Print itinerary
  - Share via social media atau email
  - Edit itinerary
  - Hapus itinerary

Halaman ini dapat diakses kembali kapan saja untuk referensi atau persiapan perjalanan.

---

## HALAMAN ADMIN

### 12. Halaman Login Admin
**Route:** `/admin/login` (admin.login)

Halaman Login Admin adalah halaman autentikasi khusus untuk administrator sistem yang terpisah dari login pengguna biasa. Halaman ini menggunakan guard 'admin' untuk memastikan pemisahan hak akses yang jelas.

Fitur keamanan:
- Form login dengan email dan password admin
- Validasi kredensial terhadap tabel admin (bukan users)
- Session regeneration setelah login berhasil
- Auto-redirect ke dashboard admin jika sudah login
- Redirect ke dashboard setelah login berhasil
- Pesan error yang informatif jika login gagal

Sistem admin terpisah sepenuhnya dari sistem pengguna untuk meningkatkan keamanan dan memudahkan pengelolaan hak akses. Admin tidak dapat login melalui halaman login pengguna, dan sebaliknya.

---

### 13. Halaman Dashboard Admin
**Route:** `/admin/dashboard` (admin.dashboard)
**Auth Required:** Ya (middleware auth:admin)

Halaman Dashboard Admin adalah halaman utama panel administrasi yang menampilkan ringkasan statistik dan informasi penting sistem secara keseluruhan. Dashboard dirancang untuk memberikan overview cepat kepada admin tentang kondisi dan aktivitas sistem.

Statistik yang ditampilkan:
- **Total Destinasi Wisata**: Jumlah keseluruhan destinasi dalam database
- **Total Kategori**: Jumlah kategori wisata yang tersedia
- **Total Pengguna**: Jumlah pengguna terdaftar
- **Total Itinerary**: Jumlah itinerary yang telah dibuat oleh semua pengguna

Widget tambahan yang mungkin ada:
- Grafik pertumbuhan pengguna
- Destinasi wisata paling populer
- Aktivitas terbaru pengguna
- Quick actions untuk manajemen data
- Link navigasi ke halaman-halaman manajemen lainnya

Dashboard ini merupakan pusat kontrol admin untuk monitoring dan akses cepat ke semua fitur administrasi.

---

### 14. Halaman Manajemen Data Wisata
**Route:** `/admin/tourism` (admin.tourism.index)
**Auth Required:** Ya (middleware auth:admin)

Halaman Manajemen Data Wisata adalah halaman CRUD (Create, Read, Update, Delete) lengkap untuk mengelola data destinasi wisata. Halaman ini menggunakan DataTables untuk menampilkan data dalam bentuk tabel interaktif dengan fitur sorting, searching, dan pagination.

Fitur utama:
- **DataTables**: Tabel responsif dengan AJAX loading
- **Kolom Informasi**: ID, Nama, Rating, Popularity, Kategori, Rentang Harga, Status Ready
- **Tombol Aksi per Baris**:
  - Lihat detail
  - Edit data
  - Hapus data
- **Tombol Tambah**: Untuk menambah destinasi baru
- **Fitur Khusus**:
  - Import data dari API eksternal
  - Update data dari SerpAPI (Google Maps data)
  - Bulk actions untuk multiple selection

**Form Tambah/Edit Destinasi** mencakup:
- Informasi dasar (nama, alamat, deskripsi, koordinat)
- Rating dan popularity
- Multiple kategori (checkbox)
- Multiple harga tiket untuk berbagai kategori pengunjung
- Multiple jam operasional untuk setiap hari
- Upload multiple foto
- Data review/testimoni
- Status ready (siap ditampilkan ke pengguna atau tidak)

Halaman ini sangat penting untuk menjaga kualitas dan aktualitas data destinasi wisata dalam sistem.

---

### 15. Halaman Detail Data Wisata Admin
**Route:** `/admin/tourism/{id}` (admin.tourism.show)
**Auth Required:** Ya (middleware auth:admin)

Halaman Detail Data Wisata Admin menampilkan informasi lengkap satu destinasi wisata dalam format yang mudah dibaca untuk keperluan verifikasi dan review data oleh admin.

Informasi yang ditampilkan:
- **Data Utama**: Nama, alamat lengkap, koordinat, rating, popularity
- **Kategori**: Daftar semua kategori yang terkait
- **Harga**: Tabel harga untuk berbagai kategori pengunjung (dewasa, anak, dll)
- **Jam Operasional**: Tabel jadwal buka-tutup per hari
- **Galeri Foto**: Grid semua foto destinasi dengan preview
- **Deskripsi Lengkap**: Full text deskripsi
- **Review**: Daftar review dan rating dari pengguna
- **Data Teknis**: 
  - Sumber data (manual/API)
  - Tanggal dibuat dan update terakhir
  - Status publikasi
- **Tombol Aksi**:
  - Edit data
  - Hapus data
  - Update dari API
  - Publish/Unpublish

Halaman ini membantu admin dalam quality control data sebelum dipublikasikan ke pengguna.

---

### 16. Halaman Manajemen Kategori
**Route:** `/admin/categories` (admin.categories.index)
**Auth Required:** Ya (middleware auth:admin)

Halaman Manajemen Kategori digunakan untuk mengelola kategori-kategori destinasi wisata yang digunakan untuk klasifikasi dan filtering. Kategori membantu pengguna menemukan destinasi sesuai minat mereka.

Fitur halaman:
- **DataTables**: Tabel kategori dengan AJAX
- **Kolom**: ID, Nama Kategori, Deskripsi, Tanggal Dibuat
- **CRUD Operations**:
  - Tambah kategori baru
  - Edit nama dan deskripsi kategori
  - Hapus kategori (dengan validasi tidak ada destinasi terkait)
  - Lihat jumlah destinasi per kategori
- **Modal Form**: Form tambah/edit dalam modal popup untuk UX yang lebih baik
- **Validasi**: 
  - Nama kategori harus unik
  - Maksimal 255 karakter
  - Cek dependensi sebelum hapus

Contoh kategori: Wisata Alam, Wisata Kuliner, Wisata Edukasi, Wisata Religi, Wisata Belanja, Taman Bermain, Museum, Pantai, dll.

Pengelolaan kategori yang baik memudahkan pengguna dalam filtering dan menemukan destinasi sesuai preferensi.

---

### 17. Halaman Manajemen Pengguna
**Route:** `/admin/users` (admin.users.index)
**Auth Required:** Ya (middleware auth:admin)

Halaman Manajemen Pengguna memungkinkan admin untuk mengelola akun-akun pengguna yang terdaftar dalam sistem. Halaman ini penting untuk monitoring aktivitas pengguna dan menangani masalah terkait akun.

Fitur yang tersedia:
- **DataTables**: Tabel pengguna dengan informasi lengkap
- **Kolom**: ID, Nama, Email, Tanggal Registrasi, Status
- **CRUD Operations**:
  - Tambah pengguna baru (manual)
  - Edit data pengguna (nama, email, password)
  - Hapus pengguna (dengan konfirmasi)
  - Reset password pengguna
  - Aktifkan/Nonaktifkan akun
- **Informasi Tambahan per User**:
  - Jumlah itinerary yang dibuat
  - Jumlah destinasi di Trip Cart
  - Aktivitas terakhir
- **Filter & Search**: Berdasarkan nama, email, tanggal registrasi
- **Export**: Export daftar pengguna ke Excel/CSV

Form tambah/edit meliputi:
- Nama lengkap
- Email (harus unik)
- Password (di-hash dengan bcrypt)
- Konfirmasi password
- Status aktif

Halaman ini membantu admin dalam customer relationship management dan troubleshooting masalah pengguna.

---

### 18. Halaman Distance Matrix
**Route:** `/admin/distance-matrix` (admin.distance-matrix.index)
**Auth Required:** Ya (middleware auth:admin)

Halaman Distance Matrix menampilkan visualisasi matriks jarak dan durasi perjalanan antara semua destinasi wisata yang ada dalam cache. Halaman ini sangat penting untuk verifikasi data jarak yang digunakan dalam algoritma TSP dan untuk monitoring kelengkapan data distance cache.

Fitur halaman:
- **Matriks Jarak**: Tabel 2D yang menampilkan jarak (dalam km) antara setiap pasangan destinasi
- **Matriks Durasi**: Tabel 2D yang menampilkan waktu tempuh (dalam menit) antara setiap pasangan destinasi
- **Color Coding**: 
  - Hijau: jarak dekat
  - Kuning: jarak menengah
  - Merah: jarak jauh
  - Abu-abu: data tidak tersedia
- **Peta Interaktif**: 
  - Menampilkan semua destinasi sebagai marker
  - Klik dua destinasi untuk melihat rute dan jarak
  - Polyline menunjukkan jalur perjalanan
  - Popup dengan informasi jarak dan durasi
- **Filter**: Pilih subset destinasi untuk melihat matriks yang lebih sederhana
- **Export**: Download matriks dalam format CSV atau Excel
- **Refresh Data**: Tombol untuk recalculate distance cache yang hilang atau outdated

Data pada halaman ini berasal dari tabel `distance_cache` yang dihitung menggunakan Google Distance Matrix API atau OSRM. Matriks ini tidak selalu simetris (jarak A ke B bisa berbeda dengan B ke A) karena faktor kondisi jalan dan lalu lintas.

Halaman ini membantu admin memastikan data jarak yang akurat untuk perhitungan itinerary yang optimal.

---

### 19. Halaman Manajemen Itinerary Admin
**Route:** `/admin/itinerary` (admin.itinerary.index)
**Auth Required:** Ya (middleware auth:admin)

Halaman Manajemen Itinerary Admin menampilkan semua itinerary yang telah dibuat oleh seluruh pengguna sistem. Halaman ini memberikan visibilitas penuh kepada admin untuk monitoring aktivitas pembuatan itinerary dan analisis pola perjalanan wisatawan.

Informasi yang ditampilkan:
- **Tabel Itinerary**: Daftar semua itinerary dari semua pengguna
- **Kolom**:
  - ID Itinerary
  - Nama Itinerary
  - Nama Pengguna (pembuat)
  - Tanggal Perjalanan
  - Jumlah Destinasi
  - Total Jarak
  - Total Durasi
  - Status (draft/confirmed/completed)
  - Tanggal Dibuat
- **Filter & Search**:
  - Berdasarkan pengguna
  - Berdasarkan tanggal perjalanan
  - Berdasarkan status
  - Berdasarkan destinasi yang dikunjungi
- **Aksi per Itinerary**:
  - Lihat detail lengkap itinerary
  - Hapus itinerary (jika bermasalah)
  - Export data itinerary
- **Pagination**: 15 itinerary per halaman
- **Statistik**: 
  - Destinasi paling sering dikunjungi
  - Rata-rata jumlah destinasi per itinerary
  - Rata-rata total jarak perjalanan
  - Pola waktu perjalanan (weekday vs weekend)

Halaman ini berguna untuk analisis bisnis, seperti mengetahui destinasi mana yang paling diminati dalam kombinasi perjalanan, atau pola waktu kunjungan wisatawan.

---

### 20. Halaman Import Data dari API
**Route:** `/admin/tourism-import-api` (admin.tourism.import-api)
**Auth Required:** Ya (middleware auth:admin)

Halaman Import Data dari API adalah fitur khusus yang memungkinkan admin untuk mengimpor data destinasi wisata secara otomatis dari sumber eksternal seperti Google Places API, TripAdvisor API, atau API pariwisata lainnya.

Proses import meliputi:
1. **Konfigurasi API**: Input API key dan parameter pencarian
2. **Preview Data**: Menampilkan data yang akan diimpor untuk review
3. **Mapping Fields**: Pemetaan field dari API ke struktur database lokal
4. **Validasi**: Cek duplikasi berdasarkan nama atau koordinat
5. **Import**: Proses impor data ke database
6. **Log**: Menampilkan hasil import (berhasil/gagal) dengan detail

Data yang dapat diimpor:
- Informasi dasar destinasi (nama, alamat, koordinat)
- Rating dan review
- Foto-foto
- Jam operasional
- Harga tiket (jika tersedia)
- Kategori/tipe destinasi

Halaman ini sangat membantu untuk memperkaya database destinasi wisata tanpa harus input manual satu per satu, terutama saat inisialisasi sistem atau update berkala data.

---

### 21. Halaman Update Data SerpAPI
**Route:** `/admin/tourism-update-serpapi` (admin.tourism.update-serpapi)
**Auth Required:** Ya (middleware auth:admin)

Halaman Update Data SerpAPI digunakan untuk memperbarui atau memperkaya data destinasi wisata yang sudah ada dengan informasi terbaru dari SerpAPI (Google Maps Search API). Fitur ini penting untuk menjaga aktualitas data seperti rating, review, foto, dan jam operasional yang bisa berubah seiring waktu.

Proses update meliputi:
1. **Pilih Destinasi**: Pilih destinasi yang ingin diupdate (single atau bulk)
2. **Fetch dari SerpAPI**: Query ke SerpAPI berdasarkan nama dan koordinat destinasi
3. **Compare Data**: Menampilkan perbandingan data lama vs data baru
4. **Pilih Field**: Pilih field mana yang ingin diupdate
5. **Update**: Simpan perubahan ke database
6. **Log**: Catat riwayat update

Data yang dapat diupdate:
- Rating terbaru
- Jumlah review
- Foto-foto baru
- Jam operasional (jika berubah)
- Informasi kontak
- Harga tiket terbaru
- Deskripsi update

Sistem menggunakan multiple API key untuk menghindari rate limiting dan memastikan kelancaran proses update. Halaman ini membantu menjaga relevansi dan akurasi informasi destinasi wisata.

---

## FITUR KEAMANAN & MIDDLEWARE

### Authentication Guards
Sistem menggunakan dua guard terpisah:
- **auth:web** - Untuk autentikasi pengguna biasa
- **auth:admin** - Untuk autentikasi administrator

Pemisahan ini memastikan:
- Admin tidak bisa login sebagai user biasa
- User biasa tidak bisa akses halaman admin
- Session terpisah untuk keamanan lebih baik

### Protected Routes
- Semua route Trip Cart, Itinerary (user) memerlukan login pengguna
- Semua route dengan prefix `/admin` memerlukan login admin
- Redirect otomatis ke login jika belum autentikasi

### Data Security
- Password di-hash menggunakan bcrypt
- Session regeneration untuk mencegah session fixation
- CSRF protection pada semua form POST/PUT/DELETE
- Validasi input ketat pada semua endpoint
- Sanitasi output untuk mencegah XSS

---

## TEKNOLOGI & ALGORITMA YANG DIGUNAKAN

### Algoritma Simple Additive Weighting (SAW)
Digunakan pada halaman Tourism Index untuk memberikan rekomendasi destinasi berdasarkan multi-kriteria dengan normalisasi dan pembobotan.

### Algoritma Travelling Salesman Problem (TSP) dengan Dynamic Programming
Digunakan pada proses generate itinerary untuk menemukan rute optimal yang meminimalkan total jarak tempuh dengan kompleksitas O(2^n * n^2).

### Distance Caching System
Sistem cache jarak untuk mengurangi beban API dan mempercepat perhitungan itinerary.

### API Integration
- Google Distance Matrix API / OSRM - Untuk perhitungan jarak dan durasi
- Google Maps JavaScript API - Untuk visualisasi peta
- SerpAPI - Untuk import dan update data destinasi wisata
- OSRM Routing API - Untuk mendapatkan polyline rute perjalanan

### Frontend Technologies
- Laravel Blade Templates
- Tailwind CSS untuk styling
- Alpine.js / JavaScript untuk interaktivitas
- DataTables untuk tabel admin
- Leaflet / Google Maps untuk visualisasi peta

### Backend Technologies
- Laravel 11 Framework
- MySQL Database
- RESTful API design
- AJAX untuk request asynchronous
