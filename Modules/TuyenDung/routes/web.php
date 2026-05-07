<?php

use Illuminate\Support\Facades\Route;
use Modules\TuyenDung\Http\Controllers\TuyenDungController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('tuyen-dung')->name('tuyendung.')->group(function () {
        Route::get('/', [TuyenDungController::class, 'index'])->name('list');
        Route::get('/data', [TuyenDungController::class, 'data'])->name('data');
        Route::get('/create', [TuyenDungController::class, 'create'])->name('create');
        Route::post('/store', [TuyenDungController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TuyenDungController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TuyenDungController::class, 'update'])->name('update');
        Route::delete('/{id}', [TuyenDungController::class, 'destroy'])->name('destroy');

        // Ứng tuyển
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [\Modules\TuyenDung\Http\Controllers\ApplicationController::class, 'index'])->name('list');
            Route::get('/data', [\Modules\TuyenDung\Http\Controllers\ApplicationController::class, 'data'])->name('data');
            Route::post('/update-status/{id}', [\Modules\TuyenDung\Http\Controllers\ApplicationController::class, 'updateStatus'])->name('update-status');
        });
    });
});
