<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Vendor;
use App\Models\VendorContact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeType;
use Illuminate\Support\Facades\Hash;
use App\Models\UserType;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * CREATE THESE ROWS IN DB BASED ON FACTORY VALUES
       Dont need Employye, Customer or Vendor seeder
       * MODEL
       * @see \App\Models\User
       * FACTORY
       * @see \Database\Factories\UserFactory::definition()
    */
    public function run(): void
    {
        // Chris Info
        $users = [
            [
                'first_name' => 'Chris',
                'last_name' => 'Wright',
                'email' => 'chris@example.com',
                'user_type_id' => UserType::where('role', 'employee')->first()->id,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
            // Jacob Info
            [
                'first_name' => 'Jacob',
                'last_name' => 'Steck',
                'email' => 'jacob@example.com',
                'user_type_id' => UserType::where('role', 'employee')->first()->id,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
            // Montez Info
            [
                'first_name' => 'Montez',
                'last_name' => 'Sibley',
                'email' => 'montez@example.com',
                'user_type_id' => UserType::where('role', 'employee')->first()->id,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
        ];
        // Create Each Of Our Users

        foreach ($users as $user) {
            User::create($user);
            // put us in employee table with management role
            Employee::factory()->create([
                'user_id' => User::where('email', $user['email'])->first()->id,
                'employee_type_id' => EmployeeType::where('role', 'management')->first()->id,
            ]);
        }
        $userTypeCounts = [
            1 => 20,
            2 => 15,
            3 => 15,
        ];

        $vendors = Vendor::all();
        foreach ($userTypeCounts as $role => $count) {
            User::factory()->count($count)->create(['user_type_id' => $role])->each(function ($user) use ($vendors){
                // When making user if their role is employee create an Employee instance from Employee factory
                if ($user->userType->role == 'employee') {
                    Employee::factory()->create([
                        'user_id' => $user->id,
                        'employee_type_id' => EmployeeType::all()->random()->id,
                    ]);
                }
                // make vendor instance from vendor factory
                elseif ($user->userType->role == 'vendor') {
                    VendorContact::factory()->create(['user_id' => $user->id, 'vendor_id' => $vendors->random()->id]);
                } elseif ($user->userType->role == 'customer') {
                    Customer::factory()->create(['user_id' => $user->id]);
                }
            });
        }
    }
}
