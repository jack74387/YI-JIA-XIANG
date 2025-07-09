<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('categories')->truncate();
        $categories = [
            ['name' => '經典系列', 'description' => '經典人氣肉鬆、肉條，傳承數十年手藝。', 'image' => '/images/categories/classic.png'],
            ['name' => '肉乾系列', 'description' => '多種口味肉乾，蜜汁、黑胡椒、原味等。', 'image' => '/images/categories/jerky.png'],
            ['name' => '海鮮系列', 'description' => '嚴選海味，魷魚等。', 'image' => '/images/categories/seafood.png'],
            ['name' => '休閒系列', 'description' => '多樣化休閒零嘴，搭配茶飲最佳選擇。', 'image' => '/images/categories/snack.png'],
        ];
        foreach ($categories as $cat) {
            DB::table('categories')->insert($cat);
        }
    }
} 