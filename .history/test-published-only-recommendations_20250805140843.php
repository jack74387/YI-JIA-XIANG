<?php
/**
 * 測試推薦系統只推薦上架商品
 * 
 * 驗證所有推薦策略都只返回 status = 'published' 的商品
 */

require_once __DIR__ . '/backend/vendor/autoload.php';

// Laravel 應用程式啟動
$app = require_once __DIR__ . '/backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\Product;

echo "=== 測試推薦系統只推薦上架商品 ===\n\n";

try {
    // 1. 檢查商品狀態分佈
    echo "📊 商品狀態分佈:\n";
    $statusCounts = Product::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->get();
    
    foreach ($statusCounts as $status) {
        echo "   - {$status->status}: {$status->count} 個商品\n";
    }
    echo "\n";
    
    // 2. 選擇測試商品 (上架狀態)
    $testProduct = Product::with('category')
        ->where('status', 'published')
        ->first();
    
    if (!$testProduct) {
        echo "❌ 沒有找到上架商品進行測試\n";
        exit;
    }
    
    echo "🎯 測試商品:\n";
    echo "   - ID: {$testProduct->id}\n";
    echo "   - 名稱: {$testProduct->name}\n";
    echo "   - 狀態: {$testProduct->status}\n";
    echo "   - 分類: " . ($testProduct->category ? $testProduct->category->name : '無') . "\n\n";
    
    // 3. 測試推薦 API
    echo "🧪 測試推薦 API...\n";
    
    $controller = new \App\Http\Controllers\ProductController();
    $request = new Illuminate\Http\Request(['limit' => 10]);
    
    $response = $controller->getRecommendations($testProduct->id, $request);
    $responseData = json_decode($response->getContent(), true);
    
    if ($responseData['success']) {
        $recommendations = $responseData['data']['recommendations'];
        echo "✅ 推薦成功! 共 " . count($recommendations) . " 個商品\n\n";
        
        // 4. 驗證推薦商品狀態
        echo "🔍 驗證推薦商品狀態:\n";
        $allPublished = true;
        $statusDistribution = [];
        
        foreach ($recommendations as $index => $product) {
            $status = $product['status'];
            $statusDistribution[$status] = ($statusDistribution[$status] ?? 0) + 1;
            
            if ($status !== 'published') {
                $allPublished = false;
                echo "   ❌ 商品 #{$product['id']} ({$product['name']}) 狀態: {$status}\n";
            } else {
                echo "   ✅ 商品 #{$product['id']} ({$product['name']}) 狀態: {$status}\n";
            }
        }
        
        echo "\n📈 推薦商品狀態統計:\n";
        foreach ($statusDistribution as $status => $count) {
            echo "   - {$status}: {$count} 個商品\n";
        }
        
        if ($allPublished) {
            echo "\n🎉 驗證通過！所有推薦商品都是上架狀態 (published)\n";
        } else {
            echo "\n❌ 驗證失敗！發現非上架狀態的推薦商品\n";
        }
        
        // 5. 測試不同商品以確保一致性
        echo "\n🔄 測試其他商品以確保一致性:\n";
        $otherProducts = Product::where('status', 'published')
            ->where('id', '!=', $testProduct->id)
            ->take(2)
            ->get();
        
        foreach ($otherProducts as $index => $product) {
            echo "\n   測試商品 " . ($index + 2) . ": {$product->name}\n";
            
            $response = $controller->getRecommendations($product->id, $request);
            $responseData = json_decode($response->getContent(), true);
            
            if ($responseData['success']) {
                $recs = $responseData['data']['recommendations'];
                $publishedCount = count(array_filter($recs, function($r) {
                    return $r['status'] === 'published';
                }));
                
                echo "     推薦 " . count($recs) . " 個商品，上架狀態: {$publishedCount}/" . count($recs) . "\n";
                
                if ($publishedCount === count($recs)) {
                    echo "     ✅ 全部為上架商品\n";
                } else {
                    echo "     ❌ 包含非上架商品\n";
                }
            } else {
                echo "     ❌ 推薦失敗\n";
            }
        }
        
        // 6. 測試推薦策略說明
        $strategies = $responseData['data']['strategies_used'];
        echo "\n📋 推薦策略使用狀況:\n";
        echo "   - 分類推薦: " . ($strategies['category_based'] ? '✓ 已使用' : '✗ 未使用') . "\n";
        echo "   - 價格推薦: " . ($strategies['price_based'] ? '✓ 已使用' : '✗ 未使用') . "\n";
        echo "   - 熱門推薦: " . ($strategies['popular_fallback'] ? '✓ 已使用' : '✗ 未使用') . "\n";
        
    } else {
        echo "❌ 推薦失敗: " . $responseData['message'] . "\n";
    }
    
    // 7. 直接查詢驗證
    echo "\n🔍 直接查詢驗證 - 檢查資料庫中非上架商品:\n";
    $nonPublishedCount = Product::where('status', '!=', 'published')->count();
    echo "   非上架商品總數: {$nonPublishedCount}\n";
    
    if ($nonPublishedCount > 0) {
        echo "   這些商品不應該出現在推薦中\n";
    } else {
        echo "   ✅ 所有商品都是上架狀態\n";
    }
    
} catch (Exception $e) {
    echo "❌ 測試過程發生錯誤: " . $e->getMessage() . "\n";
    echo "🔍 錯誤位置: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== 測試完成 ===\n";
