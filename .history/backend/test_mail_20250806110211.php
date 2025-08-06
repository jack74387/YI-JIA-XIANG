<?php

require_once 'vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 測試郵件發送
try {
    // 找一個有用戶的訂單來測試
    $order = \App\Models\Order::with(['user', 'items'])->whereHas('user')->first();
    
    if (!$order) {
        echo "沒有找到有用戶的訂單來測試\n";
        exit;
    }
    
    echo "找到測試訂單:\n";
    echo "訂單 ID: " . $order->id . "\n";
    echo "用戶: " . ($order->user ? $order->user->name : '無') . "\n";
    echo "用戶信箱: " . ($order->user ? $order->user->email : '無') . "\n";
    echo "當前狀態: " . $order->status . "\n";
    
    if (!$order->user || !$order->user->email) {
        echo "訂單沒有有效的用戶信箱，無法測試郵件發送\n";
        exit;
    }
    
    // 創建郵件實例
    $mail = new \App\Mail\OrderStatusNotification($order, $order->status, 'processing');
    
    // 發送測試郵件
    echo "\n正在發送測試郵件...\n";
    \Illuminate\Support\Facades\Mail::to($order->user->email)->send($mail);
    
    echo "郵件發送成功！\n";
    echo "收件人: " . $order->user->email . "\n";
    
} catch (\Exception $e) {
    echo "錯誤: " . $e->getMessage() . "\n";
    echo "檔案: " . $e->getFile() . "\n";
    echo "行號: " . $e->getLine() . "\n";
}
