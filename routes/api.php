<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripSegmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



// bookings
Route::middleware(["auth:sanctum"])
    ->post("/bookings", [BookingController::class, "store"]);

Route::middleware(["auth:sanctum"])
    ->get("/bookings", [BookingController::class, "index"]);

Route::middleware(["auth:sanctum"])
    ->get("/bookings/{booking}", [BookingController::class, "show"]);

// trips
Route::get("/trips", [TripController::class, "index"]);

Route::get("/trips/{id}", [TripController::class, "show"]);

// trip segments
Route::get("/trip-segments/{tripSegment}", [TripSegmentController::class, "show"]);