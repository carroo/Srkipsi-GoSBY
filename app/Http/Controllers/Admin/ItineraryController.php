<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ItineraryController extends Controller
{
    /**
     * Display list of all itineraries
     */
    public function index(Request $request)
    {
        // Check if request is AJAX for DataTable
        if ($request->ajax()) {
            $itineraries = Itinerary::with(['user', 'details'])
                ->select('itineraries.id', 'itineraries.name', 'itineraries.travel_date', 'itineraries.total_distance', 'itineraries.created_at', 'itineraries.user_id');

            return DataTables::of($itineraries)
                ->addIndexColumn()
                ->addColumn('user_name', function($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('destination_count', function($row) {
                    return $row->details->count();
                })
                ->editColumn('travel_date', function($row) {
                    return Carbon::parse($row->travel_date)->format('d M Y');
                })
                ->editColumn('total_distance', function($row) {
                    return number_format($row->total_distance / 1000, 1) . ' km';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M Y H:i');
                })
                ->addColumn('action', function($row) {
                    $btn = '<div class="flex space-x-2 justify-center">';
                    $btn .= '<a href="' . route('itinerary.result', $row->id) . '" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs transition-colors duration-200" title="Lihat Detail">';
                    $btn .= '<i class="fas fa-eye"></i></a>';
                    $btn .= '<button onclick="deleteItinerary(' . $row->id . ')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs transition-colors duration-200" title="Hapus">';
                    $btn .= '<i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Return view for non-AJAX requests
        return view('admin.itinerary.index');
    }

    /**
     * Delete itinerary
     */
    public function destroy($id)
    {
        try {
            $itinerary = Itinerary::findOrFail($id);

            // Delete related details first
            $itinerary->details()->delete();

            // Delete itinerary
            $itinerary->delete();

            return response()->json([
                'success' => true,
                'message' => 'Itinerary berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus itinerary: ' . $e->getMessage()
            ], 500);
        }
    }
}
