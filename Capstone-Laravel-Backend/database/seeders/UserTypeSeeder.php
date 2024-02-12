<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = ["employee", "customer", "vendor", "corporate"];

        foreach ($roles as $role) {
            UserType::create(['role' => $role]);
        }
    }
}
