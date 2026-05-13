<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController; // Baris ini penting untuk memanggil controller

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// === RUTE MIDTRANS WEBHOOK ===
// Pastikan nama '/midtrans-webhook' ini SAMA PERSIS dengan 
// ujung URL yang kamu masukkan di dashboard Midtrans tadi.
Route::post('/midtrans-webhook', [PaymentController::class, 'handleWebhook']);