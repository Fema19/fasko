<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RepairReportController;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('landing');
});

// Resource route untuk Message (tanpa middleware)
Route::resource('messages', MessageController::class);

// Resource routes untuk fitur utama aplikasi
Route::resource('categories', CategoryController::class);
Route::resource('facilities', FacilityController::class);
Route::resource('bookings', BookingController::class);

// Custom actions untuk bookings
Route::post('bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
Route::post('bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
Route::post('bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.checkIn');

Route::resource('repair-reports', RepairReportController::class);
Route::post('repair-reports/{report}/status', [RepairReportController::class, 'updateStatus'])->name('repair-reports.updateStatus');
