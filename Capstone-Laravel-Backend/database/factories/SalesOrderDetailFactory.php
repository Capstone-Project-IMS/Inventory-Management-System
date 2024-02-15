<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SalesOrder;
use App\Models\ProductDetail;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesOrderDetail>
 */
class SalesOrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sales_order_id' => SalesOrder::factory(),
            'product_details_id' => ProductDetail::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 0, 1000),

        ];
    }
}
