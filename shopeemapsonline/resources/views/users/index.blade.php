@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Pengguna</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Telepon</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penggunas as $pengguna)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pengguna->no_telp }}</td>
                        <td>{{ $pengguna->nama }}</td>
                        <td>{{ $pengguna->role }}</td>
                        <td>
                            {{-- <a href="{{ route('user.edit', $pengguna->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}

                            {{-- <form action="{{ route('users.destroy', $pengguna->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
