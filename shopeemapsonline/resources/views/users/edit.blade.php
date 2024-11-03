@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Pengguna</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pengguna.update', $pengguna->no_telp) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="no_telp">Nomor Telepon</label>
                <input type="text" name="no_telp" class="form-control" value="{{ $pengguna->no_telp }}" required readonly>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $pengguna->nama }}" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ $pengguna->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pengguna" {{ $pengguna->role == 'pengguna' ? 'selected' : '' }}>pengguna</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
