<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriObatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
// ⬇️⬇️⬇️ TAMBAHKAN IMPORT INI ⬇️⬇️⬇️
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// --------------------
// Authenticated Routes
// --------------------
Route::middleware(['auth'])->group(function () {

    // ========================================
    // 🛒 SHOP ROUTES (UNTUK PELANGGAN)
    // ========================================
    Route::prefix('shop')->name('shop.')->group(function () {
        // Katalog Obat
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::get('/obat/{id}', [ShopController::class, 'show'])->name('show');
        
        // Keranjang Belanja
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
        
        // Checkout & Order
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/order', [OrderController::class, 'store'])->name('order.store');
        
        // Riwayat Pesanan Pelanggan
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    });

    // ========================================
    // 📊 DASHBOARD (UNTUK ADMIN/APOTEKER)
    // ========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --------------------
    // Penjualan Routes
    // --------------------
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        // Hapus semua
        Route::delete('/destroy-all', [PenjualanController::class, 'destroyAll'])
            ->middleware('permission:delete,penjualan')->name('destroyAll');

        // AJAX Routes
        Route::get('/get-obat', [PenjualanController::class, 'getObat'])->name('getObat');
        Route::get('/get-pelanggan', [PenjualanController::class, 'getPelanggan'])->name('getPelanggan');
        Route::get('/get-harga-obat', [PenjualanController::class, 'getHargaObat'])->name('getHargaObat');
        Route::get('/check-stok', [PenjualanController::class, 'checkStok'])->name('checkStok');
        Route::get('/cetak/{id}', [PenjualanController::class, 'cetak'])->name('cetak');

        // CRUD Routes
        Route::get('/', [PenjualanController::class, 'index'])
            ->middleware('permission:detail,penjualan')->name('index');
        Route::get('/create', [PenjualanController::class, 'create'])
            ->middleware('permission:create,penjualan')->name('create');
        Route::post('/', [PenjualanController::class, 'store'])
            ->middleware('permission:create,penjualan')->name('store');
        Route::get('/laporan', [PenjualanController::class, 'laporan'])
            ->middleware('permission:detail,penjualan')->name('laporan');
        Route::get('/{id}', [PenjualanController::class, 'show'])
            ->middleware('permission:detail,penjualan')->name('show');
        Route::get('/{id}/edit', [PenjualanController::class, 'edit'])
            ->middleware('permission:update,penjualan')->name('edit');
        Route::put('/{id}', [PenjualanController::class, 'update'])
            ->middleware('permission:update,penjualan')->name('update');
        Route::delete('/{id}', [PenjualanController::class, 'destroy'])
            ->middleware('permission:delete,penjualan')->name('destroy');
    });

    // --------------------
    // Pembelian Routes
    // --------------------
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        // Hapus semua
        Route::delete('/destroy-all', [PembelianController::class, 'destroyAll'])
            ->middleware('permission:delete,pembelian')->name('destroyAll');

        // AJAX Routes
        Route::get('/get-obat', [PembelianController::class, 'getObat'])->name('getObat');
        Route::get('/get-supplier', [PembelianController::class, 'getSupplier'])->name('getSupplier');
        Route::get('/get-supplier-detail', [PembelianController::class, 'getSupplierDetail'])->name('getSupplierDetail');
        Route::get('/cetak/{id}', [PembelianController::class, 'cetak'])->name('cetak');

        // CRUD Routes
        Route::get('/', [PembelianController::class, 'index'])
            ->middleware('permission:detail,pembelian')->name('index');
        Route::get('/create', [PembelianController::class, 'create'])
            ->middleware('permission:create,pembelian')->name('create');
        Route::post('/', [PembelianController::class, 'store'])
            ->middleware('permission:create,pembelian')->name('store');
        Route::get('/laporan', [PembelianController::class, 'laporan'])
            ->middleware('permission:detail,pembelian')->name('laporan');
        Route::get('/{id}', [PembelianController::class, 'show'])
            ->middleware('permission:detail,pembelian')->name('show');
        Route::get('/{id}/edit', [PembelianController::class, 'edit'])
            ->middleware('permission:update,pembelian')->name('edit');
        Route::put('/{id}', [PembelianController::class, 'update'])
            ->middleware('permission:update,pembelian')->name('update');
        Route::delete('/{id}', [PembelianController::class, 'destroy'])
            ->middleware('permission:delete,pembelian')->name('destroy');
    });

    // --------------------
    // Obat Routes
    // --------------------
    Route::prefix('obat')->name('obat.')->group(function () {
        // Hapus semua
        Route::delete('/destroy-all', [ObatController::class, 'destroyAll'])->name('destroyAll');

        // AJAX Routes
        Route::get('/get-kategori', [ObatController::class, 'getKategori'])->name('getKategori');
        Route::get('/check-kode', [ObatController::class, 'checkKode'])->name('checkKode');
        Route::get('/search', [ObatController::class, 'search'])->name('search');
        Route::get('/data', [ObatController::class, 'data'])->name('data');

        // Stok & Kadaluarsa
        Route::get('/stok', [ObatController::class, 'stok'])->name('stok');
        Route::get('/kadaluarsa', [ObatController::class, 'kadaluarsa'])->name('kadaluarsa');
        Route::get('/stok-minim', [ObatController::class, 'stokMinim'])->name('stokMinim');

        // CRUD Routes
        Route::get('/', [ObatController::class, 'index'])->name('index');
        Route::get('/create', [ObatController::class, 'create'])->name('create');
        Route::post('/', [ObatController::class, 'store'])->name('store');
        Route::get('/{id}', [ObatController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ObatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ObatController::class, 'update'])->name('update');
        Route::delete('/{id}', [ObatController::class, 'destroy'])->name('destroy');
    });

    // --------------------
    // Kategori Obat Routes
    // --------------------
    Route::prefix('kategori-obat')->name('kategori-obat.')->group(function () {
        // Hapus semua
        Route::delete('/destroy-all', [KategoriObatController::class, 'destroyAll'])
            ->middleware('permission:delete,kategori_obat')->name('destroyAll');

        // AJAX Routes
        Route::get('/data', [KategoriObatController::class, 'data'])->name('data');
        Route::get('/check-kode', [KategoriObatController::class, 'checkKode'])->name('checkKode');

        // CRUD Routes
        Route::get('/', [KategoriObatController::class, 'index'])
            ->middleware('permission:detail,kategori_obat')->name('index');
        Route::get('/create', [KategoriObatController::class, 'create'])
            ->middleware('permission:add,kategori_obat')->name('create');
        Route::post('/', [KategoriObatController::class, 'store'])
            ->middleware('permission:create,kategori_obat')->name('store');
        Route::get('/{id}', [KategoriObatController::class, 'show'])
            ->middleware('permission:detail,kategori_obat')->name('show');
        Route::get('/{id}/edit', [KategoriObatController::class, 'edit'])
            ->middleware('permission:update,kategori_obat')->name('edit');
        Route::put('/{id}', [KategoriObatController::class, 'update'])
            ->middleware('permission:update,kategori_obat')->name('update');
        Route::delete('/{id}', [KategoriObatController::class, 'destroy'])
            ->middleware('permission:delete,kategori_obat')->name('destroy');
    });

    // --------------------
    // Supplier Routes
    // --------------------
    Route::prefix('supplier')->name('supplier.')->group(function () {
        // Hapus semua
        Route::delete('/destroy-all', [SupplierController::class, 'destroyAll'])
            ->middleware('permission:delete,supplier')->name('destroyAll');

        // AJAX Routes
        Route::get('/data', [SupplierController::class, 'data'])->name('data');
        Route::get('/check-kode', [SupplierController::class, 'checkKode'])->name('checkKode');
        Route::get('/search', [SupplierController::class, 'search'])->name('search');

        // CRUD Routes
        Route::get('/', [SupplierController::class, 'index'])
            ->middleware('permission:detail,supplier')->name('index');
        Route::get('/create', [SupplierController::class, 'create'])
            ->middleware('permission:create,supplier')->name('create');
        Route::post('/', [SupplierController::class, 'store'])
            ->middleware('permission:create,supplier')->name('store');
        Route::get('/{id}', [SupplierController::class, 'show'])
            ->middleware('permission:detail,supplier')->name('show');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])
            ->middleware('permission:update,supplier')->name('edit');
        Route::put('/{id}', [SupplierController::class, 'update'])
            ->middleware('permission:update,supplier')->name('update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])
            ->middleware('permission:delete,supplier')->name('destroy');
    });

    // --------------------
    // Pelanggan Routes
    // --------------------
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        // Hapus semua
        Route::delete('/destroy-all', [PelangganController::class, 'destroyAll'])
            ->middleware('permission:delete,pelanggan')->name('destroyAll');

        // AJAX Routes
        Route::get('/data', [PelangganController::class, 'data'])->name('data');
        Route::get('/check-kode', [PelangganController::class, 'checkKode'])->name('checkKode');
        Route::get('/search', [PelangganController::class, 'search'])->name('search');

        // CRUD Routes
        Route::get('/', [PelangganController::class, 'index'])
            ->middleware('permission:detail,pelanggan')->name('index');
        Route::get('/create', [PelangganController::class, 'create'])
            ->middleware('permission:create,pelanggan')->name('create');
        Route::post('/', [PelangganController::class, 'store'])
            ->middleware('permission:create,pelanggan')->name('store');
        Route::get('/{id}', [PelangganController::class, 'show'])
            ->middleware('permission:detail,pelanggan')->name('show');
        Route::get('/{id}/edit', [PelangganController::class, 'edit'])
            ->middleware('permission:update,pelanggan')->name('edit');
        Route::put('/{id}', [PelangganController::class, 'update'])
            ->middleware('permission:update,pelanggan')->name('update');
        Route::delete('/{id}', [PelangganController::class, 'destroy'])
            ->middleware('permission:delete,pelanggan')->name('destroy');
    });

    // --------------------
    // Laporan Routes
    // --------------------
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Laporan Penjualan
        Route::get('/penjualan', [LaporanController::class, 'penjualan'])
            ->middleware('permission:detail,penjualan')->name('penjualan');
        Route::get('/penjualan/cetak', [LaporanController::class, 'cetakPenjualan'])
            ->middleware('permission:detail,penjualan')->name('penjualan.cetak');
        Route::get('/penjualan/export', [LaporanController::class, 'exportPenjualan'])
            ->middleware('permission:detail,penjualan')->name('penjualan.export');

        // Laporan Pembelian
        Route::get('/pembelian', [LaporanController::class, 'pembelian'])
            ->middleware('permission:detail,pembelian')->name('pembelian');
        Route::get('/pembelian/cetak', [LaporanController::class, 'cetakPembelian'])
            ->middleware('permission:detail,pembelian')->name('pembelian.cetak');
        Route::get('/pembelian/export', [LaporanController::class, 'exportPembelian'])
            ->middleware('permission:detail,pembelian')->name('pembelian.export');

        // Laporan Stok
        Route::get('/stok', [LaporanController::class, 'stok'])
            ->middleware('permission:detail,obat')->name('stok');
        Route::get('/stok/cetak', [LaporanController::class, 'cetakStok'])
            ->middleware('permission:detail,obat')->name('stok.cetak');
        Route::get('/stok/export', [LaporanController::class, 'exportStok'])
            ->middleware('permission:detail,obat')->name('stok.export');

        // Laporan Kadaluarsa
        Route::get('/kadaluarsa', [LaporanController::class, 'kadaluarsa'])
            ->middleware('permission:detail,obat')->name('kadaluarsa');
        Route::get('/kadaluarsa/cetak', [LaporanController::class, 'cetakKadaluarsa'])
            ->middleware('permission:detail,obat')->name('kadaluarsa.cetak');
        Route::get('/kadaluarsa/export', [LaporanController::class, 'exportKadaluarsa'])
            ->middleware('permission:detail,obat')->name('kadaluarsa.export');

        // Laporan Keuangan
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/keuangan/cetak', [LaporanController::class, 'cetakKeuangan'])->name('keuangan.cetak');
        Route::get('/keuangan/export', [LaporanController::class, 'exportKeuangan'])->name('keuangan.export');

        // Laporan Laba Rugi
        Route::get('/laba-rugi', [LaporanController::class, 'labaRugi'])->name('labaRugi');
        Route::get('/laba-rugi/cetak', [LaporanController::class, 'cetakLabaRugi'])->name('labaRugi.cetak');
    });

    // --------------------
    // Users Routes
    // --------------------
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:detail,user')->name('index');
        Route::get('/create', [UserController::class, 'create'])
            ->middleware('permission:create,user')->name('create');
        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:create,user')->name('store');
        Route::get('/{user}', [UserController::class, 'show'])
            ->middleware('permission:detail,user')->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])
            ->middleware('permission:update,user')->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])
            ->middleware('permission:update,user')->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:delete,user')->name('destroy');
        Route::delete('/destroy-all', [UserController::class, 'destroyAll'])
            ->middleware('permission:delete,user')->name('destroyAll');
    });

    // --------------------
    // Roles Routes
    // --------------------
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->middleware('permission:detail,hak_akses')->name('index');
        Route::get('/create', [RoleController::class, 'create'])
            ->middleware('permission:create,hak_akses')->name('create');
        Route::post('/', [RoleController::class, 'store'])
            ->middleware('permission:create,hak_akses')->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])
            ->middleware('permission:detail,hak_akses')->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:update,hak_akses')->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])
            ->middleware('permission:update,hak_akses')->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:delete,hak_akses')->name('destroy');
        Route::delete('/destroy-all', [RoleController::class, 'destroyAll'])
            ->middleware('permission:delete,hak_akses')->name('destroyAll');
        Route::get('/{role}/clone', [RoleController::class, 'clone'])
            ->middleware('permission:create,hak_akses')->name('clone');
    });

    // --------------------
    // Profile Routes
    // --------------------
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/show', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::put('/photo', [ProfileController::class, 'updatePhoto'])->name('update.photo');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';