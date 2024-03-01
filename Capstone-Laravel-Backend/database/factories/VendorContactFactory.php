<?php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VendorContact>
 */
class VendorContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('user_type_id', UserType::where('role', 'vendor')->first()->id)->inRandomOrder()->first()->id,
            'vendor_id' => Vendor::all()->random()->id,
            'is_primary' => $this->faker->boolean,
        ];
    }
}
