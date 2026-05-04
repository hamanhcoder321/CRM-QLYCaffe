<?php

use Illuminate\Support\Facades\Route;
use Modules\NhanSu\Http\Controllers\NhanSuController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('nhansus', NhanSuController::class)->names('nhansu');
});
