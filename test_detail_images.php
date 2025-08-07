<?php
// 測試商品詳細頁面和管理頁面的圖片顯示
require_once 'backend/vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== 測試商品詳細頁面和管理頁面圖片顯示 ===\n\n";

// 查找幾個不同的商品
$products = Product::take(5)->get();

echo "檢查商品的主圖片設定:\n\n";

foreach ($products as $product) {
    echo "商品 ID: {$product->id}\n";
    echo "商品名稱: {$product->name}\n";
    echo "主圖片欄位 (image): " . ($product->image ?? '無') . "\n";
    
    // 測試 primary_image accessor
    $primaryImage = $product->primary_image;
    echo "primary_image accessor: ";
    if ($primaryImage && isset($primaryImage->image_path)) {
        echo $primaryImage->image_path . "\n";
    } else {
        echo "無\n";
    }
    
    // 檢查是否一致
    $isConsistent = ($product->image === $primaryImage->image_path);
    echo "主圖片和 primary_image 是否一致: " . ($isConsistent ? '是' : '否') . "\n";
    
    echo "images 陣列: ";
    if (is_array($product->images) && count($product->images) > 0) {
        echo json_encode($product->images) . "\n";
    } else {
        echo "無\n";
    }
    
    echo "---\n";
}

echo "\n=== 模擬前端商品詳細頁面的 getAllImages() 函數 ===\n\n";

$testProduct = Product::first();
if ($testProduct) {
    echo "測試商品: {$testProduct->name}\n";
    
    // 模擬 getAllImages() 函數邏輯
    $arr = [];
    if ($testProduct->primary_image && $testProduct->primary_image->image_path) {
        $arr[] = $testProduct->primary_image->image_path;
    }
    if (is_array($testProduct->images)) {
        foreach ($testProduct->images as $img) {
            if ($img && !in_array($img, $arr)) {
                $arr[] = $img;
            }
        }
    }
    $result = array_slice($arr, 0, 10);
    
    echo "getAllImages() 結果: " . json_encode($result) . "\n";
    echo "第一張圖片 (主圖): " . ($result[0] ?? '無') . "\n";
}
