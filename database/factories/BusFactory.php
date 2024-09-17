<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "plate_number" => fake()->bothify("???-###"),
        ];
    }

    /**
     * After bus creation, create 12 associated seats.
     * 
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Bus $bus){
            Seat::factory(12)->create([
                "bus_id" => $bus->id,
            ]);
        });
    }
}
