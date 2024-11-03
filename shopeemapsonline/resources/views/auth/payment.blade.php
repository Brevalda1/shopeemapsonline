@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pembayaran Registrasi</div>

                <div class="card-body">
                    <p>Silakan selesaikan pembayaran untuk melanjutkan registrasi.</p>
                    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan Script Midtrans -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $snap_token }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route("login") }}';
            },
            onPending: function(result) {
                alert('Pembayaran pending, silakan selesaikan pembayaran');
            },
            onError: function(result) {
                alert('Pembayaran gagal');
            },
            onClose: function() {
                alert('Anda menutup popup pembayaran sebelum menyelesaikan pembayaran');
            }
        });
    };
</script>
@endsection 