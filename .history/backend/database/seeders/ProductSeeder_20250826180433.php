<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 使用 delete 而不是 truncate 來避免外鍵約束問題
        DB::table('products')->delete();
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
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961240/yijiaxiang/products/gbzrnipajkdixptsid3a.jpg',
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
                'description' => '精心挑選的健康新鮮後腿豬肉和獨門匠法製造
                                    保持豬肉原有纖維再搭配黃金比例的祕方。
                                    低鹽、低糖、低油，不添加任何人工香料，
                                    保證天然健康有保障。

                                    純手工在滾筒中不停翻炒，祕方醬汁慢火烘培，
                                    留住豬肉本身最原始的風味，保留豬肉原本清晰的豬肉絲纖維，
                                    不同於肉鬆細緻的口感，肉質香軟扎實有嚼勁、絲絲入口、香味四溢， 
                                    重拾記憶中懷舊的味道，值得您細細品味。

                                    古早味豬肉脯絕對是您下酒、配稀飯、做美味飯糰的最佳選擇，
                                    讓平淡的稀飯更加美味，讓營養加分!
                                    讓人只想配著白飯一口接一口吃光光。',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961258/yijiaxiang/products/ypcxrn6fnzzipq4jkaky.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '352大卡',
                    'protein' => '32公克',
                    'fat' => '14公克',
                    'saturated_fat' => '6公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '24公克',
                    'sodium' => '1010毫克',
                    'sugar' => '23公克'
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
                'name' => '五香豬肉條',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '老闆親自嚴選的新鮮上等豬後腿肉，以獨特秘方香料醃漬調味，使各種香味混合，昇華成本店獨一無二的滋味。手工保留豬肉纖維，咬勁十足 ，再以手工慢火烘烤，肉條色澤自然。是本店的招牌商品。',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961315/yijiaxiang/products/v9litrvcao3udpymolj0.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '385大卡',
                    'protein' => '32.5公克',
                    'fat' => '13.5公克',
                    'saturated_fat' => '4.2公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '33.5公克',
                    'sodium' => '1150毫克',
                    'sugar' => '30.5公克'
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
                'name' => '五香豬肉絲',
                'category_id' => $categories['經典系列'],
                'price_large' => 480,
                'price_small' => 240,
                'unit' => '包',
                'description' => '老闆親自嚴選的新鮮上等豬後腿肉，以獨特秘方香料醃漬而成使各種香味混合而出的本店獨一無二滋味。豬肉絲相較豬肉條而已，較為軟嫩，是個老少咸宜的休閒食品呢！',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961344/yijiaxiang/products/rz0fsdlz6ksdhke8vzcl.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '385大卡',
                    'protein' => '32.5公克',
                    'fat' => '13.5公克',
                    'saturated_fat' => '4.2公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '33.5公克',
                    'sodium' => '1150毫克',
                    'sugar' => '30.5公克'
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
            // 肉乾系列
            [
                'name' => '蜜汁原味豬肉乾',
                'category_id' => $categories['肉乾系列'],
                'price_large' => 460,
                'price_small' => 230,
                'unit' => '包',
                'description' => '店長及顧客們都喜愛的美味，
                                    由後腿肉製造而成的肉乾
                                    軟而厚實 綿密甘甜
                                    厚度接近一公分，一口咬下
                                    蜜汁的甜以及肉乾的鹹味混合，真是絕配啊!!!!!!!',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961420/yijiaxiang/products/ednsgqjwt3stykcjl4zf.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '303大卡',
                    'protein' => '22.5公克',
                    'fat' => '3.7公克',
                    'saturated_fat' => '1.3公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '45公克',
                    'sodium' => '925毫克',
                    'sugar' => '36公克'
                ]),
                'ingredients' => '豬肉、砂糖、醬油(黃豆、小麥)',
                'allergens' => '本產品含有大豆製品',
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
                    'calories' => '285.2大卡',
                    'protein' => '27.3公克',
                    'fat' => '4.4公克',
                    'saturated_fat' => '1.5公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '34.1公克',
                    'sodium' => '682毫克',
                    'sugar' => '29.9公克'
                ]),
                'ingredients' => '豬肉、糖、黑胡椒、醬油、味精',
                'allergens' => '本產品含有大豆製品',
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
                'description' => '堅持選用台灣豬肉製作，
                                    淡淡檸檬香佐微微甜味，
                                    咀嚼後，慢慢感受到一股辣勁，
                                    刺激您的味蕾，清香且不膩，
                                    忍不住想一口接一口，
                                    泰式檸檬肉乾是年輕朋友的最愛，
                                    如果您愛嚐鮮，千萬不要錯過香辣好滋味！',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961490/yijiaxiang/products/k8ss6pxcgbazcbgy5wcx.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '330.3大卡',
                    'protein' => '26.5公克',
                    'fat' => '5.1公克',
                    'saturated_fat' => '1.9公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '44.6公克',
                    'sodium' => '1121毫克',
                    'sugar' => '32.5公克'
                ]),
                'ingredients' => '豬肉、糖、醬油、檸檬油、辣椒粉',
                'allergens' => '本產品含有大豆製品',
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
                'description' => '和杏仁薄片豬肉乾不同的是，它擁有一定的厚度與脆度，鹹、香、脆又帶有一點嚼勁，獨特的口感與風味，別有新意，和一般傳統肉乾大不同！！！！令人食指大動！',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961525/yijiaxiang/products/anui0dnkftyvs9rgfs16.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '440大卡',
                    'protein' => '27.6公克',
                    'fat' => '18.3公克',
                    'saturated_fat' => '3.6公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '40.6公克',
                    'sodium' => '1180毫克',
                    'sugar' => '25.6公克'
                ]),
                'ingredients' => '豬肉、杏仁、糖、醬油、豬油、沙拉油',
                'allergens' => '本產品含有堅果類',
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
                'description' => '在尋找那夢中口齒留香的好滋味嗎？
                                    嚐嚐這款杏仁脆片肉乾！
                                    薄薄脆脆的肉乾
                                    搭上特有的鹹香
                                    就是最魂牽夢縈的夢中美食',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961577/yijiaxiang/products/zoa5c1k56978okj2omrb.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '455.9大卡',
                    'protein' => '30.8公克',
                    'fat' => '16.7公克',
                    'saturated_fat' => '3.3公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '45.6公克',
                    'sodium' => '935毫克',
                    'sugar' => '33.2公克'
                ]),
                'ingredients' => '豬肉、杏仁、糖、醬油、豬油、沙拉油',
                'allergens' => '本產品含有堅果類',
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
                'description' => '選用最高級的溫體豬後腿心肉精製，
                                    和傳統厚片豬肉乾不同，片片晶瑩剔透幾可透光的薄度，加上細緻綿密的口感
                                    用最少的調味來突顯出食材的鮮美原味，
                                    經過手工烘烤，高溫燒烤過後薄片豬肉乾
                                    絕對會顛覆您對肉干的刻板印象，讓您一吃就上癮，停都停不了。',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961625/yijiaxiang/products/o8mcwpymfbs6mikrwpzr.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '389大卡',
                    'protein' => '28.6公克',
                    'fat' => '10.2公克',
                    'saturated_fat' => '3.9公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '45.7公克',
                    'sodium' => '842毫克',
                    'sugar' => '38公克'
                ]),
                'ingredients' => '豬肉、糖、醬油、味精',
                'allergens' => '本產品含有大豆製品',
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
                'description' => '和傳統厚片豬肉乾不同，
                                    在薄片肉乾上灑上黑胡椒粒，
                                    經過手工烘烤，高溫燒烤過後的黑胡椒薄片豬肉乾，
                                    香氣中帶著濃郁的黑胡椒香味，
                                    有淡淡的香辣、口感較硬，但卻越嚼越香。
                                    最適合喝酒、談天的點心，令人連回味都會垂涎三尺。',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961643/yijiaxiang/products/xlrrhmjkm0w4xiziznbz.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '343.2大卡',
                    'protein' => '31.8公克',
                    'fat' => '6公克',
                    'saturated_fat' => '2.1公克',
                    'trans_fat' => '0公克',
                    'carbohydrates' => '40.5公克',
                    'sodium' => '917毫克',
                    'sugar' => '21.1公克'
                ]),
                'ingredients' => '豬肉、糖、醬油、黑胡椒',
                'allergens' => '本產品含有大豆製品',
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
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961667/yijiaxiang/products/vlcveljwqydudsjqw2np.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '410.2大卡',
                    'protein' => '410.2大卡',
                    'fat' => '410.2大卡',
                    'saturated_fat' => '410.2大卡',
                    'trans_fat' => '410.2大卡',
                    'carbohydrates' => '410.2大卡',
                    'sodium' => '410.2大卡',
                    'sugar' => '410.2大卡'
                ]),
                'ingredients' => '牛肉、',
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
                'description' => '嚴選新鮮旗魚的中段部位去除雜質， 
                                    小火慢炒，焙炒至呈現金黃色澤的顆粒狀，
                                    甘甜的魚汁都鎖在魚鬆裡， 
                                    酥香入口即融，易嚼易消化，每口都含有旗魚的鮮美
                                    旗魚蛋白質含量高，營養價值極高，
                                    適合當各式各樣食品的佐料，又好吃又營養。',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961686/yijiaxiang/products/n62zapnzdlxtvmw7oh3n.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '410.2大卡',
                    'protein' => '410.2大卡',
                    'fat' => '410.2大卡',
                    'saturated_fat' => '410.2大卡',
                    'trans_fat' => '410.2大卡',
                    'carbohydrates' => '410.2大卡',
                    'sodium' => '410.2大卡',
                    'sugar' => '410.2大卡'
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
                'description' => '嚴選現捕旗魚，採用紮實、肉質飽滿、具彈性的新鮮旗魚，
                                    特調獨家醬汁慢火烘培、烘炒，
                                    傳承老味道的風華，
                                    保留魚本身自然甜味，
                                    口感清甜、細緻清爽、香味四溢，
                                    絕對可以滿足您的味蕾。',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961712/yijiaxiang/products/kgnbdtjfhh64ifnibgyl.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '410.2大卡',
                    'protein' => '410.2大卡',
                    'fat' => '410.2大卡',
                    'saturated_fat' => '410.2大卡',
                    'trans_fat' => '410.2大卡',
                    'carbohydrates' => '410.2大卡',
                    'sodium' => '410.2大卡',
                    'sugar' => '410.2大卡'
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
                'description' => '嚴選新鮮的魷魚，以超低溫急速冷凍保留魷魚的鮮味。
                                    將魷魚切成絲狀，
                                    除了可以品嚐出淡淡香氣，
                                    也可以咀嚼出魷魚的鮮及魷魚的甜!!!
                                    一口接一口，最能打發時間的零嘴!!!',
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961737/yijiaxiang/products/bniscir37nbwsdmhpquu.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '410.2大卡',
                    'protein' => '410.2大卡',
                    'fat' => '410.2大卡',
                    'saturated_fat' => '410.2大卡',
                    'trans_fat' => '410.2大卡',
                    'carbohydrates' => '410.2大卡',
                    'sodium' => '410.2大卡',
                    'sugar' => '410.2大卡'
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
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961771/yijiaxiang/products/eizmpzuklbqqdrc3pqzu.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '410.2大卡',
                    'protein' => '410.2大卡',
                    'fat' => '410.2大卡',
                    'saturated_fat' => '410.2大卡',
                    'trans_fat' => '410.2大卡',
                    'carbohydrates' => '410.2大卡',
                    'sodium' => '410.2大卡',
                    'sugar' => '410.2大卡'
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
                'image' => 'https://res.cloudinary.com/daeb3goxf/image/upload/v1755961786/yijiaxiang/products/uefzp0qaclbd8pxxwwpd.jpg',
                'nutrition_info' => json_encode([
                    'calories' => '410.2大卡',
                    'protein' => '410.2大卡',
                    'fat' => '410.2大卡',
                    'saturated_fat' => '410.2大卡',
                    'trans_fat' => '410.2大卡',
                    'carbohydrates' => '410.2大卡',
                    'sodium' => '410.2大卡',
                    'sugar' => '410.2大卡'
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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
                    'calories' => '',
                    'protein' => '',
                    'fat' => '',
                    'saturated_fat' => '',
                    'trans_fat' => '',
                    'carbohydrates' => '',
                    'sodium' => '',
                    'sugar' => ''
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