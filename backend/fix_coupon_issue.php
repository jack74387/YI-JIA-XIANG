<?php

echo "=== 優惠券功能修復腳本 ===\n\n";

// 1. 初始化 Laravel
echo "1. 初始化 Laravel...\n";
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use App\Models\User;

echo "✓ Laravel 初始化完成\n\n";

// 2. 檢查並修復資料表
echo "2. 檢查並修復資料表...\n";

// 檢查 coupons 表
if (!Schema::hasTable('coupons')) {
    echo "- 創建 coupons 表...\n";
    Schema::create('coupons', function($table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('code')->unique();
        $table->string('type'); // percent, fixed
        $table->integer('value');
        $table->integer('min_order')->nullable();
        $table->dateTime('expires_at')->nullable();
        $table->integer('usage_limit')->nullable();
        $table->integer('used_count')->default(0);
        $table->string('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    echo "✓ coupons 表已創建\n";
} else {
    echo "✓ coupons 表已存在\n";
    
    // 檢查並添加缺少的欄位
    if (!Schema::hasColumn('coupons', 'name')) {
        Schema::table('coupons', function($table) {
            $table->string('name')->after('id')->nullable();
        });
        echo "✓ 已添加 name 欄位\n";
    }
    
    if (!Schema::hasColumn('coupons', 'is_active')) {
        Schema::table('coupons', function($table) {
            $table->boolean('is_active')->default(true)->after('description');
        });
        echo "✓ 已添加 is_active 欄位\n";
    }
}

// 檢查 user_coupons 表
if (!Schema::hasTable('user_coupons')) {
    echo "- 創建 user_coupons 表...\n";
    Schema::create('user_coupons', function($table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
        $table->boolean('is_used')->default(false);
        $table->timestamp('used_at')->nullable();
        $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
        $table->timestamps();
        
        $table->unique(['user_id', 'coupon_id']);
        $table->index(['user_id', 'is_used']);
    });
    echo "✓ user_coupons 表已創建\n";
} else {
    echo "✓ user_coupons 表已存在\n";
}

// 檢查 operation_logs 表
if (!Schema::hasTable('operation_logs')) {
    echo "- 創建 operation_logs 表...\n";
    Schema::create('operation_logs', function($table) {
        $table->id();
        $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
        $table->string('action');
        $table->string('ip')->nullable();
        $table->text('user_agent')->nullable();
        $table->json('data')->nullable();
        $table->timestamps();
    });
    echo "✓ operation_logs 表已創建\n";
} else {
    echo "✓ operation_logs 表已存在\n";
}

echo "\n";

// 3. 檢查管理員帳號
echo "3. 檢查管理員帳號...\n";
$admin = User::where('email', 'admin@example.com')->first();
if ($admin) {
    if ($admin->is_admin) {
        echo "✓ 管理員帳號已存在且已啟用\n";
    } else {
        echo "- 啟用管理員權限...\n";
        $admin->is_admin = true;
        $admin->save();
        echo "✓ 管理員權限已啟用\n";
    }
} else {
    echo "- 創建管理員帳號...\n";
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'is_admin' => true
    ]);
    echo "✓ 管理員帳號已創建 (email: admin@example.com, password: password)\n";
}

echo "\n";

// 4. 創建測試優惠券
echo "4. 創建測試優惠券...\n";
try {
    $testCoupon = Coupon::where('code', 'TEST123')->first();
    if (!$testCoupon) {
        $testCoupon = Coupon::create([
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
        echo "✓ 測試優惠券已創建\n";
    } else {
        echo "✓ 測試優惠券已存在\n";
    }
} catch (Exception $e) {
    echo "✗ 創建測試優惠券失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 修復完成 ===\n";
echo "\n請執行以下步驟：\n";
echo "1. 確保後端服務器正在運行 (php artisan serve)\n";
echo "2. 確保前端開發服務器正在運行 (npm run dev)\n";
echo "3. 使用管理員帳號登入 (admin@example.com / password)\n";
echo "4. 訪問 http://localhost:5173/admin/coupons 測試優惠券功能\n"; 