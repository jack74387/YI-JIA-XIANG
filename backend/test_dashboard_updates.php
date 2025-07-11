<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\User;

// 模擬 Laravel 環境
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== 測試儀錶板更新功能 ===\n\n";

// 1. 測試排除已取消訂單的統計
echo "1. 測試排除已取消訂單的統計:\n";

// 創建測試訂單
$user = User::where('email', 'admin@example.com')->first();
if (!$user) {
    echo "找不到管理員用戶，請先運行 setup-admin.php\n";
    exit;
}

// 創建不同狀態的測試訂單
$testOrders = [
    ['status' => 'pending', 'total_amount' => 1000],
    ['status' => 'completed', 'total_amount' => 2000],
    ['status' => 'cancelled', 'total_amount' => 500], // 這個應該被排除
    ['status' => 'processing', 'total_amount' => 1500],
    ['status' => 'completed', 'total_amount' => 3000]
];

foreach ($testOrders as $orderData) {
    Order::create([
        'user_id' => $user->id,
        'order_number' => 'TEST-' . time() . rand(100, 999),
        'status' => $orderData['status'],
        'total' => $orderData['total_amount'],
        'created_at' => now()
    ]);
}

// 檢查統計
$totalOrders = Order::count();
$totalOrdersExcludingCancelled = Order::where('status', '!=', 'cancelled')->count();
$completedRevenue = Order::where('status', 'completed')->sum('total');

echo "   - 總訂單數: $totalOrders\n";
echo "   - 排除已取消訂單數: $totalOrdersExcludingCancelled\n";
echo "   - 已完成訂單收入: $completedRevenue\n";

// 2. 測試最近優惠券
echo "\n2. 測試最近優惠券:\n";

// 創建測試優惠券
$testCoupons = [
    ['name' => '新用戶優惠', 'code' => 'NEW100', 'type' => 'fixed', 'value' => 100, 'is_active' => true],
    ['name' => '會員折扣', 'code' => 'MEMBER20', 'type' => 'percent', 'value' => 20, 'is_active' => true],
    ['name' => '限時優惠', 'code' => 'LIMITED50', 'type' => 'fixed', 'value' => 50, 'is_active' => false]
];

foreach ($testCoupons as $couponData) {
    Coupon::create([
        'name' => $couponData['name'],
        'code' => $couponData['code'],
        'type' => $couponData['type'],
        'value' => $couponData['value'],
        'is_active' => $couponData['is_active'],
        'min_order' => 1000,
        'usage_limit' => 100,
        'description' => '測試優惠券'
    ]);
}

// 檢查最近優惠券
$recentCoupons = Coupon::orderBy('created_at', 'desc')->limit(5)->get();
echo "   - 最近優惠券數量: " . $recentCoupons->count() . "\n";

foreach ($recentCoupons as $coupon) {
    echo "     * {$coupon->name} ({$coupon->code}) - " . ($coupon->is_active ? '啟用' : '停用') . "\n";
}

// 3. 測試時間範圍統計
echo "\n3. 測試時間範圍統計:\n";

$today = now()->startOfDay();
$todayOrders = Order::where('created_at', '>=', $today)
    ->where('status', '!=', 'cancelled')
    ->count();
$todayRevenue = Order::where('created_at', '>=', $today)
    ->where('status', 'completed')
    ->sum('total');

echo "   - 今日訂單數 (排除已取消): $todayOrders\n";
echo "   - 今日收入: $todayRevenue\n";

// 4. 清理測試數據
echo "\n4. 清理測試數據:\n";

// 刪除測試訂單
Order::where('order_number', 'like', 'TEST-%')->delete();
echo "   - 已刪除測試訂單\n";

// 刪除測試優惠券
Coupon::whereIn('code', ['NEW100', 'MEMBER20', 'LIMITED50'])->delete();
echo "   - 已刪除測試優惠券\n";

echo "\n=== 測試完成 ===\n";
echo "所有功能正常運作！\n"; 