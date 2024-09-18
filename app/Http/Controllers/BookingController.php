<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\TripSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Translation\Dumper\DumperInterface;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->bookings()->latest()->paginate(10)
        );
    }

    private function getOverlappingSegments(int $tripSegmentId, int $seatId)
    {

        // Checking for overlapping segments.

        // An easy edge case: if an overlap occurs with an `is_main` should be 
        // rejected
        $requestedSegment = TripSegment::findOrFail($tripSegmentId);


        $currentStartOrder = $requestedSegment->start_order;
        $currentEndOrder = $requestedSegment->end_order;

        $tripId = $requestedSegment->trip_id;

        // Check for overlapping segments by comparing orders
        return Booking::where('seat_id', $seatId)
            ->whereHas(
                'tripSegment',
                function ($query)
                use ($tripId, $currentStartOrder, $currentEndOrder) {
                    $query->where('trip_id', $tripId)
                        ->where(
                            function ($q)
                            use ($currentStartOrder, $currentEndOrder) {
                                $q->where(
                                    function ($subQ)
                                    use ($currentStartOrder, $currentEndOrder) {
                                        $subQ->where(
                                            'start_order',
                                            '>=',
                                            $currentStartOrder
                                        )->where(
                                            'start_order',
                                            '<=',
                                            $currentEndOrder
                                        );
                                    }
                                )->orWhere(
                                    function ($subQ)
                                    use ($currentStartOrder, $currentEndOrder) {
                                        $subQ->where(
                                            'end_order',
                                            '>=',
                                            $currentStartOrder
                                        )->where(
                                            'end_order',
                                            '<=',
                                            $currentEndOrder
                                        );
                                    }
                                );
                            }
                        );
                }
            )->first();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "seat_id" => "required|exists:seats,id|numeric",
            "trip_segment_id" => "required:exists:trip_segments,id|numeric",
        ]);

        $segmentBooking = Booking::where("seat_id", $request->seat_id)
            ->where("trip_segment_id", $request->trip_segment_id)->first();


        // handles repeated requests
        if ($segmentBooking && $segmentBooking->user_id == Auth::id()) {
            return response()->json(
                [
                    "message" => "You have already booked this seat.",
                    "booking" => $segmentBooking,
                ],
                208
            );
        }

        // handles if this specific seat is already reserved for this segment
        if ($segmentBooking) {
            return response()->json(
                ["error" => "This seat is already reserved."],
                403
            );
        }

        $overlappingBooking = $this->getOverlappingSegments(
            $request->trip_segment_id,
            $request->seat_id
        );

        // If an overlapping booking exists, reject the booking
        if ($overlappingBooking) {
            return response()->json(
                [
                    "error" => "This seat is already reserved."
                ],
                403
            );
        }

        // Create the booking
        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->seat_id = $request->seat_id;
        $booking->trip_segment_id = $request->trip_segment_id;
        $booking->save();

        // Return a success response
        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        if ($booking->user_id != Auth::id()) {
            return response()->json(
                [
                    "error" => "You are not authorized for this resource"
                ],
                403
            );
        }

        return response()->json($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // todo
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // todo
    }
}
