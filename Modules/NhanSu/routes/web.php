<?php

use Illuminate\Support\Facades\Route;
use Modules\NhanSu\Http\Controllers\NhanSuController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('nhansus', NhanSuController::class)->names('nhansu');
    Route::get('facilities', [NhanSuController::class, 'facilities'])->name('nhansu.facilities');
    Route::post('facilities', [NhanSuController::class, 'storeFacility'])->name('nhansu.facilities.store');
});
