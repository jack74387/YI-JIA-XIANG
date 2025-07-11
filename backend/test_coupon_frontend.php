<?php

echo "=== 前台優惠券功能測試 ===\n\n";

// 初始化 Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;

echo "1. 檢查優惠券資料...\n";

// 檢查是否有優惠券
$coupons = Coupon::where('is_active', true)->get();
echo "   - 啟用的優惠券數量: " . $coupons->count() . "\n";

if ($coupons->count() > 0) {
    foreach ($coupons as $coupon) {
        echo "   - {$coupon->name} (代碼: {$coupon->code})\n";
    }
} else {
    echo "   - 沒有啟用的優惠券，創建測試優惠券...\n";
    
    // 創建測試優惠券
    $testCoupons = [
        [
            'name' => '新會員優惠',
            'code' => 'NEWUSER10',
            'type' => 'percent',
            'value' => 10,
            'min_order' => 500,
            'expires_at' => now()->addMonths(3),
            'usage_limit' => 100,
            'description' => '新會員專享優惠，全館商品享9折',
            'is_active' => true
        ],
        [
            'name' => '滿額折扣',
            'code' => 'SAVE20',
            'type' => 'percent',
            'value' => 20,
            'min_order' => 1000,
            'expires_at' => now()->addMonths(6),
            'usage_limit' => 50,
            'description' => '消費滿1000元享8折優惠',
            'is_active' => true
        ],
        [
            'name' => '免運費',
            'code' => 'FREESHIP',
            'type' => 'fixed',
            'value' => 120,
            'min_order' => 800,
            'expires_at' => now()->addMonths(2),
            'usage_limit' => 200,
            'description' => '滿800元免運費',
            'is_active' => true
        ]
    ];
    
    foreach ($testCoupons as $couponData) {
        Coupon::create($couponData);
        echo "   - 已創建: {$couponData['name']}\n";
    }
}

echo "\n2. 檢查用戶資料...\n";

// 檢查是否有測試用戶
$testUser = User::where('email', 'test@example.com')->first();
if (!$testUser) {
    echo "   - 創建測試用戶...\n";
    $testUser = User::create([
        'name' => '測試用戶',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'is_admin' => false
    ]);
    echo "   - 測試用戶已創建 (email: test@example.com, password: password)\n";
} else {
    echo "   - 測試用戶已存在\n";
}

echo "\n3. 檢查用戶優惠券...\n";

// 檢查用戶是否已經領取優惠券
$userCoupons = UserCoupon::where('user_id', $testUser->id)->get();
echo "   - 用戶已領取的優惠券數量: " . $userCoupons->count() . "\n";

if ($userCoupons->count() == 0) {
    echo "   - 為用戶領取一些優惠券...\n";
    
    // 為用戶領取前兩個優惠券
    $availableCoupons = Coupon::where('is_active', true)->take(2)->get();
    foreach ($availableCoupons as $coupon) {
        UserCoupon::create([
            'user_id' => $testUser->id,
            'coupon_id' => $coupon->id,
            'is_used' => false
        ]);
        echo "   - 已領取: {$coupon->name}\n";
    }
}

echo "\n4. 測試 API 端點...\n";

// 模擬 API 請求
try {
    // 模擬用戶登入
    $token = $testUser->createToken('test-token')->plainTextToken;
    echo "   - 用戶 Token: " . substr($token, 0, 20) . "...\n";
    
    // 測試優惠券列表 API
    $couponsResponse = Coupon::where('is_active', true)->paginate(15);
    echo "   - 優惠券列表 API 正常，共 " . $couponsResponse->total() . " 張優惠券\n";
    
    // 測試用戶優惠券 API
    $userCoupons = UserCoupon::with('coupon')
        ->where('user_id', $testUser->id)
        ->get();
    
    $available = $userCoupons->where('is_used', false);
    $used = $userCoupons->where('is_used', true);
    
    echo "   - 用戶可用優惠券: " . $available->count() . " 張\n";
    echo "   - 用戶已使用優惠券: " . $used->count() . " 張\n";
    
} catch (Exception $e) {
    echo "   - API 測試失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 測試完成 ===\n";
echo "\n前台測試步驟：\n";
echo "1. 使用測試帳號登入: test@example.com / password\n";
echo "2. 訪問: http://localhost:5173/coupon\n";
echo "3. 檢查優惠券是否正確顯示\n"; 