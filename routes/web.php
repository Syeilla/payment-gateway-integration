<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', [PaymentController::class, 'index']);

Route::get('/payment', [PaymentController::class, 'payment'])->name('payment');

Route::get('/check-status/{order_id}', [PaymentController::class, 'checkStatus']);
