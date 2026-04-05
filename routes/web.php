<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');  // -> resources/views/home/index.blade.php
})->name('home');

// DASHBOARD  -> phải đăng nhập mới vào được
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chi nhánh
    Route::prefix('chi-nhanh')->name('branches.')->group(function () {
        Route::get('/',               [BranchController::class, 'index'])->name('list');
        Route::get('/data',           [BranchController::class, 'getData'])->name('data');
        Route::get('/get/{branch}',   [BranchController::class, 'get'])->name('get');
        Route::get('/managers',       [BranchController::class, 'getManagers'])->name('managers');
        Route::post('/store',         [BranchController::class, 'store'])->name('store');
        Route::post('/update/{branch}', [BranchController::class, 'update'])->name('update');
        Route::delete('/delete/{branch}', [BranchController::class, 'destroy'])->name('delete');
    });
});

require __DIR__ . "/auth.php";