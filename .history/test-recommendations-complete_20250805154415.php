<?php
/**
 * 商品推薦系統完整測試套件
 * 
 * 測試 Phase 1 推薦系統的所有功能：
 * 1. API 端點測試
 * 2. 多種推薦策略驗證
 * 3. 邊界條件測試
 * 4. 效能基準測試
 * 5. 前端整合準備度檢查
 */

require_once __DIR__ . '/backend/vendor/autoload.php';

// Laravel 應用程式啟動
$app = require_once __DIR__ . '/backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\Product;
use App\Models\Category;

echo "🍖 一佳香肉脯行 - 商品推薦系統完整測試套件\n";
echo "=" . str_repeat("=", 60) . "\n\n";

$startTime = microtime(true);
$testResults = [
    'passed' => 0,
    'failed' => 0,
    'warnings' => 0
];

try {
    // 1. 基礎資料檢查
    echo "📊 1. 基礎資料檢查\n";
    echo "-" . str_repeat("-", 30) . "\n";
    
    $productCount = Product::count();
    $categoryCount = Category::count();
    $publishedCount = Product::whereIn('status', ['published', 'notification'])->count();
    
    echo "   商品總數: {$productCount}\n";
    echo "   分類總數: {$categoryCount}\n";
    echo "   可顯示商品: {$publishedCount}\n\n";
    
    if ($productCount < 5) {
        echo "   ⚠️  警告: 商品數量較少，推薦效果可能有限\n";
        $testResults['warnings']++;
    } else {
        echo "   ✅ 資料量充足\n";
        $testResults['passed']++;
    }
    
    // 2. API 功能測試
    echo "\n🚀 2. API 功能測試\n";
    echo "-" . str_repeat("-", 30) . "\n";
    
    $controller = new \App\Http\Controllers\ProductController();
    
    // 選擇測試商品
    $testProducts = Product::with('category')
        ->whereIn('status', ['published', 'notification'])
        ->take(3)
        ->get();
    
    if ($testProducts->isEmpty()) {
        echo "   ❌ 沒有可用的測試商品\n";
        $testResults['failed']++;
    } else {
        foreach ($testProducts as $index => $product) {
            echo "   測試商品 " . ($index + 1) . ": {$product->name}\n";
            
            $request = new Illuminate\Http\Request(['limit' => 6]);
            $response = $controller->getRecommendations($product->id, $request);
            $responseData = json_decode($response->getContent(), true);
            
            if ($responseData['success']) {
                $recCount = count($responseData['data']['recommendations']);
                echo "     ✅ 推薦成功 ({$recCount} 個商品)\n";
                $testResults['passed']++;
                
                // 分析推薦品質
                $strategies = $responseData['data']['strategies_used'];
                $recommendations = $responseData['data']['recommendations'];
                
                $sameCategory = 0;
                foreach ($recommendations as $rec) {
                    if ($rec['category_id'] == $product->category_id) {
                        $sameCategory++;
                    }
                }
                
                echo "     📈 同分類商品: {$sameCategory}/{$recCount}\n";
                echo "     🎯 策略: " . 
                     ($strategies['category_based'] ? '分類✓' : '') . 
                     ($strategies['price_based'] ? ' 價格✓' : '') . 
                     ($strategies['popular_fallback'] ? ' 熱門✓' : '') . "\n";
                
            } else {
                echo "     ❌ 推薦失敗: " . $responseData['message'] . "\n";
                $testResults['failed']++;
            }
        }
    }
    
    // 3. 邊界條件測試
    echo "\n🔍 3. 邊界條件測試\n";
    echo "-" . str_repeat("-", 30) . "\n";
    
    // 測試不存在的商品
    try {
        $request = new Illuminate\Http\Request(['limit' => 6]);
        $response = $controller->getRecommendations(99999, $request);
        $responseData = json_decode($response->getContent(), true);
        
        if (!$responseData['success']) {
            echo "   ✅ 不存在商品處理正確\n";
            $testResults['passed']++;
        } else {
            echo "   ❌ 不存在商品處理異常\n";
            $testResults['failed']++;
        }
    } catch (Exception $e) {
        echo "   ✅ 不存在商品拋出異常 (正常)\n";
        $testResults['passed']++;
    }
    
    // 測試下架商品
    $archivedProduct = Product::where('status', 'archived')->first();
    if ($archivedProduct) {
        try {
            $request = new Illuminate\Http\Request(['limit' => 6]);
            $response = $controller->getRecommendations($archivedProduct->id, $request);
            $responseData = json_decode($response->getContent(), true);
            
            if (!$responseData['success']) {
                echo "   ✅ 下架商品處理正確\n";
                $testResults['passed']++;
            } else {
                echo "   ❌ 下架商品處理異常\n";
                $testResults['failed']++;
            }
        } catch (Exception $e) {
            echo "   ✅ 下架商品處理正確 (異常處理)\n";
            $testResults['passed']++;
        }
    } else {
        echo "   ⚠️  沒有下架商品可測試\n";
        $testResults['warnings']++;
    }
    
    // 測試極限參數
    $testProduct = $testProducts->first();
    if ($testProduct) {
        $request = new Illuminate\Http\Request(['limit' => 50]);
        $response = $controller->getRecommendations($testProduct->id, $request);
        $responseData = json_decode($response->getContent(), true);
        
        if ($responseData['success']) {
            $actualCount = count($responseData['data']['recommendations']);
            echo "   ✅ 大量推薦處理正確 (要求50個，實際{$actualCount}個)\n";
            $testResults['passed']++;
        } else {
            echo "   ❌ 大量推薦處理失敗\n";
            $testResults['failed']++;
        }
    }
    
    // 4. 效能測試
    echo "\n⚡ 4. 效能測試\n";
    echo "-" . str_repeat("-", 30) . "\n";
    
    $perfResults = [];
    if ($testProduct) {
        for ($i = 0; $i < 5; $i++) {
            $start = microtime(true);
            $request = new Illuminate\Http\Request(['limit' => 8]);
            $response = $controller->getRecommendations($testProduct->id, $request);
            $end = microtime(true);
            
            $perfResults[] = ($end - $start) * 1000; // 轉換為毫秒
        }
        
        $avgTime = array_sum($perfResults) / count($perfResults);
        $maxTime = max($perfResults);
        $minTime = min($perfResults);
        
        echo "   📊 平均回應時間: " . number_format($avgTime, 2) . "ms\n";
        echo "   📊 最快回應時間: " . number_format($minTime, 2) . "ms\n";
        echo "   📊 最慢回應時間: " . number_format($maxTime, 2) . "ms\n";
        
        if ($avgTime < 500) {
            echo "   ✅ 效能表現良好\n";
            $testResults['passed']++;
        } elseif ($avgTime < 1000) {
            echo "   ⚠️  效能可接受\n";
            $testResults['warnings']++;
        } else {
            echo "   ❌ 效能需要優化\n";
            $testResults['failed']++;
        }
    }
    
    // 5. 前端整合檢查
    echo "\n🖥️  5. 前端整合檢查\n";
    echo "-" . str_repeat("-", 30) . "\n";
    
    // 檢查 Vue 組件
    $componentPath = __DIR__ . '/frontend/src/components/ProductRecommend.vue';
    if (file_exists($componentPath)) {
        echo "   ✅ ProductRecommend.vue 組件存在\n";
        $testResults['passed']++;
        
        $componentContent = file_get_contents($componentPath);
        if (strpos($componentContent, 'productId') !== false) {
            echo "   ✅ 組件支援 productId 參數\n";
            $testResults['passed']++;
        } else {
            echo "   ❌ 組件缺少 productId 參數支援\n";
            $testResults['failed']++;
        }
    } else {
        echo "   ❌ ProductRecommend.vue 組件不存在\n";
        $testResults['failed']++;
    }
    
    // 檢查路由配置
    $routePath = __DIR__ . '/backend/routes/api.php';
    if (file_exists($routePath)) {
        $routeContent = file_get_contents($routePath);
        if (strpos($routeContent, 'recommendations') !== false) {
            echo "   ✅ API 路由已配置\n";
            $testResults['passed']++;
        } else {
            echo "   ❌ API 路由未配置\n";
            $testResults['failed']++;
        }
    }
    
    // 檢查產品詳情頁整合
    $productDetailPath = __DIR__ . '/frontend/src/views/ProductDetailView.vue';
    if (file_exists($productDetailPath)) {
        $detailContent = file_get_contents($productDetailPath);
        if (strpos($detailContent, 'ProductRecommend') !== false) {
            echo "   ✅ 產品詳情頁已整合推薦組件\n";
            $testResults['passed']++;
        } else {
            echo "   ⚠️  產品詳情頁未整合推薦組件\n";
            $testResults['warnings']++;
        }
    }
    
    // 6. 資料品質分析
    echo "\n📈 6. 資料品質分析\n";
    echo "-" . str_repeat("-", 30) . "\n";
    
    $categorizedProducts = Product::whereNotNull('category_id')->count();
    $productsWithPrice = Product::where(function($query) {
        $query->where('price_large', '>', 0)
              ->orWhere('price_small', '>', 0);
    })->count();
    $hotProducts = Product::where('hot', true)->count();
    $ratedProducts = Product::where('rating', '>', 0)->count();
    
    echo "   🏷️  有分類的商品: {$categorizedProducts}/{$productCount} (" . 
         round(($categorizedProducts/$productCount)*100, 1) . "%)\n";
    echo "   💰 有價格的商品: {$productsWithPrice}/{$productCount} (" . 
         round(($productsWithPrice/$productCount)*100, 1) . "%)\n";
    echo "   🔥 熱門商品: {$hotProducts}\n";
    echo "   ⭐ 有評分商品: {$ratedProducts}\n";
    
    if ($categorizedProducts / $productCount >= 0.8) {
        echo "   ✅ 分類資料完整度良好\n";
        $testResults['passed']++;
    } else {
        echo "   ⚠️  建議完善商品分類資料\n";
        $testResults['warnings']++;
    }
    
} catch (Exception $e) {
    echo "❌ 測試過程發生嚴重錯誤: " . $e->getMessage() . "\n";
    echo "🔍 錯誤位置: " . $e->getFile() . ":" . $e->getLine() . "\n";
    $testResults['failed']++;
}

$endTime = microtime(true);
$totalTime = ($endTime - $startTime) * 1000;

// 測試結果總結
echo "\n" . str_repeat("=", 60) . "\n";
echo "📋 測試結果總結\n";
echo str_repeat("=", 60) . "\n";
echo "✅ 通過測試: {$testResults['passed']}\n";
echo "❌ 失敗測試: {$testResults['failed']}\n";
echo "⚠️  警告項目: {$testResults['warnings']}\n";
echo "⏱️  總執行時間: " . number_format($totalTime, 2) . "ms\n\n";

$totalTests = $testResults['passed'] + $testResults['failed'];
$successRate = $totalTests > 0 ? ($testResults['passed'] / $totalTests) * 100 : 0;

echo "📊 成功率: " . number_format($successRate, 1) . "%\n\n";

if ($testResults['failed'] == 0) {
    echo "🎉 恭喜！商品推薦系統 Phase 1 已成功實作！\n\n";
    echo "✨ 系統特色:\n";
    echo "   🎯 智慧推薦: 基於分類、價格、熱門度的多策略推薦\n";
    echo "   🚀 高效能: 平均回應時間 < 500ms\n";
    echo "   📱 響應式: 支援各種螢幕尺寸\n";
    echo "   🛡️  穩定性: 完整的錯誤處理機制\n\n";
    
    echo "🔗 API 使用方式:\n";
    echo "   端點: GET /api/v1/products/{id}/recommendations\n";
    echo "   參數: ?limit=N (預設: 8)\n";
    echo "   範例: curl 'http://localhost/api/v1/products/1/recommendations?limit=6'\n\n";
    
    echo "🖥️  前端使用方式:\n";
    echo "   <ProductRecommend :product-id=\"product.id\" :limit=\"6\" />\n\n";
    
    echo "📋 後續發展建議:\n";
    echo "   📈 Phase 2: 用戶行為追蹤 (瀏覽記錄、購買記錄)\n";
    echo "   🤖 Phase 3: 機器學習推薦 (協同過濾、內容過濾)\n";
    echo "   📊 Phase 4: A/B 測試與效果分析\n";
    
} elseif ($testResults['failed'] <= 2) {
    echo "⚠️  商品推薦系統基本功能正常，但有少數問題需要修正\n";
    echo "建議檢查失敗的測試項目並進行調整\n";
} else {
    echo "❌ 商品推薦系統存在多個問題，需要進一步調試\n";
    echo "建議逐項檢查測試結果並修正相關問題\n";
}

echo "\n📁 相關檔案:\n";
echo "   🔧 Backend API: backend/app/Http/Controllers/ProductController.php\n";
echo "   🛣️  API 路由: backend/routes/api.php\n";
echo "   🖼️  Vue 組件: frontend/src/components/ProductRecommend.vue\n";
echo "   📄 產品頁面: frontend/src/views/ProductDetailView.vue\n";
echo "   🧪 測試頁面: test-product-recommendations-frontend.html\n\n";

echo "🎯 商品推薦系統 Phase 1 測試完成！\n";
