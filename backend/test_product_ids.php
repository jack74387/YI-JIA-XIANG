<?php
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Product;

echo "=== 檢查已發布商品 ID ===\n";

$products = Product::where('published', 1)
    ->take(5)
    ->get(['id', 'name']);

foreach ($products as $product) {
    echo "ID: {$product->id}, Name: {$product->name}\n";
}

echo "\n=== 測試推薦 API ===\n";

if ($products->count() > 0) {
    $testProductId = $products->first()->id;
    echo "測試商品 ID: {$testProductId}\n";
    
    // 模擬 API 調用
    $recommendations = Product::where('published', 1)
        ->where('id', '!=', $testProductId)
        ->take(3)
        ->get(['id', 'name']);
    
    echo "推薦商品:\n";
    foreach ($recommendations as $rec) {
        echo "  - ID: {$rec->id}, Name: {$rec->name}\n";
    }
}
?>
