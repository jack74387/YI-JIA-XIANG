<?php

echo "=== 儀錶板功能測試 ===\n\n";

// 初始化 Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;
use App\Models\OperationLog;

echo "1. 檢查基本統計資料...\n";

// 基本統計
$totalProducts = Product::count();
$totalOrders = Order::count();
$totalMembers = User::where('is_admin', false)->count();
$totalCoupons = Coupon::count();

echo "   - 商品總數: {$totalProducts}\n";
echo "   - 訂單總數: {$totalOrders}\n";
echo "   - 會員總數: {$totalMembers}\n";
echo "   - 優惠券總數: {$totalCoupons}\n";

echo "\n2. 檢查今日統計...\n";

// 今日統計
$today = now()->startOfDay();
$todayOrders = Order::where('created_at', '>=', $today)->count();
$todayRevenue = Order::where('created_at', '>=', $today)
    ->where('status', 'completed')
    ->sum('total_amount');
$todayNewMembers = User::where('created_at', '>=', $today)
    ->where('is_admin', false)
    ->count();

echo "   - 今日訂單: {$todayOrders}\n";
echo "   - 今日營業額: NT$ " . number_format($todayRevenue) . "\n";
echo "   - 今日新會員: {$todayNewMembers}\n";

echo "\n3. 檢查本週統計...\n";

// 本週統計
$weekStart = now()->startOfWeek();
$weekOrders = Order::where('created_at', '>=', $weekStart)->count();
$weekRevenue = Order::where('created_at', '>=', $weekStart)
    ->where('status', 'completed')
    ->sum('total_amount');

echo "   - 本週訂單: {$weekOrders}\n";
echo "   - 本週營業額: NT$ " . number_format($weekRevenue) . "\n";

echo "\n4. 檢查本月統計...\n";

// 本月統計
$monthStart = now()->startOfMonth();
$monthOrders = Order::where('created_at', '>=', $monthStart)->count();
$monthRevenue = Order::where('created_at', '>=', $monthStart)
    ->where('status', 'completed')
    ->sum('total_amount');

echo "   - 本月訂單: {$monthOrders}\n";
echo "   - 本月營業額: NT$ " . number_format($monthRevenue) . "\n";

echo "\n5. 檢查訂單狀態統計...\n";

// 訂單狀態統計
$orderStatusStats = Order::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->get();

foreach ($orderStatusStats as $stat) {
    echo "   - {$stat->status}: {$stat->count}\n";
}

echo "\n6. 檢查最近訂單...\n";

// 最近訂單
$recentOrders = Order::with('user')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($recentOrders as $order) {
    echo "   - 訂單 #{$order->id} - {$order->user->name} - NT$ " . number_format($order->total_amount) . " - {$order->status}\n";
}

echo "\n7. 檢查最近會員...\n";

// 最近會員
$recentMembers = User::where('is_admin', false)
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($recentMembers as $member) {
    echo "   - {$member->name} ({$member->email}) - {$member->points} 點\n";
}

echo "\n8. 檢查最近操作日誌...\n";

// 最近操作日誌
$recentLogs = OperationLog::with('admin')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($recentLogs as $log) {
    $adminName = $log->admin->name ?? '系統';
    echo "   - {$adminName}: {$log->action} - {$log->description}\n";
}

echo "\n9. 檢查銷售趨勢...\n";

// 銷售趨勢（最近7天）
for ($i = 6; $i >= 0; $i--) {
    $date = now()->subDays($i);
    $dayStart = $date->startOfDay();
    $dayEnd = $date->endOfDay();
    
    $dailyRevenue = Order::where('created_at', '>=', $dayStart)
        ->where('created_at', '<=', $dayEnd)
        ->where('status', 'completed')
        ->sum('total_amount');
    
    echo "   - {$date->format('m/d')}: NT$ " . number_format($dailyRevenue) . "\n";
}

echo "\n=== 測試完成 ===\n";
echo "\n儀錶板功能包含：\n";
echo "1. 基本統計（商品、訂單、會員、優惠券總數）\n";
echo "2. 時間統計（今日、本週、本月）\n";
echo "3. 訂單狀態統計\n";
echo "4. 最近訂單列表\n";
echo "5. 最近會員列表\n";
echo "6. 最近操作日誌\n";
echo "7. 銷售趨勢圖表\n";
echo "8. 管理員密碼修改\n"; 