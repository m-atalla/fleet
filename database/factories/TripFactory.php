<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $departure_datetime = $this->faker->dateTimeBetween("now", "+3 days");
        $arrival_datetime = $this->faker
            ->dateTimeInInterval($departure_datetime, "+6 hours");
        return [
            "departure" => $departure_datetime,
            "arrival" => $arrival_datetime,
            "bus_id" => Bus::factory(),
        ];
    }
}
