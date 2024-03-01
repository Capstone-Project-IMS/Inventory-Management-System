<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customerTypeId = UserType::where('role', 'customer')->first()->id;
        return [
            'user_id' => User::where('user_type_id', $customerTypeId)->inRandomOrder()->first()->id,
        ];
    }
}
