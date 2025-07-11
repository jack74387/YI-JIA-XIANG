<?php

// 簡單的測試腳本
echo "=== 優惠券功能測試 ===\n\n";

// 1. 檢查 Laravel 是否可用
echo "1. 檢查 Laravel 環境...\n";
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "   ✓ Laravel 環境正常\n";
} catch (Exception $e) {
    echo "   ✗ Laravel 環境錯誤: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. 檢查資料庫連接
echo "\n2. 檢查資料庫連接...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=yi_jia_xiang', 'root', '');
    echo "   ✓ 資料庫連接正常\n";
} catch (Exception $e) {
    echo "   ✗ 資料庫連接失敗: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. 檢查資料表
echo "\n3. 檢查資料表...\n";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'coupons'");
    if ($stmt->rowCount() > 0) {
        echo "   ✓ coupons 表存在\n";
        
        // 檢查欄位
        $stmt = $pdo->query("DESCRIBE coupons");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $requiredColumns = ['name', 'code', 'type', 'value', 'is_active'];
        foreach ($requiredColumns as $col) {
            if (in_array($col, $columns)) {
                echo "   ✓ 欄位 {$col} 存在\n";
            } else {
                echo "   ✗ 欄位 {$col} 不存在\n";
            }
        }
    } else {
        echo "   ✗ coupons 表不存在\n";
    }
} catch (Exception $e) {
    echo "   ✗ 檢查資料表失敗: " . $e->getMessage() . "\n";
}

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_coupons'");
    if ($stmt->rowCount() > 0) {
        echo "   ✓ user_coupons 表存在\n";
    } else {
        echo "   ✗ user_coupons 表不存在\n";
    }
} catch (Exception $e) {
    echo "   ✗ 檢查 user_coupons 表失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 測試完成 ===\n"; 