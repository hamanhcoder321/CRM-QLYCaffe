<?php

use Illuminate\Support\Facades\Route;
use Modules\BanHang\Http\Controllers\BanHangController;

Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::prefix('ban-hang')->name('banhang.')->group(function () {

        // Thực đơn / Menu (Drinks)
        Route::get('/thuc-don',              [BanHangController::class, 'thucDon'])->name('thuc-don');
        Route::get('/thuc-don/data',         [BanHangController::class, 'thucDonData'])->name('thuc-don.data');
        Route::get('/thuc-don/get/{drink}',  [BanHangController::class, 'thucDonGet'])->name('thuc-don.get');
        Route::post('/thuc-don/store',       [BanHangController::class, 'thucDonStore'])->name('thuc-don.store');
        Route::post('/thuc-don/update/{drink}', [BanHangController::class, 'thucDonUpdate'])->name('thuc-don.update');
        Route::delete('/thuc-don/delete/{drink}', [BanHangController::class, 'thucDonDelete'])->name('thuc-don.delete');

        // Nguyên liệu (Products) - CRUD dùng chung cho Tồn Kho
        Route::get('/nguyen-lieu/get/{product}', [BanHangController::class, 'thuocUongGet'])->name('nguyen-lieu.get');
        Route::post('/nguyen-lieu/store',       [BanHangController::class, 'thuocUongStore'])->name('nguyen-lieu.store');
        Route::post('/nguyen-lieu/update/{product}', [BanHangController::class, 'thuocUongUpdate'])->name('nguyen-lieu.update');
        Route::delete('/nguyen-lieu/delete/{product}', [BanHangController::class, 'thuocUongDelete'])->name('nguyen-lieu.delete');

        // Tồn kho
        Route::get('/ton-kho',      [BanHangController::class, 'tonKho'])->name('ton-kho');
        Route::get('/ton-kho/data', [BanHangController::class, 'tonKhoData'])->name('ton-kho.data');

        // Helper
        Route::get('/shipments',    [BanHangController::class, 'getShipments'])->name('shipments');

        // Giao dịch bán hàng
        Route::get('/giao-dich',              [BanHangController::class, 'giaoDich'])->name('giao-dich');
        Route::get('/giao-dich/data',         [BanHangController::class, 'giaoDichData'])->name('giao-dich.data');
        Route::get('/giao-dich/get/{sell}',   [BanHangController::class, 'giaoDichGet'])->name('giao-dich.get');
        Route::post('/giao-dich/store',       [BanHangController::class, 'giaoDichStore'])->name('giao-dich.store');
        Route::post('/giao-dich/update/{sell}', [BanHangController::class, 'giaoDichUpdate'])->name('giao-dich.update');
        Route::delete('/giao-dich/delete/{sell}', [BanHangController::class, 'giaoDichDelete'])->name('giao-dich.delete');

        // PayOS thanh toán cho BanHang
        Route::post('/giao-dich/payos/tao-link',    [BanHangController::class, 'payosTaoLink'])->name('giao-dich.payos.tao-link');
    });
});

// Callback PayOS cho BanHang (không cần auth)
Route::get('/ban-hang/payos/success', [BanHangController::class, 'payosSuccess'])->name('banhang.payos.success');
Route::get('/ban-hang/payos/cancel',  [BanHangController::class, 'payosCancel'])->name('banhang.payos.cancel');

