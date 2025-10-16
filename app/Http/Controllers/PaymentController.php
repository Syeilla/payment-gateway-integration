<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // 🔹 Buat QRIS baru (langsung ke Core API)
    public function createPayment($product_id)
    {
        // Simulasi harga berdasarkan produk
        $amount = match ($product_id) {
            1 => 10000,
            2 => 15000,
            default => 20000,
        };

        $orderId = 'ORDER-' . rand(1000, 9999);

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
        ];

        $response = CoreApi::charge($params);

        // QRIS URL dari Midtrans Core API
        $qrisUrl = $response->actions[0]->url ?? null;

        // Simulator pembayaran (sandbox)
        $simulatorUrl = 'https://simulator.sandbox.midtrans.com/qris/index';

        return view('payment', [
            'order_id' => $orderId,
            'qris_url' => $qrisUrl,
            'simulator_url' => $simulatorUrl,
        ]);
    }

    // 🔹 Cek status pembayaran
    public function checkStatus($order_id)
    {
        try {
            $status = Transaction::status($order_id);
            return response()->json([
                'status' => $status->transaction_status ?? 'unknown',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    // 🔹 Opsional: tampilan awal list produk
    public function index()
    {
        return view('payment');
    }
}
