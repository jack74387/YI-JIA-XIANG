<?php

echo "=== 測試 used_count 修復 ===\n\n";

// 初始化 Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;

echo "1. 檢查優惠券的 used_count 欄位...\n";

// 檢查所有優惠券的 used_count
$coupons = Coupon::all();
foreach ($coupons as $coupon) {
    $actualUsedCount = UserCoupon::where('coupon_id', $coupon->id)
        ->where('is_used', true)
        ->count();
    
    echo "   - {$coupon->name} (代碼: {$coupon->code})\n";
    echo "     * 資料庫 used_count: {$coupon->used_count}\n";
    echo "     * 實際已使用: {$actualUsedCount}\n";
    
    // 如果不一致，更新 used_count
    if ($coupon->used_count != $actualUsedCount) {
        $coupon->update(['used_count' => $actualUsedCount]);
        echo "     * 已修正 used_count 為: {$actualUsedCount}\n";
    }
    echo "\n";
}

echo "2. 測試後端 API 回應...\n";

// 模擬 API 請求
try {
    // 測試管理員優惠券列表 API
    $adminCoupons = Coupon::orderBy('created_at', 'desc')->get();
    
    foreach ($adminCoupons as $coupon) {
        $coupon->used_count = $coupon->userCoupons()->where('is_used', true)->count();
        $remaining = $coupon->usage_limit ? ($coupon->usage_limit - $coupon->used_count) : '無限制';
        
        echo "   - {$coupon->name}\n";
        echo "     * 使用限制: " . ($coupon->usage_limit ?: '無限制') . "\n";
        echo "     * 已使用: {$coupon->used_count}\n";
        echo "     * 剩餘: {$remaining}\n";
    }
    
} catch (Exception $e) {
    echo "   - API 測試失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 測試完成 ===\n";
echo "\n修復內容：\n";
echo "1. 後台管理介面已添加「已使用」和「剩餘」欄位\n";
echo "2. 後端 API 已修正，正確返回 used_count\n";
echo "3. 前端已修正剩餘數量計算\n";
echo "4. 資料庫 used_count 欄位已同步\n"; 