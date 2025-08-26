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
                'description' => "嚴選健康後腿豬肉，嚴格火侯控制，\n二十幾年練就的手藝炒出金黃色、肉香濃烈的肉鬆。\n而只有最新鮮的豬肉製作出來的肉鬆才看到豬肉最原始的纖維。\n\n入口即化的細纖維絲，吃在嘴裡、甘潤在喉裡，\n加上嚴選的白芝麻和日本海苔，\n芝麻的香、海苔的脆、搭配上肉鬆的酥，\n三種口感的豐富層次，帶來滿滿的幸福感。",
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1737634652/yijiaxiang/products/dsnuvfcqvs5sddgdapu8.jpg',
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
            ],
            [
                'name' => '五香豬肉條',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '五香調味，鹹香適中，越吃越涮嘴。',
                'image' => '/images/products/five-spice-pork-jerky.jpg',
            ],
            [
                'name' => '五香豬肉絲',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '精選豬後腿肉，搭配五香秘製配方，鹹香甘甜、越嚼越香，每一口都喚醒兒時記憶。',
                'image' => '/images/products/five-spice-pork-jerky-dry.jpg',
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
            ],
            [
                'name' => '黑胡椒豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 460,
                'price_small' => 230,
                'unit' => '包',
                'description' => '黑胡椒香氣濃郁，微辣開胃，適合下酒。',
                'image' => '/images/products/black-pepper-jerky.jpg',
            ],
            [
                'name' => '泰式檸檬豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 560,
                'price_small' => 280,
                'unit' => '包',
                'description' => '泰式檸檬風味，酸甜開胃，清爽不膩。',
                'image' => '/images/products/thai-lemon-jerky.jpg',
            ],
            [
                'name' => '杏仁厚片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 540,
                'price_small' => 270,
                'unit' => '包',
                'description' => '厚切豬肉乾搭配杏仁片，口感豐富。',
                'image' => '/images/products/almond-thick-jerky.jpg',
            ],
            [
                'name' => '杏仁脆片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 640,
                'price_small' => 320,
                'unit' => '包',
                'description' => '酥脆杏仁片與豬肉乾完美結合，香脆可口。',
                'image' => '/images/products/almond-crispy-jerky.jpg',
            ],
            [
                'name' => '原味薄片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 640,
                'price_small' => 320,
                'unit' => '包',
                'description' => '薄片設計，原味呈現，入口即化。',
                'image' => '/images/products/original-thin-jerky.jpg',
            ],
            [
                'name' => '黑胡椒薄片豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 640,
                'price_small' => 320,
                'unit' => '包',
                'description' => '黑胡椒薄片，香辣夠味，越吃越涮嘴。',
                'image' => '/images/products/black-pepper-thin-jerky.jpg',
            ],
            [
                'name' => '黑胡椒牛肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 700,
                'price_small' => 350,
                'unit' => '包',
                'description' => '嚴選牛肉，黑胡椒調味，香氣十足。',
                'image' => '/images/products/black-pepper-beef-jerky.jpg',
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
            ],
            [
                'name' => '旗魚脯',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 400,
                'price_small' => 200,
                'unit' => '包',
                'description' => '旗魚脯，鮮美可口，營養豐富。',
                'image' => '/images/products/sailfish-jerky.jpg',
            ],
            [
                'name' => '原味魷魚絲',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 720,
                'price_small' => 360,
                'unit' => '包',
                'description' => '原味魷魚絲，Q彈有嚼勁，海味十足。',
                'image' => '/images/products/original-squid-strips.jpg',
            ],
            [
                'name' => '碳烤魷魚絲',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 720,
                'price_small' => 360,
                'unit' => '包',
                'description' => '碳烤風味，香氣撲鼻，越嚼越香。',
                'image' => '/images/products/grilled-squid-strips.jpg',
            ],
            [
                'name' => '魷魚片',
                'category_id' => $categories['海鮮系列'],
                'price_large' => 720,
                'price_small' => 360,
                'unit' => '包',
                'description' => '魷魚片，厚實彈牙，鮮味十足。',
                'image' => '/images/products/squid-slices.jpg',
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
            ],
            [
                'name' => '雲林九號花生',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '雲林九號花生，香氣濃郁，口感細膩。',
                'image' => '/images/products/peanut-no9.jpg',
            ],
            [
                'name' => '焦糖葵瓜子',
                'category_id' => $categories['休閒系列'],
                'price_large' => 200,
                'price_small' => 100,
                'unit' => '包',
                'description' => '焦糖包裹葵瓜子，甜而不膩，越吃越香。',
                'image' => '/images/products/caramel-sunflower.jpg',
            ],
            [
                'name' => '桂圓紅棗葵瓜子',
                'category_id' => $categories['休閒系列'],
                'price_large' => 200,
                'price_small' => 100,
                'unit' => '包',
                'description' => '桂圓紅棗風味，葵瓜子新體驗。',
                'image' => '/images/products/longan-jujube-sunflower.jpg',
            ],
            [
                'name' => '原味牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '經典原味牛軋糖，香濃不黏牙。',
                'image' => '/images/products/nougat-original.jpg',
            ],
            [
                'name' => '咖啡牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '咖啡風味牛軋糖，香氣濃郁。',
                'image' => '/images/products/nougat-coffee.jpg',
            ],
            [
                'name' => '蔓越莓牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '蔓越莓果乾搭配牛軋糖，酸甜好滋味。',
                'image' => '/images/products/nougat-cranberry.jpg',
            ],
            [
                'name' => '抹茶牛軋糖',
                'category_id' => $categories['休閒系列'],
                'price_large' => 0,
                'price_small' => 0,
                'unit' => '包',
                'description' => '抹茶風味牛軋糖，清新回甘。',
                'image' => '/images/products/nougat-matcha.jpg',
            ],
        ];
        foreach ($products as $i => $product) {
            // 自動填入不同 hot/views
            $product['hot'] = rand(10, 100);
            $product['views'] = rand(100, 1000);
            // 若 price_large/price_small 為 null，自動給一個合理值
            if (!isset($product['price_large']) || $product['price_large'] === null) {
                $product['price_large'] = rand(200, 800);
            }
            if (!isset($product['price_small']) || $product['price_small'] === null) {
                $product['price_small'] = rand(100, 400);
            }
            // 補齊商品詳細頁所需欄位
            $product['subtitle'] = $product['subtitle'] ?? '嚴選食材，職人手作';
            $product['rating'] = $product['rating'] ?? round(rand(40, 50) / 10, 1); // 4.0~5.0
            $product['rating_count'] = $product['rating_count'] ?? rand(10, 200);
            $product['sold_count'] = $product['sold_count'] ?? rand(50, 500);
            $product['stock'] = $product['stock'] ?? rand(10, 200);
            $mainImage = $product['image'];
            $product['images'] = json_encode([
                $mainImage,
                '/images/products/sample1.jpg',
                '/images/products/sample2.jpg',
            ]);
            $product['spec'] = $product['spec'] ?? '成分：豬肉、糖、鹽、醬油、香料。\n保存期限：180天。\n產地：台灣。';
            $product['tags'] = json_encode(['人氣', '熱銷', '經典']);
            $product['recommend_ids'] = json_encode([rand(1, 10), rand(11, 20)]);
            $product['delivery'] = json_encode(['宅配', '超商取貨', '門市自取']);
            $product['payment'] = json_encode(['信用卡', '貨到付款', 'LINE Pay']);
            $product['origin_price'] = $product['origin_price'] ?? ($product['price_large'] + 30);
            $product['weight'] = $product['weight'] ?? '250g';
            $product['share_links'] = json_encode([
                'facebook' => 'https://facebook.com/sharer/sharer.php?u=https://yourshop.com/product/'.$i,
                'line' => 'https://social-plugins.line.me/lineit/share?url=https://yourshop.com/product/'.$i,
            ]);
            DB::table('products')->insert($product);
        }
    }
} 