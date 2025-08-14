<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => '台東總店',
                'address' => '台東市廣東路269號',
                'phone' => '089-357996',
                'hours' => '週一至週六 08:00-20:00',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.902!2d121.150000!3d22.750000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3468e9b1b1b1b1b1%3A0x1111111111111111!2z5Y-w5Lit5bGx6KW_5Y2X6Zmi!5e0!3m2!1szh-TW!2stw!4v1710000000000!5m2!1szh-TW!2stw',
                'map_link' => 'https://www.google.com/maps/search/?api=1&query=台東市廣東路269號',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '台北分店',
                'address' => '台北市信義區松仁路456號',
                'phone' => '02-23456789',
                'hours' => '週一至週日 10:00-22:00',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3621.902!2d121.560000!3d25.030000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3442abc1b1b1b1b1%3A0x2222222222222222!2z5Y-w5Lit5bGx6KW_5Y2X6Zmi!5e0!3m2!1szh-TW!2stw!4v1710000000001!5m2!1szh-TW!2stw',
                'map_link' => 'https://www.google.com/maps/search/?api=1&query=台北市信義區松仁路456號',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
