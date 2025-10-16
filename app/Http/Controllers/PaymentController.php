<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class PaymentController
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function payment(Request $request)
    {
        $product_id = $request->query('product_id', 1);
        $price = $request->query('price', 10000);
        $orderId = 'ORDER-' . rand(1000, 9999);

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
        ];

        try {
            // Log request
            Log::info('Midtrans Charge Request: ' . json_encode($params));

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':' . config('midtrans.merchant_id')),
            ])->post(config('midtrans.base_url') . '/v2/charge', $params);

            // log response
            Log::info('Midtrans Charge Response: ' . $response->body());

            $data = $response->json();
            $qrisUrl = null;
            $deeplinkUrl = null;
            $qrString = $data['qr_string'] ?? null;

            foreach ($data['actions'] as $action) {
                if ($action['name'] === 'generate-qr-code-v2') {
                    $qrisUrl = $action['url'];
                }
                if ($action['name'] === 'deeplink-redirect') {
                    $deeplinkUrl = $action['url'];
                }
            }

            // If no deeplink from Midtrans, create one from QR string for GoPay
            // Note: This is a workaround for sandbox environment
            if (!$deeplinkUrl && $qrString && $data['acquirer'] === 'gopay') {
                // Generate GoPay deeplink format
                $deeplinkUrl = $this->generateDeeplinkFromQRString($qrString, 'gopay');
            }

            $error = null;
        } catch (\Exception $e) {
            $qrisUrl = null;
            $error = $e->getMessage();
        }

        return view('payment', [
            'order_id' => $orderId,
            'qris_url' => $qrisUrl,
            'deeplink_url' => $deeplinkUrl,
            'simulator_url' => isset($data['actions']) ? $this->getSimulatorUrl($data['actions']) : null,
            'product_id' => $product_id,
            'price' => $price,
            'error' => $error,
        ]);
    }

    // Helper method to get simulator URL from actions
    private function getSimulatorUrl($actions)
    {
        foreach ($actions as $action) {
            if (isset($action['name']) && $action['name'] === 'deeplink-redirect') {
                return $action['url'] ?? null;
            }
        }
        return null;
    }

    // Helper method to generate deeplink from QR string
    // Note: This is a workaround for sandbox environment where deeplinks aren't provided
    private function generateDeeplinkFromQRString($qrString, $acquirer = 'gopay')
    {
        if (!$qrString) {
            return null;
        }

        // URL encode the QR string
        $encodedQR = urlencode($qrString);

        // Generate deeplink based on acquirer
        switch (strtolower($acquirer)) {
            case 'gopay':
                // GoPay deeplink format (for testing purposes)
                // In production, Midtrans provides this automatically
                return "gojek://gopay/qr?payload=" . $encodedQR;
            
            case 'shopeepay':
                return "shopeepay://qr?payload=" . $encodedQR;
            
            case 'ovo':
                return "ovo://qrcode?payload=" . $encodedQR;
            
            case 'dana':
                return "dana://qr?payload=" . $encodedQR;
            
            default:
                // Generic QRIS deeplink
                return "qris://scan?payload=" . $encodedQR;
        }
    }

    // 🔹 Cek status pembayaran
    public function checkStatus($order_id)
    {
        try {
            // Log request
            Log::info('Midtrans Cek Status Request: ' . $order_id);

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':'),
            ])->get(config('midtrans.base_url') . '/v2/' . $order_id . '/status');

            // log response
            Log::info('Midtrans Cek Status Response: ' . $response->body());

            $data = $response->json();

            return response()->json([
                'success' => true,
                'transaction_status' => $data['transaction_status'] ?? 'unknown',
                'fraud_status' => $data['fraud_status'] ?? null,
                'status_code' => $data['status_code'] ?? null,
                'order_id' => $data['order_id'] ?? $order_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Cek Status Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // 🔹 Halaman status pembayaran
    public function index()
    {
        $products = config('products.list');
        return view('prepayment', compact('products'));
    }
}
