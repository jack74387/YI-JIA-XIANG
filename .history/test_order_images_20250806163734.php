<?php
// 測試訂單項目圖片顯示
require_once 'backend/vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo "=== 測試訂單項目圖片顯示 ===\n\n";

// 查找一個有訂單項目的訂單
$order = Order::with(['items.product'])->first();

if (!$order) {
    echo "沒有找到訂單資料\n";
    exit;
}

echo "訂單 ID: {$order->id}\n";
echo "訂單項目數量: " . $order->items->count() . "\n\n";

foreach ($order->items as $item) {
    echo "商品: {$item->product->name}\n";
    echo "商品主圖片 (primary_image): " . ($item->product->primary_image ? $item->product->primary_image->image_path : '無') . "\n";
    echo "商品圖片欄位 (image): " . ($item->product->image ?? '無') . "\n";
    
    // 測試 formatOrder 會返回什麼
    $controller = new App\Http\Controllers\OrderController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('formatOrder');
    $method->setAccessible(true);
    
    $formatted = $method->invoke($controller, $order);
    $formattedItem = $formatted['items']->firstWhere('id', $item->id);
    echo "格式化後的 image 欄位: " . ($formattedItem['image'] ?? '無') . "\n";
    echo "---\n";
}
