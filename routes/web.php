<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/availability', AvailabilityController::class)->name('availability');

    Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');

    Route::middleware('admin')->group(function () {
        Route::get('/equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
        Route::post('/equipment', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::get('/equipment/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('/equipment/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/equipment/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    });

    Route::get('/equipment/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
