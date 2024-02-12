<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Location;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storageType = ["floor", "storage"];

        $name = $this->faker->randomElement(["Location1", "Location2", "Location3"]);
        $type = $this->faker->randomElement($storageType);
        $aisle = $this->aisle();
        $row = rand(1, 10);
        $position = rand(1, 10);
        $max_capacity = rand(1, 10);
        $current_capacity = 0;

        while (Location::where(compact('name', 'type', 'aisle', 'row', 'position'))->exists()) {
            $name = $this->faker->randomElement(["Location1", "Location2", "Location3"]);
            $type = $this->faker->randomElement($storageType);
            $aisle = $this->aisle();
            $row = rand(1, 10);
            $position = rand(1, 10);
            $max_capacity = rand(1, 10);
            $current_capacity = 0;
        }
        return compact('name', 'type', 'aisle', 'row', 'position', 'max_capacity', 'current_capacity');

    }

    public function aisle()
    {
        $letter = chr(rand(65, 75));
        $number = rand(1, 10);
        return $letter . $number;
    }
}
