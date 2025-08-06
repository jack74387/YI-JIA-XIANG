<?php

require_once 'vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 測試產品的 is_featured 字段
$product = \App\Models\Product::first();

echo "測試修復前後的 is_featured 字段:\n";
echo "Product ID: " . $product->id . "\n";
echo "Product Name: " . $product->name . "\n";
echo "is_featured (原始): " . var_export($product->getAttributes()['is_featured'], true) . "\n";
echo "is_featured (轉換後): " . var_export($product->is_featured, true) . "\n";
echo "類型: " . gettype($product->is_featured) . "\n";

// 設置為精選商品
$product->is_featured = true;
$product->featured_order = 1;
$product->save();

echo "\n設置為精選商品後:\n";
echo "is_featured: " . var_export($product->is_featured, true) . "\n";
echo "featured_order: " . var_export($product->featured_order, true) . "\n";

// 重新從資料庫獲取
$product = \App\Models\Product::find($product->id);
echo "\n重新從資料庫獲取:\n";
echo "is_featured: " . var_export($product->is_featured, true) . "\n";
echo "featured_order: " . var_export($product->featured_order, true) . "\n";

echo "\nJSON 序列化結果:\n";
echo json_encode([
    'id' => $product->id,
    'name' => $product->name,
    'is_featured' => $product->is_featured,
    'featured_order' => $product->featured_order
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
