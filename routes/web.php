<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bayar', [PaymentController::class, 'index']);
Route::get('/snap-token', [PaymentController::class, 'getSnapToken']);
Route::view('/status', 'status');
Route::get('/cek-status/{orderId}', [PaymentController::class, 'checkTransactionStatus']);