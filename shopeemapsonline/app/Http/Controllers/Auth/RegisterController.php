<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Menampilkan form register
    public function create()
    {
        return view('auth.register');
    }

    // Menyimpan data register
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_telp' => 'required|unique:pengguna,no_telp',
            'password' => 'required|min:6',
            'nama' => 'required'
        ]);

        // Tambahkan role 'pengguna' secara otomatis
        $validated['role'] = 'pengguna';
        
        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        Pengguna::create($validated);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }
} 