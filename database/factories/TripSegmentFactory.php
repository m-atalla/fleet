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
            "order" => 1, // overriden on making model
            "is_main" => true,
            "trip_id" => Trip::factory(),
            "start_station_id" => Station::factory(),
            "end_station_id" => Station::factory(),
        ];
    }

    /**
     * Modify the trip order as it is added.
     * 
     */
    public function configure(): static
    {
        return $this->afterMaking(function (TripSegment $tripSegment) {
            $maxOrder = TripSegment::where("trip_id", $tripSegment->trip_id)
                ->max("order");
            $tripSegment->order = $maxOrder + 1;

            // All other segments are considered a subset of the main trip 
            // segment. This incomplete but works well enough for testing.
            if ($maxOrder > 1) {
                $tripSegment->is_main = false;
            }
        });
    }
}
