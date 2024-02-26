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
                'user_type_id' => UserType::where('role', 'admin')->first()->id,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
            // Jacob Info
            [
                'first_name' => 'Jacob',
                'last_name' => 'Steck',
                'email' => 'jacob@example.com',
                'user_type_id' => UserType::where('role', 'admin')->first()->id,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
            // Montez Info
            [
                'first_name' => 'Montez',
                'last_name' => 'Sibley',
                'email' => 'montez@example.com',
                'user_type_id' => UserType::where('role', 'admin')->first()->id,
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

        // changed to make it dynamic incase of adding more roles
        $employeeTypeId = UserType::where('role', 'employee')->first()->id;
        $customerTypeId = UserType::where('role', 'customer')->first()->id;
        $vendorTypeId = UserType::where('role', 'vendor')->first()->id;
        $userTypeCounts = [
            $employeeTypeId => 20,
            $customerTypeId => 15,
            $vendorTypeId => 15,
        ];
        // gets vendors to create 15 vendor contacts
        $vendors = Vendor::all();
        foreach ($userTypeCounts as $role => $count) {
            if ($role == UserType::where('role', 'admin')->first()->id)
                continue;
            User::factory()->count($count)->userType($role)->create();
        }

        User::all()->each(function ($user) use ($vendors) {
            // When making user if their role is employee create an Employee instance from Employee factory
            if ($user->type == 'employee') {
                Employee::factory()->create([
                    'user_id' => $user->id,
                    'employee_type_id' => EmployeeType::all()->random()->id,
                ]);
            }
            // make vendor contact instance from vendor contact factory
            elseif ($user->type == 'vendor') {
                VendorContact::factory()->create([
                    'user_id' => $user->id,
                    'vendor_id' => $vendors->random()->id,
                ]);
            } elseif ($user->type == 'customer') {
                Customer::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
