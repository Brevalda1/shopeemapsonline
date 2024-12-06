<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class MembershipController extends Controller
{  
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getPaymentToken()
    {
        try {
            $no_telp = session('no_telp');
            
            // Check if user exists
            $user = DB::table('pengguna')
                    ->where('no_telp', $no_telp)
                    ->first();
                    
            if (!$user) {
                return response()->json([
                    'error' => 'Silakan login ulang'
                ], 401);
            }

            // Set order ID unik
            $orderId = 'MEMBER-' . time();
            
            // Set jumlah pembayaran
            $amount = 10000;

            $transactionData = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount
                ],
                'customer_details' => [
                    'first_name' => $user->nama,
                    'phone' => $user->no_telp
                ],
                'merchant_id' => 'G676673022' // Added Merchant ID
            ];

            // Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            // Simpan data ke session
            session([
                'pending_membership' => [
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'no_telp' => $user->no_telp
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

    public function handlePaymentSuccess(Request $request)
    {
        try {
            $pendingMembership = session('pending_membership');
            
            if (!$pendingMembership) {
                throw new \Exception('Data transaksi tidak ditemukan');
            }

            // Update tanggal expired user
            $user = DB::table('pengguna')
                ->where('no_telp', $pendingMembership['no_telp'])
                ->first();
                
            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            $currentExpDate = $user->tanggal_exp ? Carbon::parse($user->tanggal_exp) : Carbon::now();
            $newExpDate = $currentExpDate->greaterThan(Carbon::now()) 
                         ? $currentExpDate->addMonth() 
                         : Carbon::now()->addMonth();

            DB::table('pengguna')
                ->where('no_telp', $pendingMembership['no_telp'])
                ->update([
                    'tanggal_exp' => $newExpDate
                ]);

            session()->forget('pending_membership');

            return response()->json([
                'success' => true,
                'message' => 'Membership berhasil diperpanjang silahkan logout lalu login ulang'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function notificationHandler(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();
            
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    return response()->json(['success' => false]);
                } else if ($fraudStatus == 'accept') {
                    return $this->handlePaymentSuccess($request);
                }
            } else if ($transactionStatus == 'settlement') {
                return $this->handlePaymentSuccess($request);
            }

            return response()->json(['success' => false]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
