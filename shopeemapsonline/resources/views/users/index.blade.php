@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Pengguna</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('pengguna.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Telepon</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>tanggal expired</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penggunas as $index => $pengguna)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengguna->no_telp }}</td>
                        <td>{{ $pengguna->nama }}</td>
                        <td>{{ $pengguna->role }}</td>
                        <td>{{ $pengguna->tanggal_exp }}</td>
                        <td>
                            <a href="{{ route('pengguna.edit', $pengguna->no_telp) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pengguna.destroy', $pengguna->no_telp) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
