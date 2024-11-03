<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        $penggunas = DB::table('pengguna')->get();
        return view('users.index', compact('penggunas'));
    }

    // Menampilkan form untuk menambah pengguna baru
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan data pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|unique:pengguna',
            'nama' => 'required',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        DB::table('pengguna')->insert([
            'no_telp' => $request->no_telp,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/pengguna')->with('success', 'Pengguna berhasil diperbarui');
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit($id)
    {
        $pengguna = DB::table('pengguna')->where('no_telp', $id)->first();
        return view('users.edit', compact('pengguna'));
    }

    // Memperbarui data pengguna
    public function update(Request $request, $no_telp)
{
    $request->validate([
        'no_telp' => 'required|unique:pengguna,no_telp,'.$no_telp.',no_telp',
        'nama' => 'required',
        'role' => 'required'
    ]);

    DB::table('pengguna')
        ->where('no_telp', $no_telp)
        ->update([
            'no_telp' => $request->no_telp,
            'nama' => $request->nama,
            'role' => $request->role,
        ]);

        return redirect('/pengguna')->with('success', 'Pengguna berhasil diperbarui');
}

    // Menghapus data pengguna
    public function destroy($no_telp)
    {
        DB::table('pengguna')->where('no_telp', $no_telp)->delete();
        return redirect('/pengguna')->with('success', 'Pengguna berhasil dihapus');
    }
}
