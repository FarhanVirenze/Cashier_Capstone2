<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- 1. RUTE GLOBAL (Admin & Kasir) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])
        ->name('dashboard.export.pdf');

    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])
        ->name('dashboard.export.excel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [PosController::class, 'add'])->name('pos.add');

    Route::get('/scanner', function () {
        return view('scanner');
    })->name('scanner.index');

    Route::get('/scan/check-stock/{product}', [ScanController::class, 'checkStock'])->name('scan.check-stock');
    Route::get('/scan/cart', [ScanController::class, 'cart']);
    Route::post('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/scan/update', [ScanController::class, 'update']);
    Route::post('/scan/add', [ScanController::class, 'add'])->name('scan.add');

    // Costumer
    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('customers.index');

    Route::get('/customers/create', [CustomerController::class, 'create'])
        ->name('customers.create');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->name('customers.store');

    Route::put('/customers/{id}', [CustomerController::class, 'update'])
        ->name('customers.update');

    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])
        ->name('customers.destroy');

    Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('/clear-print-session', function () {
        session()->forget('print_transaksi');

        return response()->json(['status' => 'success']);
    })->name('clear.print.session');
});

// --- 2. RUTE KHUSUS ADMIN (Kasir Dilarang Masuk) ---
Route::middleware(['auth', 'can:admin'])->group(function () {
    // Produk
    Route::resource('product', ProductController::class);
    Route::get('/product/{barcode}', [ProductController::class, 'showByBarcode']);

    // Manajemen User
    Route::resource('user', UserController::class);
    Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
    Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');

    // Laporan Transaksi Penjualan (HANYA DI SINI)
    Route::get('/transaksi', [TransaksiPenjualanController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/export-pdf', [TransaksiPenjualanController::class, 'exportPdf'])->name('transaksi.export-pdf');
    Route::get('/transaksi/export-excel', [TransaksiPenjualanController::class, 'exportExcel'])->name('transaksi.export-excel');
    Route::get('/transaksi/print', [TransaksiPenjualanController::class, 'print'])->name('transaksi.print');
    Route::get('/transaksi/cetak/{id}', [TransaksiPenjualanController::class, 'cetakTransaksi'])->name('transaksi.cetak');
    Route::get('/transaksi/data/{id}', [TransaksiPenjualanController::class, 'getTransaksiData'])->name('transaksi.data');
    Route::delete('/transaksi/{id}', [TransaksiPenjualanController::class, 'destroy'])->name('transaksi.destroy');

    // Detail Penjualan
    Route::get('/detail', [DetailPenjualanController::class, 'index'])->name('detailpenjualan.index');
    Route::delete('/detail/{id}', [DetailPenjualanController::class, 'destroy'])->name('detailpenjualan.destroy');
Route::get('detailpenjualan/export/pdf', [DetailPenjualanController::class, 'exportPdf'])->name('detailpenjualan.export.pdf');
Route::get('detailpenjualan/export/excel', [DetailPenjualanController::class, 'exportExcel'])->name('detailpenjualan.export.excel');

});

require __DIR__.'/auth.php';
