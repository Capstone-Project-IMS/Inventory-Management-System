<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @see Action
     * @see \Database\Factories\ActionFactory
     */
    public function run(): void
    {
        $actions = ["received", "shipped", "returned", "sold", "purchased", "created", "audited", "deleted", "approved", "rejected", "cancelled", "uncancelled", "picked", "packed", "to floor", "to storage"];
        foreach ($actions as $action) {
            Action::create([
                'action' => $action,
            ]);
        }
    }
}
