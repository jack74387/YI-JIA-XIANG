<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

// 模擬 Laravel 環境
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== 營業額計算測試 ===\n\n";

// 1. 檢查所有訂單的狀態和金額
echo "1. 檢查所有訂單狀態和金額:\n";
$orders = Order::all();
$totalOrders = $orders->count();
$totalRevenue = $orders->sum('total');

echo "   - 總訂單數: $totalOrders\n";
echo "   - 總金額: $totalRevenue\n\n";

// 按狀態分組統計
$statusStats = $orders->groupBy('status')->map(function($group) {
    return [
        'count' => $group->count(),
        'total' => $group->sum('total')
    ];
});

foreach ($statusStats as $status => $stats) {
    echo "   - 狀態 '$status': {$stats['count']} 筆訂單，總金額 {$stats['total']}\n";
}

// 2. 檢查今日營業額計算
echo "\n2. 今日營業額計算:\n";
$today = now()->startOfDay();
$todayOrders = Order::where('created_at', '>=', $today)->get();
$todayCompletedOrders = Order::where('created_at', '>=', $today)
    ->where('status', 'completed')
    ->get();

echo "   - 今日總訂單數: " . $todayOrders->count() . "\n";
echo "   - 今日已完成訂單數: " . $todayCompletedOrders->count() . "\n";
echo "   - 今日已完成訂單總金額: " . $todayCompletedOrders->sum('total') . "\n";

// 顯示今日訂單詳情
if ($todayCompletedOrders->count() > 0) {
    echo "   - 今日已完成訂單詳情:\n";
    foreach ($todayCompletedOrders as $order) {
        echo "     * 訂單 #{$order->id}: NT$ {$order->total} ({$order->status})\n";
    }
}

// 3. 檢查本週營業額計算
echo "\n3. 本週營業額計算:\n";
$weekStart = now()->startOfWeek();
$weekCompletedOrders = Order::where('created_at', '>=', $weekStart)
    ->where('status', 'completed')
    ->get();

echo "   - 本週已完成訂單數: " . $weekCompletedOrders->count() . "\n";
echo "   - 本週已完成訂單總金額: " . $weekCompletedOrders->sum('total') . "\n";

// 4. 檢查本月營業額計算
echo "\n4. 本月營業額計算:\n";
$monthStart = now()->startOfMonth();
$monthCompletedOrders = Order::where('created_at', '>=', $monthStart)
    ->where('status', 'completed')
    ->get();

echo "   - 本月已完成訂單數: " . $monthCompletedOrders->count() . "\n";
echo "   - 本月已完成訂單總金額: " . $monthCompletedOrders->sum('total') . "\n";

// 5. 檢查最近7天銷售趨勢
echo "\n5. 最近7天銷售趨勢:\n";
for ($i = 6; $i >= 0; $i--) {
    $date = now()->subDays($i);
    $dayStart = $date->startOfDay();
    $dayEnd = $date->endOfDay();
    
    $dailyOrders = Order::where('created_at', '>=', $dayStart)
        ->where('created_at', '<=', $dayEnd)
        ->where('status', 'completed')
        ->get();
    
    $dailyRevenue = $dailyOrders->sum('total');
    $orderCount = $dailyOrders->count();
    
    echo "   - {$date->format('Y-m-d')}: {$orderCount} 筆訂單，營業額 NT$ {$dailyRevenue}\n";
    
    if ($orderCount > 0) {
        foreach ($dailyOrders as $order) {
            echo "     * 訂單 #{$order->id}: NT$ {$order->total}\n";
        }
    }
}

// 6. 檢查是否有異常資料
echo "\n6. 檢查異常資料:\n";

// 檢查金額為0或負數的訂單
$zeroOrders = Order::where('total', '<=', 0)->get();
if ($zeroOrders->count() > 0) {
    echo "   - 發現金額 <= 0 的訂單:\n";
    foreach ($zeroOrders as $order) {
        echo "     * 訂單 #{$order->id}: NT$ {$order->total} ({$order->status})\n";
    }
} else {
    echo "   - 沒有發現金額 <= 0 的訂單\n";
}

// 檢查沒有 created_at 的訂單
$nullDateOrders = Order::whereNull('created_at')->get();
if ($nullDateOrders->count() > 0) {
    echo "   - 發現沒有建立時間的訂單:\n";
    foreach ($nullDateOrders as $order) {
        echo "     * 訂單 #{$order->id}: NT$ {$order->total} ({$order->status})\n";
    }
} else {
    echo "   - 沒有發現沒有建立時間的訂單\n";
}

// 7. 模擬 API 回傳的資料結構
echo "\n7. 模擬 API 回傳資料:\n";
$apiData = [
    'overview' => [
        'total_products' => \App\Models\Product::count(),
        'total_orders' => Order::count(),
        'total_members' => User::where('is_admin', false)->count(),
        'total_coupons' => \App\Models\Coupon::count()
    ],
    'today' => [
        'orders' => Order::where('created_at', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->count(),
        'revenue' => Order::where('created_at', '>=', $today)
            ->where('status', 'completed')
            ->sum('total'),
        'new_members' => User::where('created_at', '>=', $today)
            ->where('is_admin', false)
            ->count()
    ],
    'week' => [
        'orders' => Order::where('created_at', '>=', $weekStart)
            ->where('status', '!=', 'cancelled')
            ->count(),
        'revenue' => Order::where('created_at', '>=', $weekStart)
            ->where('status', 'completed')
            ->sum('total')
    ],
    'month' => [
        'orders' => Order::where('created_at', '>=', $monthStart)
            ->where('status', '!=', 'cancelled')
            ->count(),
        'revenue' => Order::where('created_at', '>=', $monthStart)
            ->where('status', 'completed')
            ->sum('total')
    ]
];

echo "   - 概覽統計: " . json_encode($apiData['overview'], JSON_UNESCAPED_UNICODE) . "\n";
echo "   - 今日統計: " . json_encode($apiData['today'], JSON_UNESCAPED_UNICODE) . "\n";
echo "   - 本週統計: " . json_encode($apiData['week'], JSON_UNESCAPED_UNICODE) . "\n";
echo "   - 本月統計: " . json_encode($apiData['month'], JSON_UNESCAPED_UNICODE) . "\n";

echo "\n=== 測試完成 ===\n"; 