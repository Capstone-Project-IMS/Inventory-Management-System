<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductDetail>
 */
class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ["red", "orange", "yeallow", "green", "blue", "indigo", "violet"];
        $sizes = ["500GB", "small", "1TB", "medium", "2TB", "large"];
        return [
            // to be filled in when creating an instance
            'product_id' => null,
            'barcode' => $this->faker->unique()->ean13,
            'color' => $this->faker->randomElement($colors),
            'size' => $this->faker->randomElement($sizes),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'quantity' => $this->faker->numberBetween(1, 20),
            'image' => "https://picsum.photos/200/300",
        ];
    }
}
