<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
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
            'vendor_id' => Vendor::factory(),
            'employee_id' => Employee::factory(),
            'total' => 0,
            'order_date' => $this->faker->dateTimeThisYear,
            'status' => $this->faker->randomElement($status),
        ];
    }
}
