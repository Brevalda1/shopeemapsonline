@extends('layouts.app')

@section('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5em 1em;
        margin-left: 2px;
    }
    .dataTables_wrapper .dataTables_length select {
        min-width: 60px;
    }
    .loading {
        opacity: 0.5;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Daftar Pengguna</h4>
                <a href="{{ route('pengguna.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pengguna
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="penggunaTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Telepon</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Tanggal Expired</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penggunas as $index => $pengguna)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pengguna->no_telp }}</td>
                                <td>{{ $pengguna->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $pengguna->role === 'admin' ? 'danger' : 'primary' }}">
                                        {{ $pengguna->role }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($pengguna->tanggal_exp)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pengguna.edit', $pengguna->no_telp) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('pengguna.destroy', $pengguna->no_telp) }}" 
                                              method="POST" 
                                              class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm"
                                                    data-name="{{ $pengguna->nama }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
<script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    // Inisialisasi DataTable
    const table = $('#penggunaTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: 5 } // Kolom aksi tidak bisa diurutkan
        ]
    });

    // Reindex nomor urut saat difilter
    table.on('order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    // Konfirmasi hapus dengan SweetAlert
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const name = $(this).find('button[type="submit"]').data('name');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus pengguna "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Auto hide alert
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);
});
</script>
@endsection