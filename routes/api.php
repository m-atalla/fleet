<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware(["auth:sanctum"])
    ->post("/bookings", [BookingController::class, "store"]);

Route::middleware(["auth:sanctum"])
    ->get("/bookings", [BookingController::class, "index"]);

Route::middleware(["auth:sanctum"])
    ->get("/bookings/{booking}", [BookingController::class, "show"]);