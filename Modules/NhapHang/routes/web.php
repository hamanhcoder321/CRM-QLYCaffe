<?php

use Illuminate\Support\Facades\Route;
use Modules\NhapHang\Http\Controllers\NhapHangController;

Route::middleware(['auth', 'role:warehouse'])->group(function () {
    Route::prefix('nhap-hang')->name('nhaphang.')->group(function () {
        Route::get('/', [NhapHangController::class, 'index'])->name('list');
        Route::get('/data', [NhapHangController::class, 'getData'])->name('data');
        Route::get('/filters', [NhapHangController::class, 'getFilters'])->name('filters');
        Route::get('/form-options', [NhapHangController::class, 'getFormOptions'])->name('form-options');
        Route::get('/get/{arrange}', [NhapHangController::class, 'getArrange'])->name('get');
        Route::post('/store', [NhapHangController::class, 'store'])->name('store');
        Route::post('/update/{arrange}', [NhapHangController::class, 'update'])->name('update');
        Route::delete('/delete/{arrange}', [NhapHangController::class, 'destroy'])->name('delete');

        // Đơn nhập
        Route::get('/don-nhap', [NhapHangController::class, 'donNhap'])->name('don-nhap');
        Route::get('/don-nhap/data', [NhapHangController::class, 'donNhapData'])->name('don-nhap.data');

        // Nhà cung cấp
        Route::get('/nha-cung-cap', [NhapHangController::class, 'nhaCungCap'])->name('nha-cung-cap');
        Route::get('/nha-cung-cap/data', [NhapHangController::class, 'nhaCungCapData'])->name('nha-cung-cap.data');
        Route::post('/nha-cung-cap/store', [NhapHangController::class, 'nhaCungCapStore'])->name('nha-cung-cap.store');
        Route::get('/nha-cung-cap/get/{customer}', [NhapHangController::class, 'nhaCungCapGet'])->name('nha-cung-cap.get');
        Route::post('/nha-cung-cap/update/{customer}', [NhapHangController::class, 'nhaCungCapUpdate'])->name('nha-cung-cap.update');
        Route::delete('/nha-cung-cap/delete/{customer}', [NhapHangController::class, 'nhaCungCapDestroy'])->name('nha-cung-cap.delete');
    });
});
