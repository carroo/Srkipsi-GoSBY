<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourism;
use App\Models\Category;
use App\Models\Facility;
use App\Models\TourismPrice;
use App\Models\TourismFile;
use App\Models\TourismHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class TourismController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tourism = Tourism::with(['categories', 'facilities', 'prices'])
                ->select(['id', 'name', 'rating']);

            return DataTables::of($tourism)
                ->addIndexColumn()
                ->addColumn('categories', function($row) {
                    if ($row->categories->isEmpty()) {
                        return '<span class="text-gray-400 text-sm">-</span>';
                    }
                    $badges = '';
                    foreach ($row->categories as $category) {
                        $badges .= '<span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs mr-1 mb-1">' . $category->name . '</span>';
                    }
                    return $badges;
                })
                ->addColumn('facilities', function($row) {
                    if ($row->facilities->isEmpty()) {
                        return '<span class="inline-block px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold mr-1 mb-1">0</span>';
                    }
                    $count = $row->facilities->count();
                    $badges = '<span class="inline-block px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold mr-1 mb-1">' . $count . '</span>';
                    
                    // Tampilkan max 2 fasilitas setelah badge angka
                    $facilities = $row->facilities->take(2);
                    foreach ($facilities as $facility) {
                        $badges .= '<span class="inline-block px-2 py-1 bg-green-50 text-green-600 rounded-full text-xs mr-1 mb-1">' . $facility->name . '</span>';
                    }
                    
                    if ($count > 2) {
                        $badges .= '<span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">+' . ($count - 2) . '</span>';
                    }
                    
                    return $badges;
                })
                ->addColumn('price_range', function($row) {
                    if ($row->prices->isEmpty()) {
                        return '<span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Tidak ada informasi / Gratis</span>';
                    }
                    $minPrice = $row->prices->min('price');
                    $maxPrice = $row->prices->max('price');
                    
                    // Jika harga 0, tampilkan badge "Gratis"
                    if ($maxPrice == 0) {
                        return '<span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">GRATIS</span>';
                    }
                    
                    if ($minPrice == $maxPrice) {
                        return '<span class="text-sm font-medium text-gray-700">Rp ' . number_format($minPrice, 0, ',', '.') . '</span>';
                    }
                    
                    // Jika min price 0 tapi ada harga lain
                    if ($minPrice == 0) {
                        return '<span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold mr-1">GRATIS</span><span class="text-sm font-medium text-gray-700"> - Rp ' . number_format($maxPrice, 0, ',', '.') . '</span>';
                    }
                    
                    return '<span class="text-sm font-medium text-gray-700">Rp ' . number_format($minPrice, 0, ',', '.') . ' - Rp ' . number_format($maxPrice, 0, ',', '.') . '</span>';
                })
                ->addColumn('action', function($row) {
                    $btn = '<div class="flex space-x-2 justify-center">';
                    $btn .= '<button onclick="viewTourism('.$row->id.')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs transition-colors duration-200" title="Lihat Detail">';
                    $btn .= '<i class="fas fa-eye"></i></button>';
                    $btn .= '<button onclick="editTourism('.$row->id.')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs transition-colors duration-200" title="Edit">';
                    $btn .= '<i class="fas fa-edit"></i></button>';
                    $btn .= '<button onclick="deleteTourism('.$row->id.')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs transition-colors duration-200" title="Hapus">';
                    $btn .= '<i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('rating', function($row) {
                    if (!$row->rating) {
                       $row->rating = 0;
                    }
                    $stars = '';
                    $fullStars = floor($row->rating);
                    $hasHalfStar = ($row->rating - $fullStars) >= 0.5;
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $fullStars) {
                            $stars .= '<i class="fas fa-star text-yellow-400 text-xs"></i>';
                        } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                            $stars .= '<i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>';
                        } else {
                            $stars .= '<i class="far fa-star text-gray-300 text-xs"></i>';
                        }
                    }
                    $stars .= ' <span class="text-sm font-medium text-gray-700 ml-1">' . number_format($row->rating, 1) . '</span>';
                    return $stars;
                })
                ->rawColumns(['categories', 'facilities', 'price_range', 'rating', 'action'])
                ->make(true);
        }

        $categories = Category::all();
        $facilities = Facility::all();
        
        return view('admin.tourism.index', compact('categories', 'facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'rating' => 'nullable|numeric|between:0,5',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:category,id',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facility,id',
            'prices' => 'nullable|array',
            'prices.*.type' => 'required_with:prices|string|max:100',
            'prices.*.price' => 'required_with:prices|numeric|min:0',
            'hours' => 'nullable|array',
            'hours.*.day' => 'required_with:hours|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'hours.*.open_time' => 'required_with:hours|date_format:H:i',
            'hours.*.close_time' => 'required_with:hours|date_format:H:i',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wisata harus diisi',
            'name.max' => 'Nama wisata maksimal 255 karakter',
            'latitude.between' => 'Latitude harus antara -90 dan 90',
            'longitude.between' => 'Longitude harus antara -180 dan 180',
            'email.email' => 'Format email tidak valid',
            'website.url' => 'Format website tidak valid',
            'rating.between' => 'Rating harus antara 0 dan 5',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create tourism
            $tourism = Tourism::create([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'rating' => $request->rating,
            ]);

            // Attach categories
            if ($request->has('categories')) {
                $tourism->categories()->attach($request->categories);
            }

            // Attach facilities
            if ($request->has('facilities')) {
                $tourism->facilities()->attach($request->facilities);
            }

            // Create prices
            if ($request->has('prices')) {
                foreach ($request->prices as $priceData) {
                    $tourism->prices()->create([
                        'type' => $priceData['type'],
                        'price' => $priceData['price'],
                    ]);
                }
            }

            // Create hours
            if ($request->has('hours')) {
                foreach ($request->hours as $hourData) {
                    $tourism->hours()->create([
                        'day' => $hourData['day'],
                        'open_time' => $hourData['open_time'],
                        'close_time' => $hourData['close_time'],
                    ]);
                }
            }

            // Upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('tourism', 'public');
                    $tourism->files()->create([
                        'file_path' => $path,
                        'file_type' => $image->getMimeType(),
                        'original_name' => $image->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wisata berhasil ditambahkan',
                'data' => $tourism->load(['categories', 'facilities', 'prices', 'hours', 'files'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan wisata: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $tourism = Tourism::with(['categories', 'facilities', 'prices', 'hours', 'files'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $tourism
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'rating' => 'nullable|numeric|between:0,5',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:category,id',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facility,id',
            'prices' => 'nullable|array',
            'prices.*.type' => 'required_with:prices|string|max:100',
            'prices.*.price' => 'required_with:prices|numeric|min:0',
            'hours' => 'nullable|array',
            'hours.*.day' => 'required_with:hours|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'hours.*.open_time' => 'required_with:hours|date_format:H:i',
            'hours.*.close_time' => 'required_with:hours|date_format:H:i',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:tourism_file,id',
        ], [
            'name.required' => 'Nama wisata harus diisi',
            'name.max' => 'Nama wisata maksimal 255 karakter',
            'latitude.between' => 'Latitude harus antara -90 dan 90',
            'longitude.between' => 'Longitude harus antara -180 dan 180',
            'email.email' => 'Format email tidak valid',
            'website.url' => 'Format website tidak valid',
            'rating.between' => 'Rating harus antara 0 dan 5',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $tourism = Tourism::findOrFail($id);

            // Update tourism
            $tourism->update([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'rating' => $request->rating,
            ]);

            // Sync categories
            if ($request->has('categories')) {
                $tourism->categories()->sync($request->categories);
            } else {
                $tourism->categories()->detach();
            }

            // Sync facilities
            if ($request->has('facilities')) {
                $tourism->facilities()->sync($request->facilities);
            } else {
                $tourism->facilities()->detach();
            }

            // Update prices - delete all and recreate
            $tourism->prices()->delete();
            if ($request->has('prices')) {
                foreach ($request->prices as $priceData) {
                    $tourism->prices()->create([
                        'type' => $priceData['type'],
                        'price' => $priceData['price'],
                    ]);
                }
            }

            // Update hours - delete all and recreate
            $tourism->hours()->delete();
            if ($request->has('hours')) {
                foreach ($request->hours as $hourData) {
                    $tourism->hours()->create([
                        'day' => $hourData['day'],
                        'open_time' => $hourData['open_time'],
                        'close_time' => $hourData['close_time'],
                    ]);
                }
            }

            // Delete selected images
            if ($request->has('delete_images')) {
                $filesToDelete = TourismFile::whereIn('id', $request->delete_images)
                    ->where('tourism_id', $id)
                    ->get();
                
                foreach ($filesToDelete as $file) {
                    Storage::disk('public')->delete($file->file_path);
                    $file->delete();
                }
            }

            // Upload new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('tourism', 'public');
                    $tourism->files()->create([
                        'file_path' => $path,
                        'file_type' => $image->getMimeType(),
                        'original_name' => $image->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wisata berhasil diperbarui',
                'data' => $tourism->load(['categories', 'facilities', 'prices', 'hours', 'files'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui wisata: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tourism = Tourism::findOrFail($id);

            // Delete all related files from storage
            foreach ($tourism->files as $file) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete tourism (cascade will handle related records based on migration)
            $tourism->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wisata berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus wisata: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import tourism data from external API with Server-Sent Events (SSE) for real-time progress
     */
    public function importFromApi(Request $request)
    {
        // Set headers for Server-Sent Events (SSE)
        return response()->stream(function () {
            // Disable output buffering
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set SSE headers
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no'); // Disable nginx buffering
            
            $this->sendSSE('info', 'Memulai proses import...', 0);
            
            try {
                // Get data from API
                $apiUrl = 'https://tourism.surabaya.go.id/api/travel-kit/map?type=destination&radius=1000&latitude=-7.2841&longitude=112.7541';
                
                $this->sendSSE('info', 'Mengambil data dari API eksternal...', 5);
                
                $response = Http::timeout(30)->get($apiUrl);

                if (!$response->successful()) {
                    $this->sendSSE('error', 'Gagal mengambil data dari API: ' . $response->status(), 0);
                    $this->sendSSE('done', 'Import gagal', 100);
                    return;
                }

                $apiData = $response->json();

                if (!isset($apiData['data']) || !is_array($apiData['data'])) {
                    $this->sendSSE('error', 'Format data API tidak valid', 0);
                    $this->sendSSE('done', 'Import gagal', 100);
                    return;
                }

                $totalData = count($apiData['data']);
                $this->sendSSE('info', "Data berhasil diambil. Total: {$totalData} wisata", 10);

                $imported = 0;
                $updated = 0;
                $skipped = 0;
                $errors = [];

                foreach ($apiData['data'] as $index => $apiTourism) {
                    $currentProgress = 10 + (($index + 1) / $totalData * 85);
                    
                    try {
                        // Get external ID
                        $externalId = $apiTourism['id'] ?? null;
                        
                        if (!$externalId) {
                            $skipped++;
                            $this->sendSSE('warning', "Item #" . ($index + 1) . ": Tidak ada ID eksternal, dilewati", $currentProgress);
                            continue;
                        }

                        // Get tourism name from language data
                        $tourismName = null;
                        if (!empty($apiTourism['touristDestinationLanguages'])) {
                            $tourismName = $apiTourism['touristDestinationLanguages'][0]['name'] ?? null;
                        }

                        if (!$tourismName) {
                            $skipped++;
                            $this->sendSSE('warning', "Item #" . ($index + 1) . ": Tidak ada nama, dilewati", $currentProgress);
                            continue;
                        }

                        // Get description (prefer Indonesian language)
                        $description = null;
                        if (!empty($apiTourism['touristDestinationLanguages'])) {
                            $description = $apiTourism['touristDestinationLanguages'][0]['description'] ?? null;
                        }

                        // Check if tourism already exists by external_id
                        $tourism = Tourism::where('external_id', $externalId)->first();
                        
                        $isUpdate = (bool)$tourism;
                        
                        DB::beginTransaction();

                        // Create or update tourism record
                        $tourismData = [
                            'name' => $tourismName,
                            'description' => $description,
                            'location' => $apiTourism['address'] ?? null,
                            'latitude' => $apiTourism['latitude'] ?? null,
                            'longitude' => $apiTourism['longitude'] ?? null,
                            'phone' => $apiTourism['contact'] ?? null,
                            'website' => $apiTourism['websiteLink'] ?? null,
                            'external_id' => $externalId,
                            'external_source' => 'tourism.surabaya.go.id',
                        ];

                        if ($isUpdate) {
                            // Update existing record
                            $tourism->update($tourismData);
                            $this->sendSSE('success', "Memperbarui: {$tourismName}", $currentProgress);
                            
                            // Clear existing relations for update
                            $tourism->categories()->detach();
                            $tourism->prices()->delete();
                            
                            // Delete old images if updating
                            foreach ($tourism->files as $file) {
                                Storage::disk('public')->delete($file->file_path);
                                $file->delete();
                            }
                        } else {
                            // Create new record
                            $tourism = Tourism::create($tourismData);
                            $this->sendSSE('success', "Menambahkan: {$tourismName}", $currentProgress);
                        }

                        // Import categories
                        if (!empty($apiTourism['tourismCategory'])) {
                            $categoryIds = [];
                            foreach ($apiTourism['tourismCategory'] as $apiCategory) {
                                $category = Category::firstOrCreate(
                                    ['name' => $apiCategory['name']],
                                    ['is_active' => true]
                                );
                                $categoryIds[] = $category->id;
                            }
                            if (!empty($categoryIds)) {
                                $tourism->categories()->attach($categoryIds);
                            }
                        }

                        // Import prices
                        if (!empty($apiTourism['touristDestinationTicketPrices'])) {
                            foreach ($apiTourism['touristDestinationTicketPrices'] as $apiPrice) {
                                $tourism->prices()->create([
                                    'type' => $apiPrice['name'] ?? 'Umum',
                                    'price' => $apiPrice['price'] ?? 0,
                                ]);
                            }
                        }

                        // Import images
                        if (!empty($apiTourism['touristDestinationFiles'])) {
                            foreach ($apiTourism['touristDestinationFiles'] as $apiFile) {
                                if (!empty($apiFile['link'])) {
                                    try {
                                        $imageResponse = Http::timeout(15)->get($apiFile['link']);
                                        
                                        if ($imageResponse->successful()) {
                                            $imageContent = $imageResponse->body();
                                            $extension = $apiFile['ext'] ?? 'jpg';
                                            $fileName = 'imported_' . time() . '_' . uniqid() . '.' . $extension;
                                            $path = 'tourism/' . $fileName;
                                            
                                            Storage::disk('public')->put($path, $imageContent);
                                            
                                            $tourism->files()->create([
                                                'file_path' => $path,
                                                'file_type' => 'image/' . $extension,
                                                'original_name' => $apiFile['name'] ?? $fileName,
                                            ]);
                                        }
                                    } catch (\Exception $e) {
                                        // Skip if image download fails
                                        continue;
                                    }
                                }
                            }
                        }

                        DB::commit();
                        
                        if ($isUpdate) {
                            $updated++;
                        } else {
                            $imported++;
                        }

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $errors[] = [
                            'name' => $tourismName ?? 'Unknown',
                            'error' => $e->getMessage()
                        ];
                        $this->sendSSE('error', "Gagal: " . ($tourismName ?? 'Unknown') . " - " . $e->getMessage(), $currentProgress);
                    }
                }

                // Send final summary
                $this->sendSSE('info', "Import selesai!", 95);
                $this->sendSSE('summary', json_encode([
                    'imported' => $imported,
                    'updated' => $updated,
                    'skipped' => $skipped,
                    'total_processed' => $totalData,
                    'errors_count' => count($errors),
                    'errors' => $errors
                ]), 100);
                
                $this->sendSSE('done', 'Import berhasil diselesaikan', 100);

            } catch (\Exception $e) {
                $this->sendSSE('error', 'Gagal mengimport data: ' . $e->getMessage(), 0);
                $this->sendSSE('done', 'Import gagal', 100);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Send Server-Sent Event
     */
    private function sendSSE($type, $message, $progress)
    {
        $data = [
            'type' => $type,
            'message' => $message,
            'progress' => round($progress, 2),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ];
        
        echo "data: " . json_encode($data) . "\n\n";
        
        if (ob_get_level()) {
            ob_flush();
        }
        flush();
    }
}

