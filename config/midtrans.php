<?php

// return [
//     /*
    // |--------------------------------------------------------------------------
    // | Midtrans Configuration
    // |--------------------------------------------------------------------------
    // |
    // | Kamu bisa atur environment sandbox / production dari file .env
    // | MIDTRANS_IS_PRODUCTION=true  → untuk production
    // | MIDTRANS_IS_PRODUCTION=false → untuk sandbox
    // |
    // */

//     'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
//     'client_key'  => env('MIDTRANS_CLIENT_KEY', ''),
//     'server_key'  => env('MIDTRANS_SERVER_KEY', ''),

//     // mode: true untuk sandbox (testing), false untuk production
//     'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

//     // Atur notifikasi URL atau callback
//     'is_sanitized'  => true,
//     'is_3ds'        => true,
// ];

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
];


