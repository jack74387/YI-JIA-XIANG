<?php
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Product;

echo "=== 更新產品營養資訊 ===\n";

$product = Product::find(1);
if ($product) {
    $product->nutrition_info = [
        'calories' => '320大卡',
        'protein' => '25公克',
        'fat' => '18公克',
        'carbohydrates' => '12公克',
        'sodium' => '850毫克'
    ];
    $product->ingredients = '豬肉、糖、鹽、醬油、香料';
    $product->allergens = '本產品含有大豆製品';
    $product->shelf_life = '60天（未開封）';
    $product->storage_instructions = '常溫保存，開封後請冷藏';
    $product->origin = '台灣';
    $product->package_info = [
        ['name' => '600g 大包裝', 'description' => '適合家庭分享'],
        ['name' => '300g 中包裝', 'description' => '適合小家庭'],
        ['name' => '150g 隨手包', 'description' => '適合個人享用']
    ];
    
    $result = $product->save();
    
    if ($result) {
        echo "✅ 產品 ID {$product->id} 的營養資訊已成功更新\n";
        echo "商品名稱: {$product->name}\n";
        echo "營養資訊: " . json_encode($product->nutrition_info, JSON_UNESCAPED_UNICODE) . "\n";
        echo "主要成分: {$product->ingredients}\n";
        echo "過敏原: {$product->allergens}\n";
        echo "保存期限: {$product->shelf_life}\n";
        echo "保存方式: {$product->storage_instructions}\n";
        echo "產地: {$product->origin}\n";
        echo "包裝規格: " . json_encode($product->package_info, JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "❌ 更新失敗\n";
    }
} else {
    echo "❌ 找不到產品 ID 1\n";
}

echo "\n=== 測試完成 ===\n";
?>
