<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Menampilkan form register
    public function create()
    {
        return view('auth.register');
    }

    // Menyimpan data register
    public function store(Request $request)
    {
        // Ambil data pending registration dari session
        $pendingRegistration = session('pending_registration');
        
        if (!$pendingRegistration) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Data registrasi tidak ditemukan']);
        }

        $validated = $pendingRegistration['validated_data'];
        
        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        
        // Set tanggal expired 1 tahun
        $validated['tanggal_exp'] = now()->addMonth();
        
        // Set role pengguna
        $validated['role'] = 'pengguna';

        // Simpan ke database
        Pengguna::create($validated);

        // Hapus data pending dari session
        session()->forget('pending_registration');

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Callback untuk notifikasi dari Midtrans
    public function notificationHandler(Request $request)
    {
        Log::info('Midtrans Notification:', $request->all());

        try {
            $notif = new \Midtrans\Notification();
            
            $transaction = $notif->transaction_status;
            $fraud = $notif->fraud_status;
            $order_id = $notif->order_id;

            Log::info('Transaction Status:', [
                'status' => $transaction,
                'fraud' => $fraud,
                'order_id' => $order_id
            ]);

            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    return response()->json(['success' => false, 'message' => 'Transaction challenged']);
                } else if ($fraud == 'accept') {
                    // Payment success, process the order
                    return $this->handlePaymentSuccess($request);
                }
            } else if ($transaction == 'settlement') {
                // Payment success, process the order
                return $this->handlePaymentSuccess($request);
            }

            return response()->json(['success' => false, 'message' => 'Invalid transaction status']);

        } catch (\Exception $e) {
            Log::error('Notification Handler Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function handlePaymentSuccess(Request $request)
    {
        Log::info('Payment Success Handler:', $request->all());

        try {
            // Ambil data registrasi dari session
            $pendingRegistration = session('pending_registration');
            
            if (!$pendingRegistration) {
                Log::error('Pending registration not found in session');
                return response()->json(['success' => false, 'message' => 'Data registrasi tidak ditemukan']);
            }

            $validated = $pendingRegistration['validated_data'];
            
            // Hash password
            $validated['password'] = Hash::make($validated['password']);
            
            // Set tanggal expired 1 tahun
            $validated['tanggal_exp'] = now()->addMonth();
            
            // Set role pengguna
            $validated['role'] = 'pengguna';

            // Simpan ke database
            $user = Pengguna::create([
                'nama' => $validated['nama'],
                'no_telp' => $validated['no_telp'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'tanggal_exp' => $validated['tanggal_exp']
            ]);

            Log::info('User berhasil dibuat:', $user->toArray());

            // Hapus data pending dari session
            session()->forget('pending_registration');

            return response()->json(['success' => true, 'message' => 'Registrasi berhasil']);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan data pengguna:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPaymentToken(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama' => 'required',
                'no_telp' => 'required|unique:pengguna,no_telp',
                'password' => 'required|min:6'
            ]);

            // Set order ID unik
            $orderId = 'REG-' . time();
            
            // Set jumlah pembayaran
            $amount = 10000; // Rp 10.000

            // Siapkan data transaksi
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => $amount
            ];

            $customerDetails = [
                'first_name' => $validated['nama'],
                'phone' => $validated['no_telp']
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'merchant_id' => 'G676673022' // Added Merchant ID here
            ];

            // Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            // Simpan data sementara ke session
            session([
                'pending_registration' => [
                    'validated_data' => $validated,
                    'order_id' => $orderId,
                    'amount' => $amount
                ]
            ]);

            return response()->json([
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 422);
        }
    }
}
