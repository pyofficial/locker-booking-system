<?php

use App\Http\Controllers\v1\LockerBookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'lockers', 'as' => 'lockers.'], function (){
    Route::get('available-lockers', [LockerBookingController::class, 'checkAvailability'])->name('check-availability');
    Route::post('book-locker', [LockerBookingController::class, 'bookLocker'])->name('book-locker');
    Route::delete('/cancel-booking/{booking_id}', [LockerBookingController::class, 'cancelBooking'])->name('cancel-booking');
});