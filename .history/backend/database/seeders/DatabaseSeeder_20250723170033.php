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
        // 關閉外鍵檢查
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('coupons')->truncate();

        // 建立 admin
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('123'),
                'is_admin' => 1,
            ]
        );

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            InventorySeeder::class,
            // StoreSeeder::class, // 新增這一行
        ]);
        // 開啟外鍵檢查
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
