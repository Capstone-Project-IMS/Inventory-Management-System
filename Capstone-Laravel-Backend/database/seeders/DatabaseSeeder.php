<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @wee UserTypeSeeder
     * @see EmployeeTypeSeeder
     * @see UserSeeder
     * @see LocationSeeder
     * @see StorageLocationSeeder
     * @see FloorLocationSeeder
     * @see ProductSeeder
     * @see ProductDetailsSeeder
     * @see PurchaseOrdersSeeder
     * @see SalesOrder
     * @see ActionSeeder
     * @see PurchaseOrderDetailsSeeder
     * @see 
     * 
     */
    public function run(): void
    {
        // Call seeders
        $this->call(
            [
                UserTypeSeeder::class,
                EmployeeTypeSeeder::class,
                ActionSeeder::class,
                UserSeeder::class,
                LocationSeeder::class,
                StorageLocationSeeder::class,
                FloorLocationSeeder::class,
                ProductSeeder::class,
                ProductDetailsSeeder::class,
                PurchaseOrdersSeeder::class,
                SalesOrderSeeder::class,
                PurchaseOrderDetailsSeeder::class,
                SalesOrderDetailsSeeder::class,


            ]
        );
    }
}
