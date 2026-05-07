<?php

use Illuminate\Support\Facades\Route;
use Modules\QuanLyChiTieu\Http\Controllers\ChiTieuController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('quan-ly-chi-tieu')->name('quanlychitieu.')->group(function () {
        Route::get('/', [ChiTieuController::class, 'index'])->name('index');
        Route::post('/store', [ChiTieuController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ChiTieuController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ChiTieuController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [ChiTieuController::class, 'destroy'])->name('destroy');
    });
});
