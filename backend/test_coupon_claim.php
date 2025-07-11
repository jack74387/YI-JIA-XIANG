<?php

echo "=== 優惠券領取功能測試 ===\n\n";

// 初始化 Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;

echo "1. 檢查優惠券資料...\n";

// 檢查是否有啟用的優惠券
$coupons = Coupon::where('is_active', true)->get();
echo "   - 啟用的優惠券數量: " . $coupons->count() . "\n";

if ($coupons->count() > 0) {
    foreach ($coupons as $coupon) {
        $claimedCount = UserCoupon::where('coupon_id', $coupon->id)->count();
        echo "   - {$coupon->name} (代碼: {$coupon->code}) - 已領取: {$claimedCount} 次\n";
    }
} else {
    echo "   - 沒有啟用的優惠券\n";
}

echo "\n2. 檢查測試用戶...\n";

// 檢查測試用戶
$testUser = User::where('email', 'test@example.com')->first();
if ($testUser) {
    echo "   - 測試用戶: {$testUser->name} ({$testUser->email})\n";
    
    // 檢查用戶已領取的優惠券
    $userCoupons = UserCoupon::where('user_id', $testUser->id)->get();
    echo "   - 已領取優惠券數量: " . $userCoupons->count() . "\n";
    
    if ($userCoupons->count() > 0) {
        foreach ($userCoupons as $userCoupon) {
            $coupon = $userCoupon->coupon;
            $status = $userCoupon->is_used ? '已使用' : '可用';
            echo "   - {$coupon->name} - 狀態: {$status}\n";
        }
    }
} else {
    echo "   - 測試用戶不存在\n";
}

echo "\n3. 測試 API 端點...\n";

// 模擬 API 請求
try {
    // 測試可領取優惠券 API
    $claimableCoupons = Coupon::where('is_active', true)
        ->where('expires_at', '>', now())
        ->get();
    
    $userClaimableCoupons = [];
    $userClaimedCoupons = [];
    
    foreach ($claimableCoupons as $coupon) {
        $userCoupon = UserCoupon::where('user_id', $testUser->id)
            ->where('coupon_id', $coupon->id)
            ->first();
            
        if ($userCoupon) {
            $userClaimedCoupons[] = $coupon;
        } else {
            $userClaimableCoupons[] = $coupon;
        }
    }
    
    echo "   - 可領取優惠券: " . count($userClaimableCoupons) . " 張\n";
    echo "   - 已領取優惠券: " . count($userClaimedCoupons) . " 張\n";
    
    if (count($userClaimableCoupons) > 0) {
        echo "   - 可領取的優惠券:\n";
        foreach ($userClaimableCoupons as $coupon) {
            echo "     * {$coupon->name} (代碼: {$coupon->code})\n";
        }
    }
    
} catch (Exception $e) {
    echo "   - API 測試失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 測試完成 ===\n";
echo "\n前台測試步驟：\n";
echo "1. 使用測試帳號登入: test@example.com / password\n";
echo "2. 訪問: http://localhost:5173/coupon\n";
echo "3. 點擊「可領取」標籤頁\n";
echo "4. 點擊「立即領取」按鈕領取優惠券\n";
echo "5. 檢查「可用優惠券」標籤頁是否顯示剛領取的優惠券\n"; 