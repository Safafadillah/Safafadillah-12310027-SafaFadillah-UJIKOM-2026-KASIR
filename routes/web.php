<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\User;

// landing
Route::get('/', function () {
    return view('landing');
});

// login
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ================= ADMIN =================
Route::middleware(['role:admin'])->prefix('admin')->group(function () {

Route::get('/dashboard', function () {

    $today = now()->toDateString();

    $totalHariIni = \App\Models\Pembelian::whereDate('created_at', $today)
        ->sum('total_harga');

    return view('admin.dashboard', [
        'productsCount' => Product::count(),
        'usersCount' => User::count(),
        'totalHariIni' => $totalHariIni // ✅ tambahan
    ]);
});

    // ================= PRODUK =================
    Route::get('/produk', [ProductController::class, 'index']);
    Route::get('/produk/create', [ProductController::class, 'create']);
    Route::post('/produk', [ProductController::class, 'store']);
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/produk/{id}', [ProductController::class, 'update']);
    Route::delete('/produk/{id}', [ProductController::class, 'destroy']);
    Route::post('/produk/{id}/update-stock', [ProductController::class, 'updateStock']);


    // ================= PEMBELIAN =================
    Route::get('/pembelian', [PembelianController::class, 'index'])
        ->name('admin.pembelian.index');
    Route::get('/pembelian/export/excel', [PembelianController::class, 'exportExcel'])
        ->name('admin.pembelian.export');
    Route::get('/pembelian/{id}/detail', [PembelianController::class, 'detail']);
    Route::get('/pembelian/{id}', [PembelianController::class, 'show'])
        ->name('admin.pembelian.show');

    // ================= data pengguna =================
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/create', [UserController::class, 'create']);
    Route::post('/user', [UserController::class, 'store']);
    Route::get('/user/{id}/edit', [UserController::class, 'edit']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
});


// ================= PETUGAS =================

Route::middleware(['role:petugas'])->prefix('petugas')->group(function () {

    Route::get('/dashboard', function () {

        $today = now()->toDateString();

        $totalHariIni = \App\Models\Pembelian::whereDate('created_at', $today)
            ->sum('total_harga');

        return view('petugas.dashboard', compact('totalHariIni'));
    });

    Route::get('/produk', [ProductController::class, 'indexPetugas']);

    Route::get('/pembelian', [PembelianController::class, 'indexPetugas']);

    Route::get('/pembelian/create', [PembelianController::class, 'createPetugas'])
        ->name('petugas.pembelian.create');

    Route::post('/pembelian/penjualan', [PembelianController::class, 'storePetugas'])
        ->name('petugas.pembelian.store');

    Route::post('/pembelian/simpan', [PembelianController::class, 'simpan'])
        ->name('petugas.pembelian.simpan');

    Route::get('/pembelian/struk/{id}', function ($id) {
        $pembelian = \App\Models\Pembelian::with('details.product')->findOrFail($id);
        return view('petugas.pembelian.struk', compact('pembelian'));
    })->name('petugas.pembelian.struk');

    // ✅ CEK MEMBER (FINAL)
    Route::get('/cek-member/{nohp}', function ($nohp) {

        $member = \App\Models\Pembelian::where('no_telp', $nohp)->latest()->first();

        if ($member) {

            $poin =
                \App\Models\Pembelian::where('no_telp', $nohp)->sum('poin_didapat')
                - \App\Models\Pembelian::where('no_telp', $nohp)->sum('poin_dipakai');

            return response()->json([
                'status' => 'lama',
                'nama' => $member->nama_pelanggan,
                'poin' => max($poin, 0) // ✅ FIX DISINI
            ]);
        }

        return response()->json([
            'status' => 'baru'
        ]);
    });

    Route::get('/pembelian/export', [PembelianController::class, 'exportExcelPetugas'])
        ->name('petugas.pembelian.export');

    Route::get('/pembelian/{id}/detail', function ($id) {

        $p = \App\Models\Pembelian::with('details.product')->findOrFail($id);

        return view('petugas.pembelian.detail', compact('p'));
    });

    Route::get('/petugas/pembelian/pdf/{id}', [PembelianController::class, 'downloadPdf'])
    ->name('petugas.pembelian.pdf');
});
