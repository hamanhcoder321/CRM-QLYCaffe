<?php

use Illuminate\Support\Facades\Route;
use Modules\NhanSu\Http\Controllers\NhanSuController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Chấm công - Cho phép tất cả nhân viên truy cập
    Route::get('cham-cong', [NhanSuController::class, 'timekeeping'])->name('nhansu.cham-cong');
    Route::post('cham-cong', [NhanSuController::class, 'storeTimekeeping'])->name('nhansu.cham-cong.store');

    // Tuyển dụng, cơ sở vật chất - Chỉ dành cho HR/Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('nhansus', NhanSuController::class)->names('nhansu');
        Route::get('facilities', [NhanSuController::class, 'facilities'])->name('nhansu.facilities');
        Route::post('facilities', [NhanSuController::class, 'storeFacility'])->name('nhansu.facilities.store');
        Route::get('facilities/{id}', [NhanSuController::class, 'getFacility'])->name('nhansu.facilities.get');
        Route::post('facilities/update/{id}', [NhanSuController::class, 'updateFacility'])->name('nhansu.facilities.update');
        Route::delete('facilities/{id}', [NhanSuController::class, 'destroyFacility'])->name('nhansu.facilities.delete');
    });
});
