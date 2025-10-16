<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('prepayment');
});

Route::get('/prepayment', function () {
    return view('payment');
});

// Route::get('/payment', function () {
//     return view('payment');
// });

Route::get('/payment', [PaymentController::class, 'index']);
Route::post('/create-payment', [PaymentController::class, 'createPayment']);
Route::get('/status', [PaymentController::class, 'status']);
