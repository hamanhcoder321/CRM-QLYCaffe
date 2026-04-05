<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/account', [UserController::class, 'index'])->name('account');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/data', [UserController::class, 'getUsersData'])->name('data');
        Route::get('/filters', [UserController::class, 'getFilters'])->name('filters');
        Route::get('/created', [UserController::class, 'created'])->name('created');
        Route::post('/create', [UserController::class, 'store'])->name('store');
        Route::get('/update/{user}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{user}', [UserController::class, 'destroy'])->name('delete');
    });
});