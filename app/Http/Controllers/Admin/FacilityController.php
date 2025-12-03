<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $facilities = Facility::select(['id', 'name', 'description', 'created_at']);

            return DataTables::of($facilities)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<div class="flex space-x-2 justify-center">';
                    $btn .= '<button onclick="editFacility('.$row->id.')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs transition-colors duration-200" title="Edit">';
                    $btn .= '<i class="fas fa-edit"></i></button>';
                    $btn .= '<button onclick="deleteFacility('.$row->id.')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs transition-colors duration-200" title="Hapus">';
                    $btn .= '<i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.facilities.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:facility,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama fasilitas harus diisi',
            'name.unique' => 'Nama fasilitas sudah ada',
            'name.max' => 'Nama fasilitas maksimal 255 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $facility = Facility::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fasilitas berhasil ditambahkan',
                'data' => $facility
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $facility = Facility::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $facility
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:facility,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama fasilitas harus diisi',
            'name.unique' => 'Nama fasilitas sudah ada',
            'name.max' => 'Nama fasilitas maksimal 255 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $facility = Facility::findOrFail($id);
            $facility->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fasilitas berhasil diperbarui',
                'data' => $facility
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $facility = Facility::findOrFail($id);

            // Check if facility is being used
            if ($facility->tourisms()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas tidak dapat dihapus karena masih digunakan pada wisata'
                ], 422);
            }

            $facility->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fasilitas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }
}