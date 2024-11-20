<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Session::has('role') || Session::get('role') !== 'admin') {
                return redirect('/login')->with('error', 'Hanya admin yang dapat mengakses halaman ini.');
            }
            return $next($request);
        });
    }

    // Menampilkan daftar pengguna
    public function index(Request $request)
    {
        // Double check untuk keamanan tambahan
        if (!Session::get('role') === 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access');
        }

        $penggunas = DB::table('pengguna')->get();
        return view('users.index', compact('penggunas'));
    }

    // Menampilkan form untuk menambah pengguna baru
    public function create(Request $request)
    {
        if (!Session::get('role') === 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access');
        }

        return view('users.create');
    }

    // Menyimpan data pengguna baru
    public function store(Request $request)
    {
        if (!Session::get('role') === 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'no_telp' => 'required|unique:pengguna',
            'nama' => 'required',
            'password' => 'required|min:6',
            'role' => 'required',
            'tanggal_exp' => 'required|date|after:today'
        ]);

        try {
            DB::table('pengguna')->insert([
                'no_telp' => $request->no_telp,
                'nama' => $request->nama,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'tanggal_exp' => $request->tanggal_exp
            ]);

            return redirect('/pengguna')->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit(Request $request, $id)
    {
        if (!Session::get('role') === 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access');
        }

        $pengguna = DB::table('pengguna')->where('no_telp', $id)->first();
        if (!$pengguna) {
            return redirect('/pengguna')->with('error', 'Pengguna tidak ditemukan');
        }

        return view('users.edit', compact('pengguna'));
    }

    // Memperbarui data pengguna
    public function update(Request $request, $no_telp)
    {
        if (!Session::get('role') === 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'no_telp' => 'required|unique:pengguna,no_telp,'.$no_telp.',no_telp',
            'nama' => 'required',
            'role' => 'required',
            'tanggal_exp' => 'required|date'
        ]);
    
        try {
            DB::table('pengguna')
                ->where('no_telp', $no_telp)
                ->update([
                    'no_telp' => $request->no_telp,
                    'nama' => $request->nama,
                    'role' => $request->role,
                    'tanggal_exp' => $request->tanggal_exp
                ]);

            return redirect('/pengguna')->with('success', 'Pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    // Menghapus data pengguna
    public function destroy(Request $request, $no_telp)
    {
        if (!Session::get('role') === 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access');
        }

        try {
            // Prevent admin from deleting their own account
            if ($no_telp === Session::get('no_telp')) {
                return back()->with('error', 'Tidak dapat menghapus akun sendiri');
            }

            DB::table('pengguna')->where('no_telp', $no_telp)->delete();
            return redirect('/pengguna')->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
