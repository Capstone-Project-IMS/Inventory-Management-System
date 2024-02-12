<?php

namespace Database\Seeders;

use App\Models\EmployeeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'management' => 'create,read,update,delete',
            'general' => 'read,update',
            'fulfillment' => 'read,update',
            'receiving' => 'create,read,update'
        ];
        foreach ($roles as $role => $permission) {
            EmployeeType::create(['role' => $role, 'permission' => $permission]);
        }
    }
}
