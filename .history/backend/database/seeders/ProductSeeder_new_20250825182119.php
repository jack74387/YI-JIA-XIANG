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
                'description' => "堅持使用現宰的健康豬隻，\n低糖、低鹽、低油，不添加色素和防腐劑。\n使用自製豬油，及嚴選通過國家認證的健康醬油，吃起來不死鹹且回甘又健康。\n\n特別將豬肉原有纖維切細讓口感更細緻，\n耗時費工，加工時磨得很細才能入口即化，\n在大滾筒中不停翻炒昇華，隨之而來的是陣陣飄香。\n\n一絲一絲酥脆的蓬鬆肉絲，\n口口都吃的到肉香與豬肉纖維\n最適合老人及幼兒成長食用的肉鬆\n吃過後的回購率也是百分百，讓人嘗一口就愛上的好滋味。",
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961217/yijiaxiang/products/vg1cmoxhizcpq8ubhzvc.jpg',
                // 營養資訊 (每100公克)
                'nutrition_info' => [
                    'calories' => '320大卡',
                    'protein' => '25公克',
                    'fat' => '18公克',
                    'carbohydrates' => '12公克',
                    'sodium' => '850毫克',
                    'sugar' => '8公克'
                ],
                // 成分資訊
                'ingredients' => '豬肉、糖、鹽、醬油、香料',
                'allergens' => '本產品含有大豆製品',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '海苔芝麻豬肉鬆',
                'category_id' => $categories['經典系列'],
                'price_large' => 380,
                'price_small' => 190,
                'unit' => '包',
                'description' => "嚴選健康後腿豬肉，嚴格火侯控制，\n二十幾年練就的手藝炒出金黃色、肉香濃烈的肉鬆。\n而只有最新鮮的豬肉製作出來的肉鬆才看到豬肉最原始的纖維。\n\n入口即化的細纖維絲，吃在嘴裡、甘潤在喉裡，\n加上嚴選的白芝麻和日本海苔，\n芝麻的香、海苔的脆、搭配上肉鬆的酥，\n三種口感和香氣在口中完全融合！\n含在嘴中酥香的口感，霎那間化開的滋味，令人難以忘懷。\n\n肉鬆可直接搭配米，饅頭食用或當作製作壽司的食材，\n肉鬆也可搭配麵包或做成三明治，可以說是百搭啊!!!\n無論哪個年齡層都無法抗拒的美味。",
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961240/yijiaxiang/products/gbzrnipajkdixptsid3a.jpg',
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
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
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
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
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '五香豬肉絲',
                'category_id' => $categories['經典系列'],
                'price_large' => 450,
                'price_small' => 225,
                'unit' => '包',
                'description' => '香辣五香豬肉絲，Q彈有嚼勁。',
                'image' => '/images/products/five-spice-pork-shreds.jpg',
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            
            // 休閒系列
            [
                'name' => '蜜汁豬肉乾',
                'category_id' => $categories['休閒系列'],
                'price_large' => 450,
                'price_small' => 225,
                'unit' => '包',
                'description' => '蜜汁調味，甜鹹適中的豬肉乾。',
                'image' => '/images/products/honey-pork-jerky.jpg',
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '原味豬肉乾',
                'category_id' => $categories['休閒系列'],
                'price_large' => 420,
                'price_small' => 210,
                'unit' => '包',
                'description' => '原汁原味的豬肉乾，保持肉質最自然的美味。',
                'image' => '/images/products/original-pork-jerky.jpg',
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '黑胡椒豬肉乾',
                'category_id' => $categories['休閒系列'],
                'price_large' => 450,
                'price_small' => 225,
                'unit' => '包',
                'description' => '黑胡椒香料調味，口感層次豐富。',
                'image' => '/images/products/black-pepper-pork-jerky.jpg',
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            
            // 特色系列
            [
                'name' => '炭烤豬肉乾',
                'category_id' => $categories['特色系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '炭火烤製，香氣濃郁的豬肉乾。',
                'image' => '/images/products/charcoal-grilled-pork-jerky.jpg',
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
                    ['name' => '隨手包', 'description' => '適合個人享用']
                ]
            ],
            [
                'name' => '辣味豬肉條',
                'category_id' => $categories['特色系列'],
                'price_large' => 460,
                'price_small' => 230,
                'unit' => '包',
                'description' => '微辣開胃，越吃越順口的豬肉條。',
                'image' => '/images/products/spicy-pork-strips.jpg',
                // 營養資訊 (每100公克) - 請自行填入實際數值
                'nutrition_info' => [
                    'calories' => '請填入數值',
                    'protein' => '請填入數值',
                    'fat' => '請填入數值',
                    'carbohydrates' => '請填入數值',
                    'sodium' => '請填入數值',
                    'sugar' => '請填入數值'
                ],
                // 成分資訊 - 請自行填入實際成分
                'ingredients' => '請填入主要成分',
                'allergens' => '請填入過敏原資訊',
                'origin' => '台灣',
                // 包裝規格
                'package_info' => [
                    ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
                    ['name' => '300g 中包裝', 'description' => '適合小家庭'],
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
