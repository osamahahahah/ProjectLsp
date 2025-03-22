<?php

use Illuminate\Support\Facades\Route;
use Filament\Pages\Dashboard;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BookingRoomController;
use App\Http\Controllers\MidtransController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', Dashboard::class)->name('filament.pages.dashboard');

Route::middleware(['auth'])->group(function () {
    // Booking Room
    Route::get('/booking-room/{id}', [BookingRoomController::class, 'show'])
        ->name('booking-room.show');

    // Reservasi (Grup Route)
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{id}', [ReservationController::class, 'show'])->name('show'); // Tambahan detail reservasi
        Route::patch('/{id}/status', [ReservationController::class, 'updateStatus'])->name('updateStatus');
    });

});


Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback'])
->name('midtrans.callback');

Route::get('/midtrans/redirect', function (Request $request) {
    return redirect()->route('filament.admin.resources.booking-rooms.index')
        ->with('success', 'Payment processed successfully!');
})->name('midtrans.redirect');


