<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== 測試商品詳細頁加入購物車彈窗圖片 ===\n\n";

// 獲取幾個商品測試
$products = Product::limit(3)->get();

foreach ($products as $product) {
    echo "商品 ID: {$product->id}\n";
    echo "商品名稱: {$product->name}\n";
    echo "image 欄位: " . ($product->image ?? '無') . "\n";
    
    // 測試 primary_image accessor
    $primaryImageData = $product->primary_image;
    if ($primaryImageData) {
        echo "Primary Image Accessor: {$primaryImageData->image_path}\n";
    } else {
        echo "Primary Image Accessor: null\n";
    }
    
    echo str_repeat("-", 30) . "\n\n";
}

echo "測試完成！\n";
echo "確認要點:\n";
echo "1. 商品詳細頁的 primary_image 是否正確從資料庫載入\n";
echo "2. addToCart 函數是否保留了完整的 primary_image 資料\n";
echo "3. ProductAddToCartModal 組件是否使用 product.primary_image?.image_path\n";
?>
