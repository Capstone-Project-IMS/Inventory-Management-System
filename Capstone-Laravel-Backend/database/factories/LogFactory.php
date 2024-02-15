<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\ProductDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_details_id' => ProductDetail::factory(),
            'action_id' => Action::factory(),
            'description' => $this->faker->sentence(),
        ];
    }
}
