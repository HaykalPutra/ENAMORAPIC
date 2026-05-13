<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\DataPesananController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin2\DashboardAdmin2Controller;
use App\Http\Controllers\Admin2\PegawaiController;
use App\Http\Controllers\Admin2\FreelanceController;
use App\Http\Controllers\Admin2\PesananAdmin2Controller;
use App\Http\Controllers\Admin2\LaporanAdmin2Controller;
use App\Http\Controllers\Admin2\CustomerAdmin2Controller;
use App\Http\Controllers\Admin2\SearchAdmin2Controller;

/*
|--------------------------------------------------------------------------
| Web Routes — Enamorapic
|--------------------------------------------------------------------------
*/

// =============================================
// CUSTOMER / PUBLIC ROUTES
// =============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket/{paket:paket_id}', [HomeController::class, 'showPaket'])->name('paket.show');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');

// Payment Routes
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/show/{booking:booking_id}', [PaymentController::class, 'show'])->name('show');
    Route::post('/initiate/{booking:booking_id}', [PaymentController::class, 'initiate'])->name('initiate');
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/verify/{booking:booking_id}', [PaymentController::class, 'verify'])->name('verify');
    Route::post('/callback', [PaymentController::class, 'callback'])->name('callback');
    
    // Sandbox only
    Route::post('/test-mark-success/{booking:booking_id}', [PaymentController::class, 'testMarkSuccess'])->name('test-mark-success');
});

// =============================================
// AUTH ROUTES
// =============================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================
// ADMIN (CEO) ROUTES
// =============================================
Route::middleware(['auth', 'role:CEO'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Paket
    Route::resource('paket', PaketController::class);

    // Booking / Pesanan
    Route::get('/pesanan', [BookingAdminController::class, 'index'])->name('pesanan');
    Route::get('/pesanan/{id}', [BookingAdminController::class, 'show'])->name('pesanan.show');
    Route::put('/pesanan/{id}/status', [BookingAdminController::class, 'updateStatus'])->name('pesanan.status');
    Route::delete('/pesanan/{id}', [BookingAdminController::class, 'destroy'])->name('pesanan.destroy');

    // Pesanan Website (dari form publik)
    Route::get('/pesanan-website', [DataPesananController::class, 'index'])->name('pesanan-website');
    Route::put('/pesanan-website/{id}/validate', [DataPesananController::class, 'validate_pesanan'])->name('pesanan-website.validate');

    // Customer
    Route::resource('customer', CustomerController::class);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/invoice/{booking}', [LaporanController::class, 'invoice'])->name('laporan.invoice');
    Route::get('/laporan/invoice/{booking}/pdf', [LaporanController::class, 'invoicePdf'])->name('laporan.invoice.pdf');

    // Global Search + Profile (topbar)
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Import Data
    Route::get('/import', [ImportController::class, 'index'])->name('import');
    Route::post('/import', [ImportController::class, 'store'])->name('import.store');
});

// =============================================
// ADMIN2 (SEKRETARIS) ROUTES
// =============================================
Route::middleware(['auth', 'role:ADMIN,CEO'])->prefix('admin2')->name('admin2.')->group(function () {
    Route::get('/', [DashboardAdmin2Controller::class, 'index'])->name('dashboard');

    // Pegawai
    Route::resource('pegawai', PegawaiController::class);

    // Freelance
    Route::resource('freelance', FreelanceController::class);

    // Pesanan (lihat & proses dari admin2)
    Route::get('/pesanan', [PesananAdmin2Controller::class, 'index'])->name('pesanan');
    Route::get('/pesanan/{id}', [PesananAdmin2Controller::class, 'show'])->name('pesanan.show');
    Route::put('/pesanan/{id}/proses', [PesananAdmin2Controller::class, 'proses'])->name('pesanan.proses');

    // Customer (read-only + follow up termin)
    Route::get('/customer', [CustomerAdmin2Controller::class, 'index'])->name('customer.index');
    Route::get('/customer/{customer}', [CustomerAdmin2Controller::class, 'show'])->name('customer.show');
    Route::get('/customer/{customer}/booking/{booking}/terms', [CustomerAdmin2Controller::class, 'terms'])->name('customer.terms');

    // Laporan
    Route::get('/laporan', [LaporanAdmin2Controller::class, 'index'])->name('laporan');
    Route::get('/laporan/export-pdf', [LaporanAdmin2Controller::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/export-excel', [LaporanAdmin2Controller::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/invoice/{booking}', [LaporanAdmin2Controller::class, 'invoice'])->name('laporan.invoice');
    Route::get('/laporan/invoice/{booking}/pdf', [LaporanAdmin2Controller::class, 'invoicePdf'])->name('laporan.invoice.pdf');

    // Global Search
    Route::get('/search', [SearchAdmin2Controller::class, 'index'])->name('search');
});