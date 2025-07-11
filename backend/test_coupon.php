<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use App\Models\UserCoupon;

// 初始化 Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== 優惠券功能測試 ===\n\n";

// 1. 檢查資料表是否存在
echo "1. 檢查資料表...\n";
try {
    $coupons = DB::table('coupons')->count();
    echo "   - coupons 表存在，共有 {$coupons} 筆資料\n";
} catch (Exception $e) {
    echo "   - coupons 表不存在或錯誤: " . $e->getMessage() . "\n";
}

try {
    $userCoupons = DB::table('user_coupons')->count();
    echo "   - user_coupons 表存在，共有 {$userCoupons} 筆資料\n";
} catch (Exception $e) {
    echo "   - user_coupons 表不存在或錯誤: " . $e->getMessage() . "\n";
}

// 2. 創建測試優惠券
echo "\n2. 創建測試優惠券...\n";
try {
    $coupon = Coupon::create([
        'name' => '測試優惠券',
        'code' => 'TEST123',
        'type' => 'percent',
        'value' => 10,
        'min_order' => 500,
        'expires_at' => now()->addMonths(1),
        'usage_limit' => 100,
        'description' => '測試用優惠券',
        'is_active' => true
    ]);
    echo "   - 成功創建優惠券: {$coupon->name} (代碼: {$coupon->code})\n";
} catch (Exception $e) {
    echo "   - 創建優惠券失敗: " . $e->getMessage() . "\n";
}

// 3. 測試優惠券模型方法
echo "\n3. 測試優惠券模型方法...\n";
try {
    $coupon = Coupon::where('code', 'TEST123')->first();
    if ($coupon) {
        echo "   - 優惠券有效性檢查: " . ($coupon->isValid() ? '有效' : '無效') . "\n";
        echo "   - 折扣文字: {$coupon->discount_text}\n";
        echo "   - 狀態文字: {$coupon->status_text}\n";
        echo "   - 計算折扣 (訂單金額 1000): NT$" . $coupon->calculateDiscount(1000) . "\n";
    } else {
        echo "   - 找不到測試優惠券\n";
    }
} catch (Exception $e) {
    echo "   - 測試模型方法失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 測試完成 ===\n"; 