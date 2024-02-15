<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesOrder>
 */
class SalesOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ["pending", "processing", "completed", "cancelled"];
        return [
            'customer_id' => Customer::factory(),
            'employee_id' => Employee::factory(),
            'total' => 0,
            'order_date' => $this->faker->dateTimeThisYear,
            'status' => $this->faker->randomElement($status),
        ];
    }
}
