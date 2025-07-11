<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Product;

// 模擬 Laravel 環境
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== 設定測試產品狀態 ===\n\n";

try {
    // 將前 10 個產品設為上架
    $publishedCount = Product::take(10)->update(['status' => 'published']);
    echo "已將 {$publishedCount} 個產品設為 'published'\n";
    
    // 將接下來 5 個產品設為通知狀態
    $notificationCount = Product::skip(10)->take(5)->update(['status' => 'notification']);
    echo "已將 {$notificationCount} 個產品設為 'notification'\n";
    
    // 將接下來 3 個產品設為封存
    $archivedCount = Product::skip(15)->take(3)->update(['status' => 'archived']);
    echo "已將 {$archivedCount} 個產品設為 'archived'\n";
    
    // 顯示狀態統計
    $products = Product::all();
    echo "\n產品狀態統計:\n";
    
    $statusCounts = $products->groupBy('status')->map(function($group) {
        return $group->count();
    });
    
    foreach ($statusCounts as $status => $count) {
        echo "- {$status}: {$count} 個產品\n";
    }
    
    echo "\n=== 設定完成 ===\n";
    
} catch (\Exception $e) {
    echo "設定失敗: " . $e->getMessage() . "\n";
} 