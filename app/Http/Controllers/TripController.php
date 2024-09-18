<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Trip::with("tripSegments")->get());
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
