<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use Illuminate\Http\Request;

class PinController extends Controller
{
    public function index()
    {
        return Pin::all();
    }

    public function store(Request $request)
    {
        $pin = Pin::create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description
        ]);

        return response()->json($pin);
    }
    public function update(Request $request, $id)
    {
        try {
            $pin = Pin::findOrFail($id);
            $pin->update([
                'description' => $request->description
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pin berhasil diupdate',
                'data' => $pin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate pin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pin = Pin::findOrFail($id);
            $pin->delete();
            return response()->json([
                'success' => true,
                'message' => 'Pin berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pin: ' . $e->getMessage()
            ], 500);
        }
    }
}
