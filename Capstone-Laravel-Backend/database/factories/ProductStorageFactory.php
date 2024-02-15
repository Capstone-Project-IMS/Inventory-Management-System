<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\StorageLocation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductStorage>
 */
class ProductStorageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_details_id' => null,
            'storage_location_id' => StorageLocation::factory(),
            'quantity' => 0,
        ];
    }
}
