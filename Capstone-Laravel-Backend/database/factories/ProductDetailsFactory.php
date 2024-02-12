<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ["red", "orange", "yeallow", "green", "blue", "indigo", "violet"];
        $sizes = ["500GB" , "small", "1TB" , "medium", "2TB" , "large"];
        return [
            'product_id' => Product::factory(),
            'location_id' =>Location::factory(),
            'color' => $this->faker->randomElement($colors),
            'size' => $this->faker->randomElement($sizes),
            'price' => $this->faker->randomFloat(2,0,10000),
            'quantity' => $this->faker->randomDigitNotZero(),
            'barcode' => $this->faker->ean13(),
            'image' => "https://picsum.photos/200/300",
        ];
    }
}
