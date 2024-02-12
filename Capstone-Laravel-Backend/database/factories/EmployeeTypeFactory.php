<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeType>
 */
class EmployeeTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    /**
       DEFINES DATA TO BE ENTERED WITH SEEDER
       * MODEL
       * @see \App\Models\EmployeeType
       * SEEDER
       * @see \Database\Seeders\EmployeeTypeSeeder
    */
    public function definition(): array
    {
        // Stores roles as key and permission for each role as value
        $roles = [
            'management' => 'create,read,update,delete',
            'general' => 'read,update',
            'fulfillment' => 'read,update',
            'receiving' => 'create,read,update'
        ];
        // role of employee type is the key to $roles
        $role = $this->faker->randomElement(array_keys($roles));
        // permission is value of $roles, can access individually with implode()
        $permission = $roles[$role];
        return [
            "role" => $role,
            'permission' => $permission,
        ];
    }
}
