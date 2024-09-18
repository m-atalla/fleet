<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\TripSegment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tripSegment = TripSegment::factory()->create();
        return [
            "seat_id" => $tripSegment->trip->bus->seats->first()->id,
            "user_id" => User::factory(),
            "trip_segment_id" => $tripSegment->id
        ];
    }
}
