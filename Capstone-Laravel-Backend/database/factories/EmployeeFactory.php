<?php

namespace Database\Factories;

use App\Models\EmployeeType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // user_id should be from user factory
            'user_id' => User::factory(),
            // random employee type
            'employee_type_id' => EmployeeType::all()->random()->id,
        ];
    }
}
