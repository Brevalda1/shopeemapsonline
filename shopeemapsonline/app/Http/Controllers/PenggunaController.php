<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::all();
        return view('pengguna.index', compact('penggunas'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|unique:pengguna',
            'password' => 'required|min:6',
            'nama' => 'required',
            'role' => 'required|in:pengguna,admin',
        ]);

        Pengguna::create([
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'nama' => $request->nama,
            'role' => $request->role,
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->update($request->all());
        return redirect()->route('pengguna.show', $id);
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function show($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.show', compact('pengguna'));
    }
}
