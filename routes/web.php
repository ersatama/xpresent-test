<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;

// Главная страница — список услуг
Route::get('/', [ServiceController::class, 'index'])->name('services.index');

// Просмотр конкретной услуги
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Получение доступных слотов для выбранной даты
Route::get('/services/{service}/slots', [ServiceController::class, 'slots'])->name('services.slots');

// Создание бронирования
Route::post('/services/{service}/book', [BookingController::class, 'store'])->name('bookings.store');