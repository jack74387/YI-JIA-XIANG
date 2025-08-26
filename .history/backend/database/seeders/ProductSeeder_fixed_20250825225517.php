<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->truncate();
        $categories = DB::table('categories')->pluck('id', 'name');
        
        $products = [
            // 經典系列
            [
                'name' => '黃金經典豬肉鬆',
                'category_id' => $categories['經典系列'],
                'price_large' => 380,
                'price_small' => 190,
                'unit' => '包',
                'description' => "堅持使用現宰的健康豬隻，\n低糖、低鹽、低油，不添加色素和防腐劑。\n使用自製豬油，及嚴選通過國家認證的健康醬油，吃起來不死鹹且回甘又健康。\n\n特別將豬肉原有纖維切細讓口感更細緻，\n耗時費工，加工時磨得很細才能入口即化，\n在大滾筒中不停翻炒昇華，隨之而來的是陣陣飄香。\n\n一絲一絲酥脆的蓬鬆肉絲，\n口口都吃的到肉香與豬肉纖維，\n最適合老人及幼兒成長食用的肉鬆。\n吃過後的回購率也是百分百，讓人嘗一口就愛上的好滋味。",
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961217/yijiaxiang/products/vg1cmoxhizcpq8ubhzvc.jpg',
                'nutrition_info' => [
                    'calories' => '289大卡',
                    'protein' => '22公克',
                    'fat' => '18公克',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '12公克',
                    'sodium' => '850毫克',
                    'sugar' => '8公克'
                ],
                'ingredients' => '豬肉、砂糖、醬油(黃豆、小麥)、豬油、沙拉油',
                'allergens' => '本產品含有大豆製品',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '海苔芝麻豬肉鬆',
                'category_id' => $categories['經典系列'],
                'price_large' => 380,
                'price_small' => 190,
                'unit' => '包',
                'description' => "嚴選健康後腿豬肉，嚴格火侯控制，\n二十幾年練就的手藝炒出金黃色、肉香濃烈的肉鬆。\n而只有最新鮮的豬肉製作出來的肉鬆才看到豬肉最原始的纖維。\n\n入口即化的細纖維絲，吃在嘴裡、甘潤在喉裡，\n加上嚴選的白芝麻和日本海苔，\n芝麻的香、海苔的脆、搭配上肉鬆的酥，\n三種口感的豐富層次，帶來滿滿的幸福感。",
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1737634652/yijiaxiang/products/dsnuvfcqvs5sddgdapu8.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '豬肉脯',
                'category_id' => $categories['經典系列'],
                'price_large' => 390,
                'price_small' => 195,
                'unit' => '包',
                'description' => '傳統豬肉脯，香氣四溢，入口即化。',
                'image' => '/images/products/pork-floss.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '五香豬肉條',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '五香調味，鹹香適中，越吃越涮嘴。',
                'image' => '/images/products/five-spice-pork-jerky.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '五香豬肉絲',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '精選豬後腿肉，搭配五香秘製配方，鹹香甘甜、越嚼越香，每一口都喚醒兒時記憶。',
                'image' => '/images/products/five-spice-pork-jerky-dry.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            
            // 肉乾系列
            [
                'name' => '蜜汁原味豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 460,
                'price_small' => 230,
                'unit' => '包',
                'description' => '特製蜜汁醃製，甜鹹適中，肉質鮮嫩有嚼勁。',
                'image' => '/images/products/honey-jerky.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '黑胡椒豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 460,
                'price_small' => 230,
                'unit' => '包',
                'description' => '黑胡椒香氣濃郁，微辣開胃，適合下酒。',
                'image' => '/images/products/black-pepper-jerky.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '泰式檸檬豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 560,
                'price_small' => 280,
                'unit' => '包',
                'description' => '泰式檸檬風味，酸甜開胃，清爽不膩。',
                'image' => '/images/products/thai-lemon-jerky.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '杏仁厚片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 540,
                'price_small' => 270,
                'unit' => '包',
                'description' => '厚切豬肉乾搭配杏仁片，口感豐富。',
                'image' => '/images/products/almond-thick-jerky.jpg',
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => [
                    ['name' => '大包裝', 'description' => '適合家庭分享'],
                    ['name' => '中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ]
        ];

        foreach ($products as $product) {
            // 將 JSON 欄位轉換為 JSON 字符串
            if (isset($product['nutrition_info'])) {
                $product['nutrition_info'] = json_encode($product['nutrition_info']);
            }
            if (isset($product['package_info'])) {
                $product['package_info'] = json_encode($product['package_info']);
            }
            
            DB::table('products')->insert($product);
        }
    }
}
