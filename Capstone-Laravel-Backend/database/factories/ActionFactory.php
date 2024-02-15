<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * 
     * @see \App\Models\Action
     * @see \Database\Seeders\ActionSeeder
     */
    public function definition(): array
    {
        $actions = ["received", "shipped", "returned", "sold", "purchased", "created", "audited", "deleted", "approved", "rejected", "cancelled", "uncancelled", "picked", "packed", "to floor", "to storage", "login", "logout", "signup"];
        return [
            'action' => $this->faker->randomElement($actions),
        ];
    }
}
