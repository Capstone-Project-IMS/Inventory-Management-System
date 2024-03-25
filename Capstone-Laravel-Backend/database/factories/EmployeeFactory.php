<?php

namespace Database\Factories;

use App\Models\EmployeeType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\UserType;

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
            // random employee type
            'employee_type_id' => EmployeeType::all()->random()->id,
        ];
    }
}
