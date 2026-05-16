<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourGenerationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::middleware('admin.auth')->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::resource('categories', TourCategoryController::class)->except(['show']);
        Route::resource('tours', TourController::class)->except(['show']);
        Route::post('tours/{tour}/generate-draft', [TourGenerationController::class, 'generate'])->name('tours.generate-draft');
        Route::post('tours/{tour}/artifacts/{artifact}/apply', [TourGenerationController::class, 'apply'])->name('tours.artifacts.apply');
    });
});
