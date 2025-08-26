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
            [
                'name' => '黃金經典豬肉鬆',
                'category_id' => $categories['經典系列'],
                'price_large' => 380,
                'price_small' => 190,
                'unit' => '包',
                'description' => "堅持使用現宰的健康豬隻，\n低糖、低鹽、低油，不添加色素和防腐劑。\n使用自製豬油，及嚴選通過國家認證的健康醬油，吃起來不死鹹且回甘又健康。\n\n特別將豬肉原有纖維切細讓口感更細緻，\n耗時費工，加工時磨得很細才能入口即化，\n在大滾筒中不停翻炒昇華，隨之而來的是陣陣飄香。\n\n一絲一絲酥脆的蓬鬆肉絲，\n口口都吃的到肉香與豬肉纖維，\n最適合老人及幼兒成長食用的肉鬆。\n吃過後的回購率也是百分百，讓人嘗一口就愛上的好滋味。",
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961217/yijiaxiang/products/vg1cmoxhizcpq8ubhzvc.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '501大卡',
                    'protein' => '42.5公克',
                    'fat' => '21公克',
                    'saturated_fat' => '9公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '35.5公克',
                    'sodium' => '1050毫克',
                    'sugar' => '31公克'
                ]),
                'ingredients' => '豬肉、砂糖、醬油(黃豆、小麥)、豬油、沙拉油',
                'allergens' => '本產品含有大豆製品',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '海苔芝麻豬肉鬆',
                'category_id' => $categories['經典系列'],
                'price_large' => 380,
                'price_small' => 190,
                'unit' => '包',
                'description' => "嚴選健康後腿豬肉，嚴格火侯控制，\n二十幾年練就的手藝炒出金黃色、肉香濃烈的肉鬆。\n而只有最新鮮的豬肉製作出來的肉鬆才看到豬肉最原始的纖維。\n\n入口即化的細纖維絲，吃在嘴裡、甘潤在喉裡，\n加上嚴選的白芝麻和日本海苔，\n芝麻的香、海苔的脆、搭配上肉鬆的酥，\n三種口感的豐富層次，帶來滿滿的幸福感。",
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1737634652/yijiaxiang/products/dsnuvfcqvs5sddgdapu8.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '480大卡',
                    'protein' => '38.5公克',
                    'fat' => '21.5公克',
                    'saturated_fat' => '9公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '33公克',
                    'sodium' => '950毫克',
                    'sugar' => '26公克'
                ]),
                'ingredients' => '豬肉、砂糖、醬油(黃豆、小麥)、豬油、沙拉油',
                'allergens' => '本產品含有大豆製品',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '豬肉脯',
                'category_id' => $categories['經典系列'],
                'price_large' => 390,
                'price_small' => 195,
                'unit' => '包',
                'description' => '傳統豬肉脯，香氣四溢，入口即化。',
                'image' => '/images/products/pork-floss.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '豬肉、砂糖、醬油(黃豆、小麥)、豬油、沙拉油',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '五香豬肉條',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '五香調味，鹹香適中，越吃越涮嘴。',
                'image' => '/images/products/five-spice-pork-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '五香豬肉絲',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '精選豬後腿肉，搭配五香秘製配方，鹹香甘甜、越嚼越香，每一口都喚醒兒時記憶。',
                'image' => '/images/products/five-spice-pork-jerky-dry.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
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
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '黑胡椒豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 460,
                'price_small' => 230,
                'unit' => '包',
                'description' => '黑胡椒香氣濃郁，微辣開胃，適合下酒。',
                'image' => '/images/products/black-pepper-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '泰式檸檬豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 560,
                'price_small' => 280,
                'unit' => '包',
                'description' => '泰式檸檬風味，酸甜開胃，清爽不膩。',
                'image' => '/images/products/thai-lemon-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '杏仁厚片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 540,
                'price_small' => 270,
                'unit' => '包',
                'description' => '厚切豬肉乾搭配杏仁片，口感豐富。',
                'image' => '/images/products/almond-thick-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '杏仁脆片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 640,
                'price_small' => 320,
                'unit' => '包',
                'description' => '酥脆杏仁片與豬肉乾完美結合，香脆可口。',
                'image' => '/images/products/almond-crispy-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '原味薄片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 640,
                'price_small' => 320,
                'unit' => '包',
                'description' => '薄片設計，原味呈現，入口即化。',
                'image' => '/images/products/original-thin-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '黑胡椒薄片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 640,
                'price_small' => 320,
                'unit' => '包',
                'description' => '黑胡椒薄片，香辣夠味，越吃越涮嘴。',
                'image' => '/images/products/black-pepper-thin-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '黑胡椒牛肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 700,
                'price_small' => 350,
                'unit' => '包',
                'description' => '嚴選牛肉，黑胡椒調味，香氣十足。',
                'image' => '/images/products/black-pepper-beef-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            // 海鮮系列
            [
                'name' => '旗魚鬆',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 400,
                'price_small' => 200,
                'unit' => '包',
                'description' => '旗魚鬆，細緻綿密，入口即化。',
                'image' => '/images/products/sailfish-floss.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '旗魚脯',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 400,
                'price_small' => 200,
                'unit' => '包',
                'description' => '旗魚脯，鮮美可口，營養豐富。',
                'image' => '/images/products/sailfish-jerky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '原味魷魚絲',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 720,
                'price_small' => 360,
                'unit' => '包',
                'description' => '原味魷魚絲，Q彈有嚼勁，海味十足。',
                'image' => '/images/products/original-squid-strips.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '碳烤魷魚絲',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 720,
                'price_small' => 360,
                'unit' => '包',
                'description' => '碳烤風味，香氣撲鼻，越嚼越香。',
                'image' => '/images/products/grilled-squid-strips.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '魷魚片',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 720,
                'price_small' => 360,
                'unit' => '包',
                'description' => '魷魚片，厚實彈牙，鮮味十足。',
                'image' => '/images/products/squid-slices.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            // 休閒系列
            [
                'name' => '雲林黑金剛花生',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '嚴選雲林黑金剛花生，顆粒飽滿，香脆可口。',
                'image' => '/images/products/peanut-black.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '雲林九號花生',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '雲林九號花生，香氣濃郁，口感細膩。',
                'image' => '/images/products/peanut-no9.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '焦糖葵瓜子',
                'category_id' => $categories['休閒系列'],
                'price_large' => 200,
                'price_small' => 100,
                'unit' => '包',
                'description' => '焦糖包裹葵瓜子，甜而不膩，越吃越香。',
                'image' => '/images/products/caramel-sunflower.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '桂圓紅棗葵瓜子',
                'category_id' => $categories['休閒系列'],
                'price_large' => 200,
                'price_small' => 100,
                'unit' => '包',
                'description' => '桂圓紅棗風味，葵瓜子新體驗。',
                'image' => '/images/products/longan-jujube-sunflower.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '原味牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '經典原味牛軋糖，香濃不黏牙。',
                'image' => '/images/products/nougat-original.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '咖啡牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '咖啡風味牛軋糖，香氣濃郁。',
                'image' => '/images/products/nougat-coffee.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '蔓越莓牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '蔓越莓果乾搭配牛軋糖，酸甜好滋味。',
                'image' => '/images/products/nougat-cranberry.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
            [
                'name' => '抹茶牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '抹茶風味牛軋糖，清新回甘。',
                'image' => '/images/products/nougat-matcha.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'saturated_fat' => '請填入數值',
                    'trans_fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ]),
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                'package_info' => json_encode([
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ])
            ],
        ];
        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}