<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::all();
        return view('users.index', compact('penggunas'));
    }

    // Menampilkan form untuk menambah pengguna baru
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan data pengguna baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|unique:pengguna',
            'nama' => 'required',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        Pengguna::create([
            'no_telp' => $request->no_telp,
            'nama' => $request->nama,
            'password' => Hash::make($request->password), // Hash password sebelum disimpan
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('users.edit', compact('pengguna'));
    }

    // Memperbarui data pengguna
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_telp' => 'required|unique:pengguna,no_telp,' . $id,
            'nama' => 'required',
            'role' => 'required'
        ]);

        $pengguna = Pengguna::findOrFail($id);
        $pengguna->update([
            'no_telp' => $request->no_telp,
            'nama' => $request->nama,
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    // Menghapus data pengguna
    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }

}