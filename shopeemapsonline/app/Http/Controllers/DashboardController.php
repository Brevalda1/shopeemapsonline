<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Cek apakah session 'nama' dan 'last_activity' ada
        if (!$request->session()->has('nama') || !$request->session()->has('last_activity')) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
    
        // Ambil waktu aktivitas terakhir dari session
        $lastActivity = $request->session()->get('last_activity');
        $currentTime = time();
    
        // Cek apakah sesi sudah melebihi batas waktu (misalnya 1 jam = 3600 detik)
        if (($currentTime - $lastActivity) > 3600) {
            // Jika sudah melebihi, logout pengguna dan redirect ke login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return redirect('/login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }
    
        // Update last_activity di session
        $request->session()->put('last_activity', $currentTime);
    
        $data = [
            'totalUsers' => 1234,
            'activeLocations' => 56,
            'totalVisits' => 89421,
            'defaultLocation' => [
                'lat' => -6.2088,
                'lng' => 106.8456,
                'zoom' => 13
            ],
            'nama' => $request->session()->get('nama')
        ];
    
        return view('pengguna.dashboard', $data);
    }
    
    public function adminDashboard(Request $request)
    {
        if (!$request->session()->has('nama') || !$request->session()->has('last_activity')) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
    
        $lastActivity = $request->session()->get('last_activity');
        $currentTime = time();
    
        if (($currentTime - $lastActivity) > 3600) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return redirect('/login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }
    
        $request->session()->put('last_activity', $currentTime);
    
        $data = [
            'totalUsers' => 1234,
            'activeLocations' => 56,
            'totalVisits' => 89421,
            'defaultLocation' => [
                'lat' => -6.2088,
                'lng' => 106.8456,
                'zoom' => 13
            ],
            'nama' => $request->session()->get('nama')
        ];
    
        return view('admin.dashboard', $data);
    }
    
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Get the current user's phone number from session
        $noTelp = session('no_telp');
        $user = Pengguna::where('no_telp', $noTelp)->first();
        // if ($request->fails()) {
        //     return redirect()->back()->withErrors($request);
        // }
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
