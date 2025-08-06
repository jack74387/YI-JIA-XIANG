<?php

require_once 'vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 測試格式化後的訂單數據
try {
    // 找一個有用戶的訂單來測試
    $order = \App\Models\Order::with(['user', 'items.product'])->whereHas('user')->first();
    
    if (!$order) {
        echo "沒有找到有用戶的訂單來測試\n";
        exit;
    }
    
    echo "測試訂單格式化:\n";
    echo "訂單 ID: " . $order->id . "\n";
    echo "訂單項目數: " . $order->items->count() . "\n\n";
    
    // 模擬 OrderController 的 formatOrderForEmail 方法
    $controller = new \App\Http\Controllers\OrderController();
    
    // 使用反射來調用私有方法
    $reflection = new ReflectionClass($controller);
    $formatMethod = $reflection->getMethod('formatOrderForEmail');
    $formatMethod->setAccessible(true);
    $getSpecTextMethod = $reflection->getMethod('getSpecText');
    $getSpecTextMethod->setAccessible(true);
    
    // 測試 getSpecText 方法
    echo "規格文字測試:\n";
    echo "large -> " . $getSpecTextMethod->invoke($controller, 'large') . "\n";
    echo "small -> " . $getSpecTextMethod->invoke($controller, 'small') . "\n";
    echo "sample -> " . $getSpecTextMethod->invoke($controller, 'sample') . "\n\n";
    
    // 格式化訂單
    $formattedOrder = $formatMethod->invoke($controller, $order);
    
    echo "格式化後的訂單項目:\n";
    foreach ($formattedOrder->items as $item) {
        echo "商品名稱: " . $item->name . "\n";
        echo "規格: " . $item->spec . " (" . $item->spec_text . ")\n";
        echo "數量: " . $item->quantity . "\n";
        echo "單價: NT$" . $item->price . "\n";
        echo "小計: NT$" . $item->subtotal . "\n";
        echo "------------------------\n";
    }
    
} catch (\Exception $e) {
    echo "錯誤: " . $e->getMessage() . "\n";
    echo "檔案: " . $e->getFile() . "\n";
    echo "行號: " . $e->getLine() . "\n";
}
