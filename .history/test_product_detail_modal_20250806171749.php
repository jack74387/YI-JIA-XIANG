<?php
// 測試商品詳細頁加入購物車彈窗的圖片顯示

require_once 'backend/bootstrap/app.php';

use App\Models\Product;

echo "=== 測試商品詳細頁加入購物車彈窗圖片 ===\n\n";

// 獲取幾個商品測試
$products = Product::with('primaryImage')->limit(3)->get();

foreach ($products as $product) {
    echo "商品 ID: {$product->id}\n";
    echo "商品名稱: {$product->name}\n";
    
    // 模擬前端 ProductDetailView.vue 中的 loadProduct 後的商品資料
    $productData = [
        'id' => $product->id,
        'name' => $product->name,
        'primary_image' => $product->primary_image ? [
            'image_path' => $product->primary_image->image_path
        ] : null,
        'specs' => $product->specs->map(function($spec) {
            return [
                'id' => $spec->id,
                'size' => $spec->size,
                'price' => $spec->price,
                'weight' => $spec->weight
            ];
        })
    ];
    
    echo "主圖路徑: " . ($productData['primary_image']['image_path'] ?? '無主圖') . "\n";
    
    // 模擬加入購物車時的 specData
    if (!empty($productData['specs'])) {
        $firstSpec = $productData['specs'][0];
        echo "規格資料:\n";
        echo "  - 規格 ID: {$firstSpec['id']}\n";
        echo "  - 規格大小: {$firstSpec['size']}\n";
        echo "  - 價格: {$firstSpec['price']}\n";
        echo "  - 重量: {$firstSpec['weight']}\n";
        
        // 模擬 addToCart 函數中的 selectedProduct
        $selectedProduct = [
            'id' => $productData['id'],
            'name' => $productData['name'] . "（" . ($firstSpec['size'] === 'large' ? '600g' : '300g') . "）",
            'price' => $firstSpec['price'],
            'spec' => $firstSpec['size'],
            'spec_id' => $firstSpec['id'],
            'weight' => $firstSpec['weight'],
            'primary_image' => $productData['primary_image'] // 保留完整的 primary_image
        ];
        
        echo "\n加入購物車彈窗商品資料:\n";
        echo "  - 彈窗標題: {$selectedProduct['name']}\n";
        echo "  - 彈窗價格: {$selectedProduct['price']}\n";
        echo "  - 彈窗圖片路徑: " . ($selectedProduct['primary_image']['image_path'] ?? '無圖片') . "\n";
    }
    
    echo str_repeat("-", 50) . "\n\n";
}

echo "測試完成！\n";
echo "確認要點:\n";
echo "1. 商品詳細頁的 primary_image 是否正確從資料庫載入\n";
echo "2. addToCart 函數是否保留了完整的 primary_image 資料\n";
echo "3. ProductAddToCartModal 組件是否使用 product.primary_image?.image_path\n";
?>
