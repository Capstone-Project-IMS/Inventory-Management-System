<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ref
     */
    public function run(): void
    {
        //
        $roles = ["admin","employee", "customer", "vendor", "corporate"];

        foreach ($roles as $role) {
            UserType::create(['role' => $role]);
        }
    }
}
