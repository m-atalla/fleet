<?php

namespace Database\Factories;

use App\Models\Station;
use App\Models\Trip;
use App\Models\TripSegment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripSegment>
 */
class TripSegmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "start_order" => 0, 
            "end_order" => 1, 
            "is_main" => true,
            "trip_id" => Trip::factory(),
            "start_station_id" => Station::factory(),
            "end_station_id" => Station::factory(),
        ];
    }
}
