<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 創建測試用戶
        User::create([
            'name' => '測試用戶',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'points' => 1000,
            'member_level' => 'silver',
        ]);

        User::create([
            'name' => '管理員',
            'email' => 'admin@yijiaxiang.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'points' => 5000,
            'member_level' => 'platinum',
        ]);

        // 創建更多測試用戶
        User::factory(10)->create([
            'points' => rand(0, 2000),
        ]);
    }
} 