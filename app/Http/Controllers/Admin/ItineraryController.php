<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    /**
     * Display list of all itineraries
     */
    public function index()
    {
        // Get all itineraries with user and details
        $itineraries = Itinerary::with(['user', 'details', 'startPoint'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.itinerary.index', compact('itineraries'));
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

            return redirect()->route('admin.itinerary.index')
                ->with('success', 'Itinerary berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus itinerary');
        }
    }
}
