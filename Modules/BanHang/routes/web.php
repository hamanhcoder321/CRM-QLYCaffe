<?php

use Illuminate\Support\Facades\Route;
use Modules\BanHang\Http\Controllers\BanHangController;

Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::prefix('ban-hang')->name('banhang.')->group(function () {

        // Thức uống
        Route::get('/thuc-uong',              [BanHangController::class, 'thuocUong'])->name('thuc-uong');
        Route::get('/thuc-uong/data',         [BanHangController::class, 'thuocUongData'])->name('thuc-uong.data');
        Route::get('/thuc-uong/get/{product}', [BanHangController::class, 'thuocUongGet'])->name('thuc-uong.get');
        Route::post('/thuc-uong/store',       [BanHangController::class, 'thuocUongStore'])->name('thuc-uong.store');
        Route::post('/thuc-uong/update/{product}', [BanHangController::class, 'thuocUongUpdate'])->name('thuc-uong.update');
        Route::delete('/thuc-uong/delete/{product}', [BanHangController::class, 'thuocUongDelete'])->name('thuc-uong.delete');

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
        Route::delete('/giao-dich/delete/{sell}', [BanHangController::class, 'giaoDichDelete'])->name('giao-dich.delete');
    });
});
