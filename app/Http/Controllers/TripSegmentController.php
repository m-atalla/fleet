<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TripSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TripSegmentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(TripSegment $tripSegment)
    {
        return response()->json([
            "segment" => $tripSegment,
            "availableSeats" => $this->availableSeats($tripSegment->id)
        ]);
    }


    public function availableSeats($tripSegmentId)
    {
        // Cache key based on the trip segment
        $cacheKey = 'available_seats_trip_segment_' . $tripSegmentId;

        // Check if the result is already cached
        return Cache::remember(
            $cacheKey,
            now()->addHour(),
            function () use ($tripSegmentId) {
                // Fetch the trip segment
                $tripSegment = TripSegment::findOrFail($tripSegmentId);

                $currentStartOrder = $tripSegment->start_order;
                $currentEndOrder = $tripSegment->end_order;
                $tripId = $tripSegment->trip_id;

                // Fetch all seats for the trip (assuming seats are tied to a trip)
                $allSeats = $tripSegment->trip->bus->seats;

                // Get booked seat IDs across overlapping segments for this trip
                $bookedSeats = Booking::whereHas('tripSegment', function ($query) use ($tripId, $currentStartOrder, $currentEndOrder) {
                    $query->where('trip_id', $tripId)
                        ->where(function ($q) use ($currentStartOrder, $currentEndOrder) {
                            $q->where(function ($subQ) use ($currentStartOrder, $currentEndOrder) {
                                $subQ->where('start_order', '<=', $currentEndOrder)
                                    ->where('end_order', '>=', $currentStartOrder);
                            });
                        });
                })->pluck('seat_id');

                // Filter out the booked seats
                $availableSeats = $allSeats->whereNotIn('id', $bookedSeats);

                // Return available seats
                return $availableSeats;
            }
        );
    }
}
