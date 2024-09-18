<?php

use App\Models\Booking;
use App\Models\Seat;
use App\Models\Station;
use App\Models\TripSegment;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

test('Can create a new booking given its available', function () {
    $user = User::factory()->create();

    $seat = Seat::factory()->create();

    $trip_segment = TripSegment::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->post(
        '/api/bookings',
        ["seat_id" => $seat->id, "trip_segment_id" => $trip_segment->id],
        ["accept" => "application/json"]
    );

    $response->assertStatus(201)
        ->assertJson(["booking" => [
            "seat_id" => $seat->id,
            "trip_segment_id" => $trip_segment->id
        ]]);
});

test('Restrict booking a new booking given its not available', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);
    $seat = Seat::factory()->create();
    $trip_segment = TripSegment::factory()->create();

    // create fake data
    Booking::factory()->create([
        "seat_id" => $seat->id,
        "trip_segment_id" => $trip_segment->id
    ]);

    $response = $this->post("/api/bookings", [
        "seat_id" => $seat->id,
        "trip_segment_id" => $trip_segment->id
    ]);

    $response->assertStatus(403);
});

test('Repeated booking request should not create new entries', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);
    $seat = Seat::factory()->create();
    $trip_segment = TripSegment::factory()->create();

    // create fake data
    Booking::factory()->create([
        "seat_id" => $seat->id,
        "trip_segment_id" => $trip_segment->id,
        "user_id" => $user->id,
    ]);

    $num_entries = Booking::count();

    $response = $this->post("/api/bookings", [
        "seat_id" => $seat->id,
        "trip_segment_id" => $trip_segment->id
    ]);

    $response->assertStatus(208)->assertJson(
        fn(AssertableJson $json) => $json->has("message")->has("booking")
    );

    $this->assertDatabaseCount("bookings", $num_entries);
});

test('Restrict reserved seats in subsequent sections', function () {
    // preamble
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $startStation = Station::factory()->create();
    $interStation = Station::factory()->create();
    $endStation = Station::factory()->create();

    $mainSegment = TripSegment::factory()->create([
        "start_station_id" => $startStation->id,
        "end_station_id" => $endStation->id,
        "start_order" => 0,
        "end_order" => 2,
        "is_main" => true,
    ]);

    // Inter segments which overlap with the main segment example:
    //  Main Segment: A -> C
    //  First Segment: A -> B
    //  Second Segment: B -> C
    $firstInterSegment = TripSegment::factory()->create([
        "start_station_id" => $startStation->id,
        "end_station_id" => $interStation->id,
        "trip_id" => $mainSegment->trip_id,
        "start_order" => 0,
        "end_order" => 1,
        "is_main" => false,
    ]);

    $secondInterSegment = TripSegment::factory()->create([
        "start_station_id" => $interStation->id,
        "end_station_id" => $endStation->id,
        "trip_id" => $mainSegment->trip_id,
        "start_order" => 1,
        "end_order" => 2,
        "is_main" => false,
    ]);

    $seats = $mainSegment->trip->bus->seats->sortBy("number");

    // create fake data
    Booking::factory()->create([
        "seat_id" => $seats->first()->id,
        "trip_segment_id" => $mainSegment->id,
        "user_id" => $user->id,
    ]);


    // requests
    $firstSegResponse = $this->post("/api/bookings", [
        "seat_id" => $seats->first()->id,
        "trip_segment_id" => $firstInterSegment->id
    ]);

    $secondSegResponse = $this->post("/api/bookings", [
        "seat_id" => $seats->first()->id,
        "trip_segment_id" => $secondInterSegment->id
    ]);

    // assertions
    $firstSegResponse->assertStatus(403);
    $secondSegResponse->assertStatus(403);
});


test('Users can get their presepective bookings', function () {
    // authenicate user
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    // create fake data
    $booking = Booking::factory()->create(["user_id" => $user->id]);
    $unauthorizedBooking = Booking::factory()->create();

    // make requests
    $validResponse = $this->get("/api/bookings/$booking->id");
    $unauthorizedResponse = $this->get("/api/bookings/$unauthorizedBooking->id");

    // response assertions
    $validResponse->assertStatus(200);
    $unauthorizedResponse->assertStatus(403);
});
