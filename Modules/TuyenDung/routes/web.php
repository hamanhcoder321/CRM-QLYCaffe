<?php

use Illuminate\Support\Facades\Route;
use Modules\TuyenDung\Http\Controllers\TuyenDungController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('tuyen-dung')->name('tuyendung.')->group(function () {
        Route::get('/', [TuyenDungController::class, 'index'])->name('list');
        Route::get('/data', [TuyenDungController::class, 'data'])->name('data');
        Route::get('/create', [TuyenDungController::class, 'create'])->name('create');
        Route::post('/store', [TuyenDungController::class, 'store'])->name('store');
    });
});
