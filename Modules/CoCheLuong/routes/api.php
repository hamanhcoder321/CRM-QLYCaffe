<?php

use Illuminate\Support\Facades\Route;
use Modules\CoCheLuong\Http\Controllers\CoCheLuongController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('cocheluongs', CoCheLuongController::class)->names('cocheluong');
});
