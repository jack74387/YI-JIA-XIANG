<?php
// 測試購物車圖片顯示
require_once 'backend/vendor/autoload.php';

// 載入 Laravel 應用
$app = require_once 'backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

echo "=== 測試購物車圖片顯示 ===\n\n";

// 查找一個有購物車的用戶
$cart = Cart::with(['items.product.primary_image'])->first();

if (!$cart) {
    echo "沒有找到購物車資料\n";
    exit;
}

echo "購物車 ID: {$cart->id}\n";
echo "用戶 ID: {$cart->user_id}\n\n";

foreach ($cart->items as $item) {
    echo "商品: {$item->product->name}\n";
    echo "主圖片: " . ($item->product->primary_image ? $item->product->primary_image->image_path : '無') . "\n";
    echo "原圖片欄位: " . ($item->product->image ?? '無') . "\n";
    echo "---\n";
}

// 測試 CartController 的 formatCartItem 方法
$testItem = $cart->items->first();
if ($testItem) {
    echo "\n=== 測試 formatCartItem 方法 ===\n";
    $controller = new App\Http\Controllers\CartController();
    
    // 使用反射來調用私有方法
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('formatCartItem');
    $method->setAccessible(true);
    
    $formatted = $method->invoke($controller, $testItem);
    echo "格式化後的圖片路徑: " . ($formatted['image'] ?? '無') . "\n";
    echo "商品資料中的主圖片: " . ($formatted['product']->primary_image?->image_path ?? '無') . "\n";
}
