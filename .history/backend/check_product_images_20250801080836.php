<?php

require_once 'vendor/autoload.php';

use App\Models\Product;

// 檢查商品圖片路徑
$products = Product::with('category', 'images')->limit(3)->get();

foreach ($products as $product) {
    echo "商品: {$product->name}\n";
    echo "主要圖片: {$product->primary_image}\n";
    
    if ($product->images->count() > 0) {
        echo "相關圖片:\n";
        foreach ($product->images as $image) {
            echo "  - {$image->image_path}\n";
        }
    } else {
        echo "沒有相關圖片\n";
    }
    echo "---\n";
}
