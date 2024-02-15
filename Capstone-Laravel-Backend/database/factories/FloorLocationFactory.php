<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Location;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FloorLocation>
 * 
 * 
 * @see \App\Models\FloorLocation
 * @see \Database\Seeders\FloorLocationSeeder
 */
class FloorLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location_id' => Location::factory(),
            'product_details_id' => null,
            'aisle' => $this->faker->randomLetter,
            'row' => $this->faker->numberBetween(1, 10),
            'position' => $this->faker->numberBetween(1, 10),
            'max_capacity' => $this->faker->numberBetween(100, 1000),
            'current_capacity' => $this->faker->numberBetween(0, 100),
        ];
    }
}
