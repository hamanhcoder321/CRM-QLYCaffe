<?php

use Illuminate\Support\Facades\Route;
use Modules\TaiChinh\Http\Controllers\QuyetToanController;

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::prefix('tai-chinh')->name('taichinh.')->group(function () {
        Route::get('/quyet-toan', [QuyetToanController::class, 'index'])->name('quyet-toan');
        Route::get('/quyet-toan/detail/{userId}', [QuyetToanController::class, 'getDetail'])->name('quyet-toan.detail');
    });
});
