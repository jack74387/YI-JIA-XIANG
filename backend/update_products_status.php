<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Product;

// 模擬 Laravel 環境
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== 更新產品狀態 ===\n\n";

try {
    // 將所有現有產品設為上架狀態
    $updatedCount = Product::whereNull('status')->update(['status' => 'published']);
    
    echo "已更新 {$updatedCount} 個產品的狀態為 'published'\n";
    
    // 顯示所有產品狀態
    $products = Product::all();
    echo "\n產品狀態統計:\n";
    
    $statusCounts = $products->groupBy('status')->map(function($group) {
        return $group->count();
    });
    
    foreach ($statusCounts as $status => $count) {
        echo "- {$status}: {$count} 個產品\n";
    }
    
    echo "\n=== 更新完成 ===\n";
    
} catch (\Exception $e) {
    echo "更新失敗: " . $e->getMessage() . "\n";
} 