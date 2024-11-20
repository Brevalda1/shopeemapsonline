<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'no_telp' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'no_telp' => $request->no_telp,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Cek tanggal expired
            if ($user->tanggal_exp && now()->startOfDay()->gt($user->tanggal_exp)) {
                Auth::logout();
                return back()->withErrors([
                    'no_telp' => 'Akun Anda telah kadaluarsa. Silakan hubungi admin.'
                ]);
            }

            $request->session()->regenerate();
            
            // Simpan data user ke session dengan waktu aktivitas terakhir
            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'no_telp' => $user->no_telp,
                'nama' => $user->nama,
                'role' => $user->role,
                'email' => $user->email,
                'tanggal_exp' => $user->tanggal_exp,
                'last_activity' => time()
            ]);

            // Set session lifetime ke 1 jam
            config(['session.lifetime' => 60]);

            if ($user->role === 'admin') {
                return redirect("/dashboardadmin")->with('success', 'Selamat datang Admin!');
            } else {
                return redirect("/dashboard")->with('success', 'Selamat datang!');
            }
        }

        return back()->withErrors([
            'no_telp' => 'Nomor telepon atau password salah'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        
        // Invalidate session dan regenerate CSRF token
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect('/')->with('success', 'Anda berhasil logout');
    }
}
