<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('inventories')->insert([
                'product_id' => $product->id,
                'quantity' => rand(20, 100),
                'alert_level' => 10,
            ]);
        }
    }
} 