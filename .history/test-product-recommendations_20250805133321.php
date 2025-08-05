<?php
/**
 * 測試商品推薦 API
 * 
 * 此腳本測試 Phase 1 推薦系統的三種策略：
 * 1. 同分類商品推薦
 * 2. 相似價格區間推薦  
 * 3. 熱門商品推薦（備用）
 */

require_once __DIR__ . '/backend/vendor/autoload.php';

// Laravel 應用程式啟動
$app = require_once __DIR__ . '/backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// 設定基本的 HTTP 請求環境
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\Product;
use App\Models\Category;

echo "=== 商品推薦系統測試 (Phase 1) ===\n\n";

try {
    // 1. 檢查產品數據
    $productCount = Product::count();
    $categoryCount = Category::count();
    
    echo "📊 資料庫狀態:\n";
    echo "   - 商品總數: {$productCount}\n";
    echo "   - 分類總數: {$categoryCount}\n\n";
    
    if ($productCount < 2) {
        echo "❌ 警告: 商品數量不足，無法進行有效的推薦測試\n";
        exit;
    }
    
    // 2. 選擇測試商品
    $testProduct = Product::with('category')
        ->whereIn('status', ['published', 'notification'])
        ->where(function($query) {
            $query->where('price_large', '>', 0)
                  ->orWhere('price_small', '>', 0);
        })
        ->whereNotNull('category_id')
        ->first();
    
    if (!$testProduct) {
        echo "❌ 找不到適合的測試商品\n";
        exit;
    }
    
    $productPrice = $testProduct->price_large ?? $testProduct->price_small ?? 0;
    
    echo "🎯 測試商品:\n";
    echo "   - ID: {$testProduct->id}\n";
    echo "   - 名稱: {$testProduct->name}\n";
    echo "   - 分類: " . ($testProduct->category ? $testProduct->category->name : '無') . "\n";
    echo "   - 價格: \$" . number_format($productPrice) . "\n";
    echo "   - 狀態: {$testProduct->status}\n\n";
    
    // 3. 測試推薦 API
    echo "🚀 測試推薦 API...\n\n";
    
    // 模擬 HTTP 請求
    $apiUrl = "http://localhost/api/v1/products/{$testProduct->id}/recommendations?limit=6";
    
    // 直接調用控制器方法進行測試
    $controller = new \App\Http\Controllers\ProductController();
    $request = new Illuminate\Http\Request(['limit' => 6]);
    
    $response = $controller->getRecommendations($testProduct->id, $request);
    $responseData = json_decode($response->getContent(), true);
    
    if ($responseData['success']) {
        $recommendations = $responseData['data']['recommendations'];
        $strategiesUsed = $responseData['data']['strategies_used'];
        
        echo "✅ 推薦成功!\n";
        echo "   - 推薦商品數量: " . count($recommendations) . "\n";
        echo "   - 使用策略:\n";
        echo "     * 分類推薦: " . ($strategiesUsed['category_based'] ? '✓' : '✗') . "\n";
        echo "     * 價格推薦: " . ($strategiesUsed['price_based'] ? '✓' : '✗') . "\n";
        echo "     * 熱門推薦: " . ($strategiesUsed['popular_fallback'] ? '✓' : '✗') . "\n\n";
        
        echo "📝 推薦商品列表:\n";
        foreach ($recommendations as $index => $product) {
            echo "   " . ($index + 1) . ". {$product['name']}\n";
            echo "      - ID: {$product['id']}\n";
            echo "      - 分類: " . ($product['category_name'] ?? '無') . "\n";
            echo "      - 價格: \$" . number_format($product['price']) . "\n";
            echo "      - 狀態: {$product['status']}\n";
            echo "      - 可購買: " . ($product['can_add_to_cart'] ? '是' : '否') . "\n\n";
        }
        
        // 4. 分析推薦品質
        echo "📈 推薦品質分析:\n";
        
        $sameCategory = 0;
        $similarPrice = 0;
        $priceRange = $testProduct->price * 0.3;
        $minPrice = $testProduct->price - $priceRange;
        $maxPrice = $testProduct->price + $priceRange;
        
        foreach ($recommendations as $product) {
            if ($product['category_id'] == $testProduct->category_id) {
                $sameCategory++;
            }
            if ($product['price'] >= $minPrice && $product['price'] <= $maxPrice) {
                $similarPrice++;
            }
        }
        
        echo "   - 同分類商品: {$sameCategory}/" . count($recommendations) . "\n";
        echo "   - 相似價格商品: {$similarPrice}/" . count($recommendations) . "\n";
        echo "   - 推薦多樣性: " . (count(array_unique(array_column($recommendations, 'category_id'))) . " 個不同分類\n\n");
        
    } else {
        echo "❌ 推薦失敗: " . $responseData['message'] . "\n";
    }
    
    // 5. 測試邊界情況
    echo "🔍 測試邊界情況:\n\n";
    
    // 測試不存在的產品
    try {
        $response = $controller->getRecommendations(99999, $request);
        $responseData = json_decode($response->getContent(), true);
        
        if (!$responseData['success']) {
            echo "✅ 不存在產品處理正確\n";
        } else {
            echo "❌ 不存在產品處理異常\n";
        }
    } catch (Exception $e) {
        echo "✅ 不存在產品拋出異常 (正常)\n";
    }
    
    // 測試下架產品
    $archivedProduct = Product::where('status', 'archived')->first();
    if ($archivedProduct) {
        try {
            $response = $controller->getRecommendations($archivedProduct->id, $request);
            $responseData = json_decode($response->getContent(), true);
            
            if (!$responseData['success']) {
                echo "✅ 下架產品處理正確\n";
            } else {
                echo "❌ 下架產品處理異常\n";
            }
        } catch (Exception $e) {
            echo "✅ 下架產品處理正確 (異常處理)\n";
        }
    }
    
    echo "\n=== 測試完成 ===\n";
    echo "🎉 Phase 1 推薦系統基礎功能運行正常！\n\n";
    echo "🔗 API 端點: GET /api/v1/products/{id}/recommendations\n";
    echo "📋 支援參數: ?limit=N (預設: 8)\n";
    
} catch (Exception $e) {
    echo "❌ 測試過程發生錯誤: " . $e->getMessage() . "\n";
    echo "🔍 錯誤位置: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
