<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ["food", 'tech', "clothing", "sport", "toy", "seasonal", "OTC", "home", "office", "cleaning", "bath", "bedding"];
        return [
            'vendor_id' => Vendor::all()->random()->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement($categories),
            'display_image' => 'https://picsum.photos/200/300',
        ];
    }
}
