<?php
// 測試商品主圖片在各個頁面的顯示
require_once 'backend/vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== 測試商品主圖片顯示 ===\n\n";

// 查找幾個不同的商品
$products = Product::take(5)->get();

foreach ($products as $product) {
    echo "商品 ID: {$product->id}\n";
    echo "商品名稱: {$product->name}\n";
    echo "主圖片 primary_image: ";
    if ($product->primary_image) {
        echo $product->primary_image->image_path . "\n";
    } else {
        echo "無\n";
    }
    echo "原始 image 欄位: " . ($product->image ?? '無') . "\n";
    echo "images 陣列: " . (is_array($product->images) ? json_encode($product->images) : '無') . "\n";
    echo "---\n";
}

echo "\n=== 測試 API 回傳格式 ===\n\n";

// 模擬前端 API 調用
$product = Product::with('category')->first();
if ($product) {
    echo "API 回傳的商品物件結構:\n";
    echo "- id: {$product->id}\n";
    echo "- name: {$product->name}\n";
    echo "- primary_image: " . ($product->primary_image ? 'object with image_path: ' . $product->primary_image->image_path : 'null') . "\n";
    echo "- image: " . ($product->image ?? 'null') . "\n";
    echo "- category: " . ($product->category ? $product->category->name : 'null') . "\n";
}
