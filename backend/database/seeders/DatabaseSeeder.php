<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('coupons')->truncate(); // 這行會清空資料表
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
