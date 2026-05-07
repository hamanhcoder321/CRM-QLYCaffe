<?php

use Illuminate\Support\Facades\Route;
use Modules\CoCheLuong\Http\Controllers\CoCheLuongController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('salary-mechanism', [CoCheLuongController::class, 'index'])->name('cocheluong.index');
    Route::get('salary-mechanism/get/{id}', [CoCheLuongController::class, 'getMechanism'])->name('cocheluong.get');
    Route::post('salary-mechanism/update', [CoCheLuongController::class, 'updateMechanism'])->name('cocheluong.update');
});
