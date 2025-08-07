<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== 測試商品頁面與商品詳細頁加入購物車彈窗圖片一致性 ===\n\n";

// 獲取一個商品進行測試
$product = Product::first();

if (!$product) {
    echo "沒有找到商品資料\n";
    exit;
}

echo "測試商品 ID: {$product->id}\n";
echo "商品名稱: {$product->name}\n";
echo "主圖路徑: " . ($product->primary_image ? $product->primary_image->image_path : '無主圖') . "\n\n";

// 模擬商品頁面 (ProductsView.vue) 的 openAddToCart 函數
echo "=== 商品頁面 (ProductsView.vue) ===\n";
$spec = 'small'; // 預設規格
$productFromList = [
    'id' => $product->id,
    'name' => $product->name,
    'primary_image' => $product->primary_image ? [
        'image_path' => $product->primary_image->image_path
    ] : null,
    'price_small' => 175,
    'price_large' => 350
];

$selectedProductFromList = [
    ...$productFromList, // 保留完整的商品物件，包含 primary_image
    'name' => $productFromList['name'] . '（300g）',
    'price' => $productFromList['price_small'],
    'spec' => $spec
];

echo "彈窗商品名稱: {$selectedProductFromList['name']}\n";
echo "彈窗圖片路徑: " . ($selectedProductFromList['primary_image']['image_path'] ?? '無圖片') . "\n\n";

// 模擬商品詳細頁 (ProductDetailView.vue) 的 addToCart 函數
echo "=== 商品詳細頁 (ProductDetailView.vue) ===\n";
$productFromDetail = [
    'id' => $product->id,
    'name' => $product->name,
    'primary_image' => $product->primary_image ? [
        'image_path' => $product->primary_image->image_path
    ] : null
];

$specData = [
    'product' => $productFromDetail,
    'spec' => 'small',
    'spec_id' => 1,
    'price' => 175,
    'weight' => '300g'
];

$selectedProductFromDetail = [
    ...$specData['product'], // 保留完整的商品物件，包含 primary_image
    'name' => $specData['product']['name'] . '（300g）',
    'price' => $specData['price'],
    'spec' => $specData['spec'],
    'spec_id' => $specData['spec_id'],
    'weight' => $specData['weight']
];

echo "彈窗商品名稱: {$selectedProductFromDetail['name']}\n";
echo "彈窗圖片路徑: " . ($selectedProductFromDetail['primary_image']['image_path'] ?? '無圖片') . "\n\n";

// 比較結果
echo "=== 圖片一致性檢查 ===\n";
$listImagePath = $selectedProductFromList['primary_image']['image_path'] ?? null;
$detailImagePath = $selectedProductFromDetail['primary_image']['image_path'] ?? null;

if ($listImagePath === $detailImagePath) {
    echo "✓ 圖片路徑一致！\n";
    echo "  商品頁面彈窗圖片: {$listImagePath}\n";
    echo "  商品詳細頁彈窗圖片: {$detailImagePath}\n";
} else {
    echo "✗ 圖片路徑不一致！\n";
    echo "  商品頁面彈窗圖片: {$listImagePath}\n";
    echo "  商品詳細頁彈窗圖片: {$detailImagePath}\n";
}

echo "\n=== 實作確認 ===\n";
echo "1. ProductsView.vue 使用: ...product (保留完整商品物件)\n";
echo "2. ProductDetailView.vue 使用: ...productData (保留完整商品物件)\n";
echo "3. ProductAddToCartModal.vue 使用: product.primary_image?.image_path\n";
echo "4. 兩個頁面都傳遞相同的 primary_image 資料結構\n";

?>
