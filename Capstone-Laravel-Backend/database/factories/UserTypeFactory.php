<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserType>
 */


// When created with factory it will fill with values defined here
class UserTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
       DEFINES DATA TO BE ENTERED WITH SEEDER
       * MODEL
       * @see \App\Models\UserType
       * SEEDER
       * @see \Database\Seeders\UserTypeSeeder
    */
    public function definition(): array
    {
        $roles = ["employee", "customer", "vendor", "corporate"];
        return [
            "role" => $this->faker->randomElement($roles),
        ];
    }
}
