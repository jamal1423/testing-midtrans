<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        return view('checkout');
    }

    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Jamal',
                'email' => 'jamal@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
    }

    public function checkTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);

            $transactionStatus = $status->transaction_status;
            $fraudStatus       = $status->fraud_status ?? null;
            $paymentType       = $status->payment_type ?? '-';

            $statusText = match ($transactionStatus) {
                'capture'     => $fraudStatus == 'challenge' ? 'Perlu verifikasi manual' : 'Transaksi berhasil',
                'settlement'  => 'Transaksi berhasil dan selesai',
                'pending'     => 'Menunggu pembayaran',
                'deny'        => 'Ditolak',
                'expire'      => 'Kedaluwarsa',
                'cancel'      => 'Dibatalkan',
                default       => 'Status tidak dikenal',
            };

            return response()->json([
                'order_id'       => $orderId,
                'status'         => $transactionStatus,
                'fraud'          => $fraudStatus,
                'payment_type'   => $paymentType,
                'message'        => $statusText,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil status transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
