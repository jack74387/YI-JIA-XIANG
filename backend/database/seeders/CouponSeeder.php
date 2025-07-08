<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME95',
                'type' => 'percent',
                'value' => 95,
                'min_order' => 500,
                'expires_at' => now()->addMonths(3),
                'usage_limit' => 100,
                'used_count' => 0,
                'description' => '新客首購95折',
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 120,
                'min_order' => 1000,
                'expires_at' => now()->addMonths(6),
                'usage_limit' => 200,
                'used_count' => 0,
                'description' => '滿千免運',
            ],
        ];
        foreach ($coupons as $coupon) {
            DB::table('coupons')->insert($coupon);
        }
    }
} 