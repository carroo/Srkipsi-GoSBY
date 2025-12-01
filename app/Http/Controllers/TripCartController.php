<?php

namespace App\Http\Controllers;

use App\Models\TripCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripCartController extends Controller
{
    /**
     * Add tourism to trip cart
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $request->validate([
            'tourism_id' => 'required|exists:tourism,id'
        ]);

        try {
            $tripCart = TripCart::firstOrCreate([
                'user_id' => Auth::id(),
                'tourism_id' => $request->tourism_id
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Destinasi berhasil ditambahkan ke trip cart!',
                'data' => $tripCart
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menambahkan destinasi. Mungkin sudah ada di trip cart Anda.'
            ], 500);
        }
    }

    /**
     * Remove tourism from trip cart
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        TripCart::where('user_id', Auth::id())
            ->where('tourism_id', $id)
            ->delete();

        return back()->with('success', 'Destinasi berhasil dihapus dari trip cart!');
    }

    /**
     * View trip cart
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tripCart = TripCart::where('user_id', Auth::id())
            ->with('tourism')
            ->get();

        return view('trip-cart.index', compact('tripCart'));
    }
}
