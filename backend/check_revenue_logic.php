<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

// 模擬 Laravel 環境
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== 營業額計算邏輯比較 ===\n\n";

$today = now()->startOfDay();
$weekStart = now()->startOfWeek();
$monthStart = now()->startOfMonth();

// 1. 目前邏輯：只計算 completed 狀態
echo "1. 目前邏輯（只計算 completed 狀態）:\n";
$todayCompleted = Order::where('created_at', '>=', $today)
    ->where('status', 'completed')
    ->sum('total');
$weekCompleted = Order::where('created_at', '>=', $weekStart)
    ->where('status', 'completed')
    ->sum('total');
$monthCompleted = Order::where('created_at', '>=', $monthStart)
    ->where('status', 'completed')
    ->sum('total');

echo "   - 今日營業額: NT$ {$todayCompleted}\n";
echo "   - 本週營業額: NT$ {$weekCompleted}\n";
echo "   - 本月營業額: NT$ {$monthCompleted}\n";

// 2. 包含所有非取消狀態
echo "\n2. 包含所有非取消狀態（pending, processing, shipped, delivered, completed）:\n";
$todayAllActive = Order::where('created_at', '>=', $today)
    ->where('status', '!=', 'cancelled')
    ->sum('total');
$weekAllActive = Order::where('created_at', '>=', $weekStart)
    ->where('status', '!=', 'cancelled')
    ->sum('total');
$monthAllActive = Order::where('created_at', '>=', $monthStart)
    ->where('status', '!=', 'cancelled')
    ->sum('total');

echo "   - 今日營業額: NT$ {$todayAllActive}\n";
echo "   - 本週營業額: NT$ {$weekAllActive}\n";
echo "   - 本月營業額: NT$ {$monthAllActive}\n";

// 3. 包含所有訂單（包括已取消）
echo "\n3. 包含所有訂單（包括已取消）:\n";
$todayAll = Order::where('created_at', '>=', $today)->sum('total');
$weekAll = Order::where('created_at', '>=', $weekStart)->sum('total');
$monthAll = Order::where('created_at', '>=', $monthStart)->sum('total');

echo "   - 今日營業額: NT$ {$todayAll}\n";
echo "   - 本週營業額: NT$ {$weekAll}\n";
echo "   - 本月營業額: NT$ {$monthAll}\n";

// 4. 按狀態詳細分析今日訂單
echo "\n4. 今日訂單按狀態分析:\n";
$todayOrdersByStatus = Order::where('created_at', '>=', $today)
    ->selectRaw('status, COUNT(*) as count, SUM(total) as total_amount')
    ->groupBy('status')
    ->get();

foreach ($todayOrdersByStatus as $stat) {
    echo "   - 狀態 '{$stat->status}': {$stat->count} 筆訂單，總金額 NT$ {$stat->total_amount}\n";
}

// 5. 本週訂單按狀態分析
echo "\n5. 本週訂單按狀態分析:\n";
$weekOrdersByStatus = Order::where('created_at', '>=', $weekStart)
    ->selectRaw('status, COUNT(*) as count, SUM(total) as total_amount')
    ->groupBy('status')
    ->get();

foreach ($weekOrdersByStatus as $stat) {
    echo "   - 狀態 '{$stat->status}': {$stat->count} 筆訂單，總金額 NT$ {$stat->total_amount}\n";
}

// 6. 本月訂單按狀態分析
echo "\n6. 本月訂單按狀態分析:\n";
$monthOrdersByStatus = Order::where('created_at', '>=', $monthStart)
    ->selectRaw('status, COUNT(*) as count, SUM(total) as total_amount')
    ->groupBy('status')
    ->get();

foreach ($monthOrdersByStatus as $stat) {
    echo "   - 狀態 '{$stat->status}': {$stat->count} 筆訂單，總金額 NT$ {$stat->total_amount}\n";
}

// 7. 建議的營業額計算方式
echo "\n7. 建議的營業額計算方式:\n";
echo "   A. 保守型（只算已完成）: 確保收款\n";
echo "   B. 實際型（算所有非取消）: 反映實際訂單量\n";
echo "   C. 總計型（算所有訂單）: 包含所有交易\n\n";

echo "   目前使用方式 A，如果你希望看到更多營業額，建議改用方式 B\n";
echo "   方式 B 會包含：pending + processing + shipped + delivered + completed\n";

echo "\n=== 分析完成 ===\n"; 