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
        $types = ["in-store", "online"];
        $type = $this->faker->randomElement($types);
        // if type is in-store then status is completed
        $orderStatus = $type === 'in-store' ? 'completed' : $this->faker->randomElement($status);
        // if order type is online then employee id is null
        $employeeId = $type === 'online' ? null : Employee::all()->random()->id;

        return [
            'customer_id' => Customer::all()->random()->id,
            'employee_id' => $employeeId,
            'type' => $type,
            'total' => 0,
            'order_date' => $this->faker->dateTimeThisYear,
            'status' => $orderStatus,
        ];
    }
}
