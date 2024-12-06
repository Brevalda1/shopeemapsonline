@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                    id="nama" name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_telp" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                                    id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required>
                                @error('no_telp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <h5 class="mb-2">Biaya Registrasi: Rp 10.000</h5>
                                    <p class="mb-0">Silakan lakukan pembayaran untuk menyelesaikan registrasi</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" id="pay-button" class="btn btn-success btn-block w-100 mb-2">
                                    <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                </button>
                                <button type="submit" class="btn btn-primary btn-block w-100" style="display: none;" id="submit-form">
                                    Register
                                </button>
                            </div>

                            <div class="mb-0">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}">Login di sini</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan Script Midtrans -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function() {
            // Ambil data form
            const nama = document.getElementById('nama').value;
            const noTelp = document.getElementById('no_telp').value;
            const password = document.getElementById('password').value;

            // Validasi form sederhana
            if (!nama || !noTelp || !password) {
                alert('Mohon lengkapi semua data terlebih dahulu');
                return;
            }

            // Tampilkan loading
            const payButton = document.getElementById('pay-button');
            payButton.disabled = true;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            // Kirim data untuk mendapatkan snap token
            fetch('{{ route("payment.token") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nama: nama,
                    no_telp: noTelp,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    payButton.disabled = false;
                    payButton.innerHTML = 'Bayar Sekarang';
                    return;
                }

                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Registrasi...';
                        
                        // Kirim data ke endpoint success handler
                        fetch('{{ route("payment.success") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(result)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = '{{ route("login") }}?status=success';
                            } else {
                                alert(data.message || 'Terjadi kesalahan saat memproses registrasi');
                                payButton.disabled = false;
                                payButton.innerHTML = 'Bayar Sekarang';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses registrasi');
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang';
                        });
                    },
                    onPending: function(result) {
                        alert('Pembayaran pending, silakan selesaikan pembayaran');
                        payButton.disabled = false;
                        payButton.innerHTML = 'Bayar Sekarang';
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal');
                        payButton.disabled = false;
                        payButton.innerHTML = 'Bayar Sekarang';
                    },
                    onClose: function() {
                        payButton.disabled = false;
                        payButton.innerHTML = 'Bayar Sekarang';
                        alert('Anda menutup popup pembayaran sebelum menyelesaikan pembayaran');
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses pembayaran');
                payButton.disabled = false;
                payButton.innerHTML = 'Bayar Sekarang';
            });
        };
    </script>
@endsection
