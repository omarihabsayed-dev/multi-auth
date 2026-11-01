<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(AdminController::class)
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::middleware('admin.guest')->group(function () {
            Route::get('/login', 'create')->name('adminCreate');
            Route::post('/login', 'login')->name('adminLogin');
            Route::get('/register', 'register')->name('adminRegister');
            Route::post('/register', 'store')->name('adminStore');
        });
        Route::middleware('admin.active')->group(function () {
            Route::get('/dashboard', 'dashboard')->name('adminDashboard');
            Route::get('/logout', 'logout')->name('adminLogout');
        });
    });

Route::controller(SellerController::class)
    ->prefix('seller')
    ->name('seller.')
    ->group(function (): void {
        Route::middleware('seller.guest')->group(function () {
            Route::get('/login', 'create')->name('sellerCreate');
            Route::post('/login', 'login')->name('sellerLogin');
            Route::get('/register', 'register')->name('sellerRegister');
            Route::post('/register', 'store')->name('sellerStore');
        });
        Route::middleware('seller.active')->group(function () {
            Route::get('/dashboard', 'dashboard')->name('sellerDashboard');
            Route::get('/logout', 'logout')->name('sellerLogout');
        });
    });

require __DIR__.'/auth.php';
