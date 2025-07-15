<?php

/**
 * 清除會員資料腳本
 * 使用方法: php clear-member-data.php
 */

require_once 'vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PointTransaction;
use App\Models\UserCoupon;
use App\Models\OperationLog;

echo "=== 會員資料清除腳本 ===\n\n";

// 顯示清除前的統計
echo "清除前的統計：\n";
echo "總用戶數: " . User::count() . "\n";
echo "管理員數: " . User::where('is_admin', true)->count() . "\n";
echo "會員數: " . User::where('is_admin', false)->count() . "\n";
echo "訂單數: " . Order::count() . "\n";
echo "購物車數: " . Cart::count() . "\n";
echo "購物車項目數: " . CartItem::count() . "\n";
echo "點數交易數: " . PointTransaction::count() . "\n";
echo "用戶優惠券數: " . UserCoupon::count() . "\n";
echo "操作日誌數: " . OperationLog::count() . "\n\n";

// 確認是否繼續
echo "警告：此操作將清除所有會員相關資料！\n";
echo "請確認是否繼續？(y/N): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
fclose($handle);

if (trim($line) !== 'y' && trim($line) !== 'Y') {
    echo "操作已取消。\n";
    exit;
}

echo "\n開始清除會員資料...\n";

try {
    // 開始事務
    DB::beginTransaction();
    
    // 1. 清除用戶優惠券
    $userCouponCount = UserCoupon::count();
    UserCoupon::truncate();
    echo "✓ 已清除 {$userCouponCount} 個用戶優惠券記錄\n";
    
    // 2. 清除點數交易記錄
    $pointTransactionCount = PointTransaction::count();
    PointTransaction::truncate();
    echo "✓ 已清除 {$pointTransactionCount} 個點數交易記錄\n";
    
    // 3. 清除購物車項目
    $cartItemCount = CartItem::count();
    CartItem::truncate();
    echo "✓ 已清除 {$cartItemCount} 個購物車項目\n";
    
    // 4. 清除購物車
    $cartCount = Cart::count();
    Cart::truncate();
    echo "✓ 已清除 {$cartCount} 個購物車\n";
    
    // 5. 清除訂單（保留管理員相關的訂單）
    $orderCount = Order::count();
    Order::truncate();
    echo "✓ 已清除 {$orderCount} 個訂單\n";
    
    // 6. 清除會員用戶（保留管理員）
    $memberCount = User::where('is_admin', false)->count();
    User::where('is_admin', false)->delete();
    echo "✓ 已清除 {$memberCount} 個會員帳號\n";
    
    // 7. 清除操作日誌（可選）
    $operationLogCount = OperationLog::count();
    OperationLog::truncate();
    echo "✓ 已清除 {$operationLogCount} 個操作日誌\n";
    
    // 提交事務
    DB::commit();
    
    echo "\n=== 清除完成 ===\n";
    echo "清除後的統計：\n";
    echo "總用戶數: " . User::count() . "\n";
    echo "管理員數: " . User::where('is_admin', true)->count() . "\n";
    echo "會員數: " . User::where('is_admin', false)->count() . "\n";
    echo "訂單數: " . Order::count() . "\n";
    echo "購物車數: " . Cart::count() . "\n";
    echo "購物車項目數: " . CartItem::count() . "\n";
    echo "點數交易數: " . PointTransaction::count() . "\n";
    echo "用戶優惠券數: " . UserCoupon::count() . "\n";
    echo "操作日誌數: " . OperationLog::count() . "\n";
    
    echo "\n✓ 所有會員資料已成功清除！\n";
    
} catch (Exception $e) {
    // 回滾事務
    DB::rollback();
    echo "\n❌ 清除過程中發生錯誤：" . $e->getMessage() . "\n";
    echo "所有變更已回滾。\n";
} 