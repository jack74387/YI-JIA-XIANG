<?php

echo "=== 執行資料庫遷移 ===\n\n";

// 初始化 Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "1. 檢查現有資料表...\n";

// 檢查 coupons 表
if (Schema::hasTable('coupons')) {
    echo "   ✓ coupons 表已存在\n";
    
    // 檢查是否需要添加欄位
    if (!Schema::hasColumn('coupons', 'name')) {
        echo "   - 添加 name 欄位...\n";
        Schema::table('coupons', function($table) {
            $table->string('name')->after('id')->nullable();
        });
        echo "   ✓ name 欄位已添加\n";
    }
    
    if (!Schema::hasColumn('coupons', 'is_active')) {
        echo "   - 添加 is_active 欄位...\n";
        Schema::table('coupons', function($table) {
            $table->boolean('is_active')->default(true)->after('description');
        });
        echo "   ✓ is_active 欄位已添加\n";
    }
} else {
    echo "   - 創建 coupons 表...\n";
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
    echo "   ✓ coupons 表已創建\n";
}

// 檢查 user_coupons 表
if (Schema::hasTable('user_coupons')) {
    echo "   ✓ user_coupons 表已存在\n";
} else {
    echo "   - 創建 user_coupons 表...\n";
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
    echo "   ✓ user_coupons 表已創建\n";
}

echo "\n2. 檢查 operation_logs 表...\n";
if (Schema::hasTable('operation_logs')) {
    echo "   ✓ operation_logs 表已存在\n";
} else {
    echo "   - 創建 operation_logs 表...\n";
    Schema::create('operation_logs', function($table) {
        $table->id();
        $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
        $table->string('action');
        $table->string('ip')->nullable();
        $table->text('user_agent')->nullable();
        $table->json('data')->nullable();
        $table->timestamps();
    });
    echo "   ✓ operation_logs 表已創建\n";
}

echo "\n=== 遷移完成 ===\n"; 