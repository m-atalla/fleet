<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $upcomingTrips = Trip::with("tripSegments")
            ->where("departure", ">", Carbon::now())->get();

        return response()->json(["trips" => $upcomingTrips]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $trip = Trip::with("tripSegments")->findOrFail($id);
        return response()->json($trip);
    }
}
