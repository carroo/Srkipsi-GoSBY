@extends('admin.layout')

@section('title', 'Kelola Wisata')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Wisata</h1>
                <p class="text-gray-600 mt-1">Manajemen data destinasi wisata</p>
            </div>
            <div class="flex gap-3">
                <button onclick="openUpdateSerpModal()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Update SerpAPI
                </button>
                <button onclick="openImportModal()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                    <i class="fas fa-download mr-2"></i>
                    Import dari API
                </button>
                <button onclick="createTourism()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Wisata
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6">
                <table id="tourismTable" class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="text-left">No</th>
                            <th class="text-left">Nama Wisata</th>
                            <th class="text-left">Kategori</th>
                            <th class="text-left">Range Harga</th>
                            <th class="text-left">Rating</th>
                            <th class="text-left">popularity</th>
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
    <div id="tourismModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 w-full max-w-6xl mb-10">
            <div class="relative bg-white rounded-xl shadow-2xl">
                <!-- Modal Header -->
                <div
                    class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">
                        Tambah Wisata
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="tourismForm" class="p-6" enctype="multipart/form-data">
                    <input type="hidden" id="tourismId" name="id">

                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-4 overflow-x-auto">
                            <button type="button" onclick="switchTab('basic')" id="tab-basic"
                                class="tab-button py-3 px-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600 whitespace-nowrap">
                                <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
                            </button>
                            <button type="button" onclick="switchTab('location')" id="tab-location"
                                class="tab-button py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                                <i class="fas fa-map-marker-alt mr-2"></i>Lokasi
                            </button>
                            <button type="button" onclick="switchTab('contact')" id="tab-contact"
                                class="tab-button py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                                <i class="fas fa-phone mr-2"></i>Kontak
                            </button>
                            <button type="button" onclick="switchTab('categories')" id="tab-categories"
                                class="tab-button py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                                <i class="fas fa-tags mr-2"></i>Kategori
                            </button>
                            <button type="button" onclick="switchTab('prices')" id="tab-prices"
                                class="tab-button py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                                <i class="fas fa-money-bill-wave mr-2"></i>Harga & Jam
                            </button>
                            <button type="button" onclick="switchTab('reviews')" id="tab-reviews"
                                class="tab-button py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                                <i class="fas fa-comments mr-2"></i>Reviews
                            </button>
                            <button type="button" onclick="switchTab('images')" id="tab-images"
                                class="tab-button py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                                <i class="fas fa-images mr-2"></i>Gambar
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="space-y-6">
                        <!-- Basic Information Tab -->
                        <div id="content-basic" class="tab-content">
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Name -->
                                <div>
                                    <label for="tourismName" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Wisata <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="tourismName" name="name"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Contoh: Pantai Kuta" required>
                                    <p id="nameError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="tourismDescription" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi
                                    </label>
                                    <textarea id="tourismDescription" name="description" rows="6"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Masukkan deskripsi lengkap wisata..."></textarea>
                                    <p id="descriptionError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Rating -->
                                <div>
                                    <label for="tourismRating" class="block text-sm font-medium text-gray-700 mb-2">
                                        Rating (0-5)
                                    </label>
                                    <input type="number" id="tourismRating" name="rating" step="0.1"
                                        min="0" max="5"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="4.5">
                                    <p id="ratingError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- popularity -->
                                <div>
                                    <label for="tourismpopularity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah popularity
                                    </label>
                                    <input type="number" id="tourismpopularity" name="popularity" step="1"
                                        min="0"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="100">
                                    <p id="popularityError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Location Tab -->
                        <div id="content-location" class="tab-content hidden">
                            <div class="space-y-4">
                                <!-- Address -->
                                <div>
                                    <label for="tourismLocation" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-map-marked-alt mr-1"></i>Alamat Lokasi
                                    </label>
                                    <textarea id="tourismLocation" name="location" rows="2"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Jl. Pantai Kuta, Badung, Bali"></textarea>
                                    <p id="locationError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Interactive Map -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-map mr-1"></i>Pilih Lokasi di Peta
                                    </label>
                                    <div id="map" class="w-full h-96 rounded-lg border border-gray-300"></div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Klik pada peta untuk memilih lokasi, atau geser marker untuk menyesuaikan posisi
                                    </p>
                                </div>

                                <!-- Coordinates (read-only, auto-filled from map) -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="tourismLatitude" class="block text-sm font-medium text-gray-700 mb-2">
                                            Latitude
                                        </label>
                                        <input type="number" id="tourismLatitude" name="latitude" step="0.00000001"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="-7.2575" readonly>
                                        <p id="latitudeError" class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>

                                    <div>
                                        <label for="tourismLongitude"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Longitude
                                        </label>
                                        <input type="number" id="tourismLongitude" name="longitude" step="0.00000001"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="112.7521" readonly>
                                        <p id="longitudeError" class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Tab -->
                        <div id="content-contact" class="tab-content hidden">
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Phone -->
                                <div>
                                    <label for="tourismPhone" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-phone mr-1"></i>Nomor Telepon
                                    </label>
                                    <input type="text" id="tourismPhone" name="phone"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="0361-123456 / 08123456789">
                                    <p id="phoneError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="tourismEmail" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-envelope mr-1"></i>Email
                                    </label>
                                    <input type="email" id="tourismEmail" name="email"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="info@wisata.com">
                                    <p id="emailError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Website -->
                                <div>
                                    <label for="tourismWebsite" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-globe mr-1"></i>Website
                                    </label>
                                    <input type="url" id="tourismWebsite" name="website"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="https://www.wisata.com">
                                    <p id="websiteError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Tab -->
                        <div id="content-categories" class="tab-content hidden">
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Categories -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="fas fa-tags mr-2"></i>Kategori Wisata
                                    </label>
                                    <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                                        @foreach ($categories as $category)
                                            <label class="flex items-center hover:bg-gray-50 p-2 rounded cursor-pointer">
                                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                    class="category-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p id="categoriesError" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Prices & Hours Tab -->
                        <div id="content-prices" class="tab-content hidden">
                            <!-- Prices Section -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-money-bill-wave mr-2"></i>Harga Tiket
                                    </label>
                                    <button type="button" onclick="addPrice()"
                                        class="text-sm text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-plus mr-1"></i>Tambah Harga
                                    </button>
                                </div>
                                <div id="pricesContainer" class="space-y-3">
                                    <!-- Price items will be added here -->
                                </div>
                                <p id="pricesError" class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>

                            <!-- Hours Section -->
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-clock mr-2"></i>Jam Operasional
                                    </label>
                                    <button type="button" onclick="addHour()"
                                        class="text-sm text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-plus mr-1"></i>Tambah Jadwal
                                    </button>
                                </div>
                                <div id="hoursContainer" class="space-y-3">
                                    <!-- Hour items will be added here -->
                                </div>
                                <p id="hoursError" class="mt-1 text-sm text-red-600 hidden"></p>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div id="content-reviews" class="tab-content hidden">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-comments mr-2"></i>Reviews / Ulasan
                                    </label>
                                    <button type="button" onclick="addReview()"
                                        class="text-sm text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-plus mr-1"></i>Tambah Review
                                    </button>
                                </div>
                                <div id="reviewsContainer" class="space-y-3">
                                    <!-- Review items will be added here -->
                                </div>
                                <p id="reviewsError" class="mt-1 text-sm text-red-600 hidden"></p>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-xs text-blue-700">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Tambahkan review/ulasan dari pengunjung untuk memberikan gambaran pengalaman wisata
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Images Tab -->
                        <div id="content-images" class="tab-content hidden">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="fas fa-images mr-2"></i>Upload Gambar
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                    <input type="file" id="tourismImages" name="images[]" multiple accept="image/*"
                                        class="hidden" onchange="previewImages(this)">
                                    <label for="tourismImages" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600">Klik untuk upload gambar atau drag & drop</p>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF hingga 2MB per file</p>
                                    </label>
                                </div>
                                <p id="imagesError" class="mt-1 text-sm text-red-600 hidden"></p>

                                <!-- Image Preview -->
                                <div id="imagePreviewContainer" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <!-- Image previews will be added here -->
                                </div>

                                <!-- Existing Images (for edit mode) -->
                                <div id="existingImagesContainer" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <!-- Existing images will be added here -->
                                </div>
                            </div>
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

    <!-- Modal View Detail -->
    <div id="viewModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 w-full max-w-5xl mb-10">
            <div class="relative bg-white rounded-xl shadow-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-map-marked-alt mr-2 text-blue-600"></i>Detail Wisata
                    </h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div id="viewContent" class="p-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div id="importModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 w-full max-w-3xl">
            <div class="relative bg-white rounded-xl shadow-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-download text-green-600 mr-2"></i>
                        Import Data Wisata dari API
                    </h3>
                    <button id="closeImportBtn" onclick="closeImportModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Import ini akan mengambil data wisata dari <strong>tourism.surabaya.go.id</strong> dan
                            menyimpannya ke dalam sistem.
                            Data yang sudah ada akan <strong>diperbarui</strong>, data baru akan
                            <strong>ditambahkan</strong>.
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div id="importProgress" class="hidden space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium text-gray-700">Progress Import</span>
                            <span id="importProgressText" class="font-semibold text-blue-600">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                            <div id="importProgressBar"
                                class="bg-gradient-to-r from-green-500 to-blue-500 h-4 rounded-full transition-all duration-300"
                                style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Log Container -->
                    <div class="border border-gray-200 rounded-lg">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                            <h4 class="font-semibold text-sm text-gray-700">
                                <i class="fas fa-list-ul mr-2"></i>
                                Log Aktivitas
                            </h4>
                        </div>
                        <div id="importLog" class="p-4 h-64 overflow-y-auto bg-white text-sm font-mono">
                            <p class="text-gray-400 text-center py-8">Belum ada log. Klik tombol "Mulai Import" untuk
                                memulai.</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                    <button type="button" id="closeImportBtn2" onclick="closeImportModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Tutup
                    </button>
                    <button type="button" id="stopImportBtn" onclick="stopImport()" class="hidden px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-stop mr-2"></i>
                        Stop Import
                    </button>
                    <button type="button" id="startImportBtn" onclick="startImport()"
                        class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-play mr-2"></i>
                        Mulai Import
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update SerpAPI -->
    <div id="updateSerpModal"
        class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 w-full max-w-3xl">
            <div class="relative bg-white rounded-xl shadow-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-sync-alt text-purple-600 mr-2"></i>
                        Update Data SerpAPI
                    </h3>
                    <button id="closeUpdateSerpBtn" onclick="closeUpdateSerpModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-sm text-purple-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Update ini akan mengambil data <strong>rating, popularity, address, jam operasional, dan
                                reviews</strong> dari <strong>SerpAPI (Google Maps)</strong>
                            untuk wisata yang <strong>belum lengkap datanya (is_ready = 0)</strong>.
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div id="updateSerpProgress" class="hidden space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium text-gray-700">Progress Update</span>
                            <span id="updateSerpProgressText" class="font-semibold text-purple-600">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                            <div id="updateSerpProgressBar"
                                class="bg-gradient-to-r from-purple-500 to-pink-500 h-4 rounded-full transition-all duration-300"
                                style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Log Container -->
                    <div class="border border-gray-200 rounded-lg">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                            <h4 class="font-semibold text-sm text-gray-700">
                                <i class="fas fa-list-ul mr-2"></i>
                                Log Aktivitas
                            </h4>
                        </div>
                        <div id="updateSerpLog" class="p-4 h-64 overflow-y-auto bg-white text-sm font-mono">
                            <p class="text-gray-400 text-center py-8">Belum ada log. Klik tombol "Mulai Update" untuk
                                memulai.</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                    <button type="button" id="closeUpdateSerpBtn2" onclick="closeUpdateSerpModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Tutup
                    </button>
                    <button type="button" id="stopUpdateSerpBtn" onclick="stopUpdateSerp()" class="hidden px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-stop mr-2"></i>
                        Stop Update
                    </button>
                    <button type="button" id="startUpdateSerpBtn" onclick="startUpdateSerp()"
                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-play mr-2"></i>
                        Mulai Update
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let table;
        let priceIndex = 0;
        let hourIndex = 0;
        let reviewIndex = 0;
        let deleteImages = [];
        let map;
        let marker;
        let importEventSource = null;
        let updateSerpEventSource = null;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#tourismTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.tourism.index') }}",
                    type: 'GET'
                },
                columns: [{
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
                        data: 'categories',
                        name: 'categories',
                        orderable: false,
                        width: '18%'
                    },
                    {
                        data: 'price_range',
                        name: 'price_range',
                        orderable: false,
                        width: '17%'
                    },
                    {
                        data: 'rating',
                        name: 'rating',
                        width: '10%'
                    },
                    {
                        data: 'popularity',
                        name: 'popularity',
                        width: '10%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '10%'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                language: {
                    processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat data...</div>',
                    emptyTable: "Tidak ada data wisata",
                    zeroRecords: "Tidak ada data yang cocok"
                },
                rowCallback: function(row, data) {
                    // Add pink background if is_ready is false (0)
                    if (data.is_ready === 0 || data.is_ready === false) {
                        // add style background color #ffebee
                        $(row).css('background-color', '#ffebee');
                        // $(row).addClass('bg-red-100');
                    }
                }
            });

            // Handle form submission
            $('#tourismForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.text-red-600').addClass('hidden');

                const id = $('#tourismId').val();
                const url = id ? `/admin/tourism/${id}` : '{{ route('admin.tourism.store') }}';
                let method = id ? 'PUT' : 'POST';

                const formData = new FormData(this);

                // Add delete images array for update
                if (id && deleteImages.length > 0) {
                    deleteImages.forEach(imageId => {
                        formData.append('delete_images[]', imageId);
                    });
                }

                // Add method spoofing for PUT request
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                    method = 'POST';
                }

                // Disable submit button
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
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
                            Object.keys(errors).forEach(key => {
                                $(`#${key}Error`).text(errors[key][0]).removeClass(
                                    'hidden');
                            });
                            Toast.fire({
                                icon: 'error',
                                title: 'Periksa kembali form Anda'
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message || 'Terjadi kesalahan'
                            });
                        }
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).html(
                            '<i class="fas fa-save mr-2"></i> Simpan');
                    }
                });
            });
        });

        // Tab Management
        function switchTab(tab) {
            // Hide all tabs
            $('.tab-content').addClass('hidden');
            $('.tab-button').removeClass('border-blue-600 text-blue-600').addClass('border-transparent text-gray-500');

            // Show selected tab
            $(`#content-${tab}`).removeClass('hidden');
            $(`#tab-${tab}`).removeClass('border-transparent text-gray-500').addClass('border-blue-600 text-blue-600');

            // Initialize map when location tab is opened
            if (tab === 'location') {
                setTimeout(() => {
                    const lat = parseFloat($('#tourismLatitude').val()) || -7.2575;
                    const lng = parseFloat($('#tourismLongitude').val()) || 112.7521;
                    initMap(lat, lng);
                }, 100);
            }
        }

        // Create new tourism
        function createTourism() {
            $('#modalTitle').text('Tambah Wisata');
            $('#tourismForm')[0].reset();
            $('#tourismId').val('');
            $('.text-red-600').addClass('hidden');
            $('.category-checkbox').prop('checked', false);
            $('#pricesContainer, #hoursContainer, #reviewsContainer, #imagePreviewContainer, #existingImagesContainer')
                .empty();
            priceIndex = 0;
            hourIndex = 0;
            reviewIndex = 0;
            deleteImages = [];
            switchTab('basic');
            $('#tourismModal').removeClass('hidden');
        }

        // View tourism detail
        function viewTourism(id) {
            $.ajax({
                url: `/admin/tourism/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        let content = `
                    <div class="space-y-6">
                        <!-- Basic Info -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg">
                            <h4 class="text-2xl font-bold text-gray-900 mb-2">${data.name}</h4>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-map-marker-alt mr-1"></i>${data.location || '-'}</span>
                                <span><i class="fas fa-star text-yellow-400 mr-1"></i>${data.rating ? data.rating + '/5' : '-'}</span>
                                <span><i class="fas fa-users mr-1"></i>${data.popularity ? Number(data.popularity).toLocaleString('id-ID') + ' popularity' : '0 popularity'}</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h5 class="font-semibold text-gray-900 mb-2">Deskripsi</h5>
                            <p class="text-gray-600">${data.description || '-'}</p>
                        </div>

                        <!-- Contact & Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Kontak</h5>
                                <div class="space-y-2 text-sm">
                                    <p><i class="fas fa-phone w-5 text-gray-400"></i>${data.phone || '-'}</p>
                                    <p><i class="fas fa-envelope w-5 text-gray-400"></i>${data.email || '-'}</p>
                                    <p><i class="fas fa-globe w-5 text-gray-400"></i>${data.website || '-'}</p>
                                </div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Koordinat</h5>
                                <div class="space-y-2 text-sm">
                                    <p><i class="fas fa-map-pin w-5 text-gray-400"></i>Lat: ${data.latitude || '-'}</p>
                                    <p><i class="fas fa-map-pin w-5 text-gray-400"></i>Long: ${data.longitude || '-'}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div>
                            <h5 class="font-semibold text-gray-900 mb-2">Kategori</h5>
                            <div class="flex flex-wrap gap-2">
                                ${data.categories.map(cat => `<span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">${cat.name}</span>`).join('') || '<span class="text-gray-400 text-sm">Tidak ada kategori</span>'}
                            </div>
                        </div>

                        <!-- Prices -->
                        ${data.prices.length > 0 ? `
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Harga Tiket</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    ${data.prices.map(price => `
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <span class="text-sm text-gray-700">${price.type}</span>
                                        <span class="font-semibold text-blue-600">Rp ${Number(price.price).toLocaleString('id-ID')}</span>
                                    </div>
                                `).join('')}
                                </div>
                            </div>
                            ` : ''}

                        <!-- Hours -->
                        ${data.hours.length > 0 ? `
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Jam Operasional</h5>
                                <div class="space-y-2">
                                    ${data.hours.map(hour => `
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <span class="text-sm text-gray-700">${hour.day}</span>
                                        <span class="text-sm text-green-600">
                                            ${hour.open_time} - ${hour.close_time}
                                        </span>
                                    </div>
                                `).join('')}
                                </div>
                            </div>
                            ` : ''}

                        <!-- Reviews -->
                        ${data.reviews && data.reviews.length > 0 ? `
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2"><i class="fas fa-comments mr-2"></i>Reviews</h5>
                                <div class="space-y-3">
                                    ${data.reviews.map(review => `
                                    <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                                        <p class="text-sm text-gray-700 italic">"${review.snippet}"</p>
                                    </div>
                                `).join('')}
                                </div>
                            </div>
                            ` : ''}

                        <!-- Images -->
                        ${data.files.length > 0 ? `
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Galeri Foto</h5>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    ${data.files.map(file => `
                                    <img src="/storage/${file.file_path}" alt="${file.original_name}" 
                                        class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 transition"
                                        onclick="window.open('/storage/${file.file_path}', '_blank')">
                                `).join('')}
                                </div>
                            </div>
                            ` : ''}
                    </div>
                `;

                        $('#viewContent').html(content);
                        $('#viewModal').removeClass('hidden');
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

        // Edit tourism
        function editTourism(id) {
            $.ajax({
                url: `/admin/tourism/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;

                        $('#modalTitle').text('Edit Wisata');
                        $('#tourismId').val(data.id);
                        $('#tourismName').val(data.name);
                        $('#tourismDescription').val(data.description);
                        $('#tourismLocation').val(data.location);
                        $('#tourismLatitude').val(data.latitude);
                        $('#tourismLongitude').val(data.longitude);
                        $('#tourismPhone').val(data.phone);
                        $('#tourismEmail').val(data.email);
                        $('#tourismWebsite').val(data.website);
                        $('#tourismRating').val(data.rating);
                        $('#tourismpopularity').val(data.popularity);

                        // Set categories
                        $('.category-checkbox').prop('checked', false);
                        data.categories.forEach(cat => {
                            $(`.category-checkbox[value="${cat.id}"]`).prop('checked', true);
                        });

                        // Load prices
                        $('#pricesContainer').empty();
                        priceIndex = 0;
                        data.prices.forEach(price => {
                            addPrice(price.type, price.price);
                        });

                        // Load hours
                        $('#hoursContainer').empty();
                        hourIndex = 0;
                        data.hours.forEach(hour => {
                            addHour(hour.day, hour.open_time, hour.close_time);
                        });

                        // Load reviews
                        $('#reviewsContainer').empty();
                        reviewIndex = 0;
                        if (data.reviews && data.reviews.length > 0) {
                            data.reviews.forEach(review => {
                                addReview(review.snippet);
                            });
                        }

                        // Load existing images
                        $('#existingImagesContainer').empty();
                        data.files.forEach(file => {
                            const imageDiv = `
                        <div class="relative group" data-image-id="${file.id}">
                            <img src="/storage/${file.file_path}" alt="${file.original_name}" 
                                class="w-full h-32 object-cover rounded-lg">
                            <button type="button" onclick="markImageForDeletion(${file.id})" 
                                class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    `;
                            $('#existingImagesContainer').append(imageDiv);
                        });

                        deleteImages = [];
                        $('.text-red-600').addClass('hidden');
                        switchTab('basic');
                        $('#tourismModal').removeClass('hidden');
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

        // Delete tourism
        function deleteTourism(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data wisata dan semua file yang terkait akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/tourism/${id}`,
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

        // Add price field
        function addPrice(type = '', price = '') {
            const html = `
        <div class="flex gap-3 price-item" data-index="${priceIndex}">
            <input type="text" name="prices[${priceIndex}][type]" value="${type}"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Contoh: Dewasa" required>
            <input type="number" name="prices[${priceIndex}][price]" value="${price}" step="0.01" min="0"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Harga" required>
            <button type="button" onclick="removePrice(${priceIndex})" 
                class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
            $('#pricesContainer').append(html);
            priceIndex++;
        }

        // Remove price field
        function removePrice(index) {
            $(`.price-item[data-index="${index}"]`).remove();
        }

        // Add hour field
        function addHour(day = '', openTime = '', closeTime = '') {
            const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            const html = `
        <div class="grid grid-cols-12 gap-3 hour-item" data-index="${hourIndex}">
            <select name="hours[${hourIndex}][day]" 
                class="col-span-4 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                <option value="">Pilih Hari</option>
                ${days.map(d => `<option value="${d}" ${d === day ? 'selected' : ''}>${d}</option>`).join('')}
            </select>
            <input type="time" name="hours[${hourIndex}][open_time]" value="${openTime}"
                class="col-span-3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            <input type="time" name="hours[${hourIndex}][close_time]" value="${closeTime}"
                class="col-span-4 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            <button type="button" onclick="removeHour(${hourIndex})" 
                class="col-span-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
            $('#hoursContainer').append(html);
            hourIndex++;
        }

        // Remove hour field
        function removeHour(index) {
            $(`.hour-item[data-index="${index}"]`).remove();
        }

        // Add review field
        function addReview(snippet = '') {
            const html = `
        <div class="review-item border border-gray-200 rounded-lg p-4" data-index="${reviewIndex}">
            <div class="flex justify-between items-start mb-2">
                <label class="block text-sm font-medium text-gray-700">Review #${reviewIndex + 1}</label>
                <button type="button" onclick="removeReview(${reviewIndex})" 
                    class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
            <textarea name="reviews[${reviewIndex}][snippet]" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Masukkan ulasan pengunjung...">${snippet}</textarea>
        </div>
    `;
            $('#reviewsContainer').append(html);
            reviewIndex++;
        }

        // Remove review field
        function removeReview(index) {
            $(`.review-item[data-index="${index}"]`).remove();
        }

        // Initialize Leaflet Map
        function initMap(lat = -7.2575, lng = 112.7521) {
            // Remove existing map if any
            if (map) {
                map.remove();
            }

            // Initialize map centered on Surabaya by default
            map = L.map('map').setView([lat, lng], 13);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add marker
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            // Update coordinates when marker is dragged
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                $('#tourismLatitude').val(position.lat.toFixed(8));
                $('#tourismLongitude').val(position.lng.toFixed(8));
            });

            // Add marker on map click
            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                marker.setLatLng([lat, lng]);
                $('#tourismLatitude').val(lat.toFixed(8));
                $('#tourismLongitude').val(lng.toFixed(8));
            });

            // Set initial coordinates
            $('#tourismLatitude').val(lat.toFixed(8));
            $('#tourismLongitude').val(lng.toFixed(8));

            // Fix map size issue
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }

        // Remove hour field
        function removeHour(index) {
            $(`.hour-item[data-index="${index}"]`).remove();
        }

        // Preview images
        function previewImages(input) {
            $('#imagePreviewContainer').empty();

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const html = `
                    <div class="relative group">
                        <img src="${e.target.result}" alt="Preview" 
                            class="w-full h-32 object-cover rounded-lg">
                        <div class="absolute bottom-2 left-2 right-2 bg-black/50 text-white text-xs p-1 rounded truncate">
                            ${file.name}
                        </div>
                    </div>
                `;
                        $('#imagePreviewContainer').append(html);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        // Mark image for deletion
        function markImageForDeletion(imageId) {
            if (!deleteImages.includes(imageId)) {
                deleteImages.push(imageId);
                $(`[data-image-id="${imageId}"]`).addClass('opacity-50').append(
                    '<div class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-lg"><span class="text-white text-xs font-semibold">Akan Dihapus</span></div>'
                );
            }
        }

        // Close modal
        function closeModal() {
            $('#tourismModal').addClass('hidden');
            $('#tourismForm')[0].reset();
            $('.text-red-600').addClass('hidden');
            $('#pricesContainer, #hoursContainer, #reviewsContainer, #imagePreviewContainer, #existingImagesContainer')
                .empty();
            priceIndex = 0;
            hourIndex = 0;
            reviewIndex = 0;
            deleteImages = [];

            // Clean up map if exists
            if (map) {
                map.remove();
                map = null;
                marker = null;
            }
        }

        // Close view modal
        function closeViewModal() {
            $('#viewModal').addClass('hidden');
        }

        // Open import modal
        function openImportModal() {
            $('#importModal').removeClass('hidden');
            $('#importProgress').addClass('hidden');
            $('#importProgressBar').css('width', '0%');
            $('#importProgressText').text('0%');
            $('#importLog').empty();
            $('#startImportBtn').prop('disabled', false);
            $('#closeImportBtn').prop('disabled', false);
        }

        // Close import modal
        function closeImportModal() {
            $('#importModal').addClass('hidden');
        }

        // Open update SerpAPI modal
        function openUpdateSerpModal() {
            $('#updateSerpModal').removeClass('hidden');
            $('#updateSerpProgress').addClass('hidden');
            $('#updateSerpProgressBar').css('width', '0%');
            $('#updateSerpProgressText').text('0%');
            $('#updateSerpLog').empty();
            $('#startUpdateSerpBtn').prop('disabled', false);
            $('#closeUpdateSerpBtn').prop('disabled', false);
            $('#closeUpdateSerpBtn2').prop('disabled', false);
        }

        // Close update SerpAPI modal
        function closeUpdateSerpModal() {
            $('#updateSerpModal').addClass('hidden');
        }

        // Start update SerpAPI
        function startUpdateSerp() {
            $('#updateSerpProgress').removeClass('hidden');
            $('#startUpdateSerpBtn').addClass('hidden');
            $('#stopUpdateSerpBtn').removeClass('hidden');
            $('#closeUpdateSerpBtn').prop('disabled', true);
            $('#closeUpdateSerpBtn2').prop('disabled', true);
            $('#updateSerpLog').empty();

            // Create EventSource for SSE
            updateSerpEventSource = new EventSource('{{ route('admin.tourism.update-serpapi') }}');
            const eventSource = updateSerpEventSource;

            eventSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);

                    // Update progress bar
                    $('#updateSerpProgressBar').css('width', data.progress + '%');
                    $('#updateSerpProgressText').text(Math.round(data.progress) + '%');

                    // Add log entry
                    let logClass = 'text-gray-700';
                    let icon = 'fa-info-circle';

                    if (data.type === 'error') {
                        logClass = 'text-red-600';
                        icon = 'fa-times-circle';
                    } else if (data.type === 'success') {
                        logClass = 'text-green-600';
                        icon = 'fa-check-circle';
                    } else if (data.type === 'warning') {
                        logClass = 'text-yellow-600';
                        icon = 'fa-exclamation-circle';
                    } else if (data.type === 'summary') {
                        const summary = JSON.parse(data.message);
                        const summaryHtml = `
                    <div class="border-t pt-3 mt-3">
                        <h4 class="font-semibold text-gray-900 mb-2">Ringkasan Update:</h4>
                        <ul class="space-y-1 text-sm">
                            <li><i class="fas fa-check-circle text-green-600"></i> Berhasil Diupdate: <strong>${summary.updated}</strong></li>
                            <li><i class="fas fa-ban text-yellow-600"></i> Dilewati: <strong>${summary.skipped}</strong></li>
                            <li><i class="fas fa-database text-gray-600"></i> Total Diproses: <strong>${summary.total_processed}</strong></li>
                            <li><i class="fas fa-exclamation-triangle text-red-600"></i> Error: <strong>${summary.errors_count}</strong></li>
                        </ul>
                        ${summary.errors_count > 0 ? `
                                <details class="mt-2">
                                    <summary class="cursor-pointer text-red-600 hover:text-red-700">Lihat Error Detail</summary>
                                    <ul class="mt-2 space-y-1 text-xs">
                                        ${summary.errors.map(e => `<li>• ${e.name}: ${e.error}</li>`).join('')}
                                    </ul>
                                </details>
                            ` : ''}
                    </div>
                `;
                        $('#updateSerpLog').append(summaryHtml);
                        return;
                    }

                    if (data.type !== 'summary') {
                        const logEntry = `
                    <div class="${logClass} text-sm py-1">
                        <i class="fas ${icon} mr-2"></i>
                        <span class="text-xs text-gray-500">${data.timestamp}</span> - ${data.message}
                    </div>
                `;
                        $('#updateSerpLog').append(logEntry);
                    }

                    // Auto scroll to bottom
                    const logContainer = document.getElementById('updateSerpLog');
                    logContainer.scrollTop = logContainer.scrollHeight;

                    // Check if done
                    if (data.type === 'done') {
                        eventSource.close();
                        updateSerpEventSource = null;
                        $('#startUpdateSerpBtn').removeClass('hidden');
                        $('#stopUpdateSerpBtn').addClass('hidden');
                        $('#closeUpdateSerpBtn').prop('disabled', false);
                        $('#closeUpdateSerpBtn2').prop('disabled', false);

                        // Show success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Update Selesai!',
                            text: data.message,
                            confirmButtonColor: '#9333ea'
                        });

                        // Reload table
                        $('#tourismTable').DataTable().ajax.reload();
                    }

                } catch (error) {
                    console.error('Error parsing SSE data:', error);
                }
            };

            eventSource.onerror = function(error) {
                console.error('EventSource error:', error);
                eventSource.close();
                updateSerpEventSource = null;

                $('#startUpdateSerpBtn').removeClass('hidden');
                $('#stopUpdateSerpBtn').addClass('hidden');
                $('#closeUpdateSerpBtn').prop('disabled', false);
                $('#closeUpdateSerpBtn2').prop('disabled', false);

                Swal.fire({
                    icon: 'error',
                    title: 'Update Gagal!',
                    text: 'Terjadi kesalahan saat mengupdate data. Silakan coba lagi.',
                    confirmButtonColor: '#ef4444'
                });
            };
        }

        // Stop update SerpAPI
        function stopUpdateSerp() {
            if (updateSerpEventSource) {
                updateSerpEventSource.close();
                updateSerpEventSource = null;

                $('#startUpdateSerpBtn').removeClass('hidden');
                $('#stopUpdateSerpBtn').addClass('hidden');
                $('#closeUpdateSerpBtn').prop('disabled', false);
                $('#closeUpdateSerpBtn2').prop('disabled', false);

                const logEntry = `
                    <div class="text-red-600 text-sm py-1 font-semibold">
                        <i class="fas fa-stop-circle mr-2"></i>
                        <span class="text-xs text-gray-500">${new Date().toLocaleTimeString('id-ID')}</span> - Update dihentikan oleh pengguna
                    </div>
                `;
                $('#updateSerpLog').append(logEntry);

                Toast.fire({
                    icon: 'warning',
                    title: 'Update dihentikan'
                });
            }
        }

        // Start import from API
        function startImport() {
            $('#importProgress').removeClass('hidden');
            $('#startImportBtn').addClass('hidden');
            $('#stopImportBtn').removeClass('hidden');
            $('#closeImportBtn').prop('disabled', true);
            $('#closeImportBtn2').prop('disabled', true);
            $('#importLog').empty();

            // Create EventSource for SSE
            importEventSource = new EventSource('{{ route('admin.tourism.import-api') }}');
            const eventSource = importEventSource;

            eventSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);

                    // Update progress bar
                    $('#importProgressBar').css('width', data.progress + '%');
                    $('#importProgressText').text(Math.round(data.progress) + '%');

                    // Add log entry
                    let logClass = 'text-gray-700';
                    let icon = 'fa-info-circle';

                    if (data.type === 'error') {
                        logClass = 'text-red-600';
                        icon = 'fa-times-circle';
                    } else if (data.type === 'success') {
                        logClass = 'text-green-600';
                        icon = 'fa-check-circle';
                    } else if (data.type === 'warning') {
                        logClass = 'text-yellow-600';
                        icon = 'fa-exclamation-circle';
                    } else if (data.type === 'summary') {
                        const summary = JSON.parse(data.message);
                        const summaryHtml = `
                    <div class="border-t pt-3 mt-3">
                        <h4 class="font-semibold text-gray-900 mb-2">Ringkasan Import:</h4>
                        <ul class="space-y-1 text-sm">
                            <li><i class="fas fa-plus-circle text-green-600"></i> Ditambahkan: <strong>${summary.imported}</strong></li>
                            <li><i class="fas fa-sync-alt text-blue-600"></i> Diperbarui: <strong>${summary.updated}</strong></li>
                            <li><i class="fas fa-ban text-yellow-600"></i> Dilewati: <strong>${summary.skipped}</strong></li>
                            <li><i class="fas fa-database text-gray-600"></i> Total Diproses: <strong>${summary.total_processed}</strong></li>
                            <li><i class="fas fa-exclamation-triangle text-red-600"></i> Error: <strong>${summary.errors_count}</strong></li>
                        </ul>
                        ${summary.errors_count > 0 ? `
                                <details class="mt-2">
                                    <summary class="cursor-pointer text-red-600 hover:text-red-700">Lihat Error Detail</summary>
                                    <ul class="mt-2 space-y-1 text-xs">
                                        ${summary.errors.map(e => `<li>• ${e.name}: ${e.error}</li>`).join('')}
                                    </ul>
                                </details>
                            ` : ''}
                    </div>
                `;
                        $('#importLog').append(summaryHtml);
                        return;
                    }

                    if (data.type !== 'summary') {
                        const logEntry = `
                    <div class="${logClass} text-sm py-1">
                        <i class="fas ${icon} mr-2"></i>
                        <span class="text-xs text-gray-500">${data.timestamp}</span> - ${data.message}
                    </div>
                `;
                        $('#importLog').append(logEntry);
                    }

                    // Auto scroll to bottom
                    const logContainer = document.getElementById('importLog');
                    logContainer.scrollTop = logContainer.scrollHeight;

                    // Check if done
                    if (data.type === 'done') {
                        eventSource.close();
                        importEventSource = null;
                        $('#startImportBtn').removeClass('hidden');
                        $('#stopImportBtn').addClass('hidden');
                        $('#closeImportBtn').prop('disabled', false);
                        $('#closeImportBtn2').prop('disabled', false);

                        // Show success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Import Selesai!',
                            text: data.message,
                            confirmButtonColor: '#3b82f6'
                        });

                        // Reload table
                        $('#tourismTable').DataTable().ajax.reload();
                    }

                } catch (error) {
                    console.error('Error parsing SSE data:', error);
                }
            };

            eventSource.onerror = function(error) {
                console.error('EventSource error:', error);
                eventSource.close();
                importEventSource = null;

                $('#startImportBtn').removeClass('hidden');
                $('#stopImportBtn').addClass('hidden');
                $('#closeImportBtn').prop('disabled', false);
                $('#closeImportBtn2').prop('disabled', false);

                Swal.fire({
                    icon: 'error',
                    title: 'Import Gagal!',
                    text: 'Terjadi kesalahan saat mengimport data. Silakan coba lagi.',
                    confirmButtonColor: '#ef4444'
                });
            };
        }

        // Stop import
        function stopImport() {
            if (importEventSource) {
                importEventSource.close();
                importEventSource = null;

                $('#startImportBtn').removeClass('hidden');
                $('#stopImportBtn').addClass('hidden');
                $('#closeImportBtn').prop('disabled', false);
                $('#closeImportBtn2').prop('disabled', false);

                const logEntry = `
                    <div class="text-red-600 text-sm py-1 font-semibold">
                        <i class="fas fa-stop-circle mr-2"></i>
                        <span class="text-xs text-gray-500">${new Date().toLocaleTimeString('id-ID')}</span> - Import dihentikan oleh pengguna
                    </div>
                `;
                $('#importLog').append(logEntry);

                Toast.fire({
                    icon: 'warning',
                    title: 'Import dihentikan'
                });
            }
        }
    </script>
@endpush
