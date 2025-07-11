<?php

echo "=== 資料庫檢查 ===\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=yi_jia_xiang', 'root', '');
    echo "✓ 資料庫連接成功\n\n";
    
    // 檢查 coupons 表
    $stmt = $pdo->query("SHOW TABLES LIKE 'coupons'");
    if ($stmt->rowCount() > 0) {
        echo "✓ coupons 表存在\n";
        
        // 檢查欄位
        $stmt = $pdo->query("DESCRIBE coupons");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'Field');
        
        $requiredColumns = ['name', 'code', 'type', 'value', 'is_active'];
        foreach ($requiredColumns as $col) {
            if (in_array($col, $columnNames)) {
                echo "  ✓ {$col} 欄位存在\n";
            } else {
                echo "  ✗ {$col} 欄位不存在\n";
            }
        }
    } else {
        echo "✗ coupons 表不存在\n";
    }
    
    echo "\n";
    
    // 檢查 user_coupons 表
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_coupons'");
    if ($stmt->rowCount() > 0) {
        echo "✓ user_coupons 表存在\n";
    } else {
        echo "✗ user_coupons 表不存在\n";
    }
    
    echo "\n";
    
    // 檢查 operation_logs 表
    $stmt = $pdo->query("SHOW TABLES LIKE 'operation_logs'");
    if ($stmt->rowCount() > 0) {
        echo "✓ operation_logs 表存在\n";
    } else {
        echo "✗ operation_logs 表不存在\n";
    }
    
} catch (Exception $e) {
    echo "✗ 資料庫連接失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 檢查完成 ===\n"; 