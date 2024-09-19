<?php

use App\Models\Booking;
use App\Models\Trip;
use App\Models\TripSegment;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;

test('A user can view upcoming trips', function () {
    $expiredTrip = Trip::factory()->create(["departure" => Carbon::yesterday()]);

    $upcoming = Trip::factory()->create(["departure" => Carbon::tomorrow()]);

    $response = $this->get("/api/trips");

    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has("trips");
        })
        ->assertJsonFragment(["id" => $upcoming->id])
        ->assertJsonMissing(["id" => $expiredTrip->id]);
});

test('A user can available seats for a segment', function () {
    $trip = Trip::factory()->create();

    $seats = $trip->bus->seats->sortBy("number");

    $mainSegment = TripSegment::factory()->create([
        "trip_id" => $trip->id,
        "start_order" => 0,
        "end_order" => 3,
    ]);

    $firstSubSegment = TripSegment::factory()->create([
        "trip_id" => $trip->id,
        "is_main" => false,
        "start_order" => 1,
        "end_order" => 2,
    ]);


    $secondSubSegment = TripSegment::factory()->create([
        "trip_id" => $trip->id,
        "is_main" => false,
        "start_order" => 2,
        "end_order" => 3,
    ]);


    Booking::factory()->create([
        "trip_segment_id" => $mainSegment->id,
        "seat_id" => $seats->first()->id
    ]);

    Booking::factory()->create([
        "trip_segment_id" => $firstSubSegment->id,
        "seat_id" => $seats->last()->id
    ]);


    $mainResponse = $this->get("/api/trip-segments/$mainSegment->id");

    $firstSegResponse = $this->get("/api/trip-segments/$firstSubSegment->id");

    $secondSegResponse = $this->get("/api/trip-segments/$secondSubSegment->id");


    $mainResponse->assertStatus(200)
        ->assertJsonMissing(["id" => $seats->first()->id])
        ->assertJsonMissing(["id" => $seats->last()->id]);

    $firstSegResponse->assertStatus(200)
        ->assertJsonMissing(["id" => $seats->first()->id])
        ->assertJsonMissing(["id" => $seats->last()->id]);

    $secondSegResponse->assertStatus(200)
        ->assertJsonMissing(["id" => $seats->first()->id])
        ->assertJsonFragment(["id" => $seats->last()->id]);
});
