<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "number" => $this->faker->unique()->numberBetween(1, 12),
            "bus_id" => Bus::factory(),
        ];
    }

    /**
     * Override to reset unique constraints after each batch
     * 
     */
    public function configure()
    {
        return $this->afterCreating(function (Seat $seat) {
            $this->faker->unique(true); 
        });
    }
}
