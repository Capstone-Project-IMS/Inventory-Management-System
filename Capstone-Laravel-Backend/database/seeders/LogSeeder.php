<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use App\Models\Action;
use App\Models\Log;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseOrders = PurchaseOrder::all();
        foreach ($purchaseOrders as $purchaseOrder) {
            $action = Action::where('action', 'purchased')->first();
            Log::factory()->create([
                'action_id' => $action->id,
                'product_details_id' => $purchaseOrder->product_detail_id,
            ]);
        }
    }
}
