@extends('layouts.app')

@section('content')
    <h1>Daftar Pengguna</h1>
    <a href="{{ route('pengguna.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor Telepon</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengguna as $user)
                <tr>
                    <td>{{ $user->no_telp }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('pengguna.edit', $user->no_telp) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pengguna.destroy', $user->no_telp) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
