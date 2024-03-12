<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ["in-cart", "saved"];
        $productDetail = ProductDetail::all()->random();
        $quantity = $this->faker->numberBetween(1, $productDetail->quantity);
        $price = $quantity * $productDetail->price;

        return [
            'customer_id' => Customer::all()->random()->id,
            'product_details_id' => $productDetail->id,
            'quantity' => $quantity,
            'price' => $price,
            'status' => $this->faker->randomElement($status),
        ];
    }
}
