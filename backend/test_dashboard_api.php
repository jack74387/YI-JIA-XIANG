<?php

echo "=== 測試儀錶板 API 回應 ===\n\n";

// 初始化 Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Http\Controllers\AdminController;

echo "1. 創建測試管理員用戶...\n";

// 創建或獲取測試管理員
$admin = User::where('email', 'admin@example.com')->first();
if (!$admin) {
    $admin = User::create([
        'name' => '測試管理員',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'is_admin' => true
    ]);
    echo "   - 已創建測試管理員\n";
} else {
    echo "   - 測試管理員已存在\n";
}

echo "\n2. 測試儀錶板 API...\n";

// 創建請求物件
$request = new \Illuminate\Http\Request();
$request->setUserResolver(function() use ($admin) {
    return $admin;
});

// 創建控制器實例
$controller = new AdminController();

try {
    // 調用儀錶板方法
    $response = $controller->dashboard($request);
    $data = $response->getData();
    
    if ($data->success) {
        echo "   - API 回應成功\n";
        
        $dashboardData = $data->data;
        
        // 檢查必要欄位
        $requiredFields = [
            'overview' => ['total_products', 'total_orders', 'total_members', 'total_coupons'],
            'today' => ['orders', 'revenue', 'new_members'],
            'week' => ['orders', 'revenue'],
            'month' => ['orders', 'revenue'],
            'order_status' => [],
            'recent_orders' => [],
            'recent_members' => [],
            'recent_logs' => [],
            'sales_trend' => []
        ];
        
        foreach ($requiredFields as $section => $fields) {
            if (isset($dashboardData->$section)) {
                echo "   - {$section}: 存在\n";
                foreach ($fields as $field) {
                    if (isset($dashboardData->$section->$field)) {
                        echo "     * {$field}: " . $dashboardData->$section->$field . "\n";
                    } else {
                        echo "     * {$field}: 缺失\n";
                    }
                }
            } else {
                echo "   - {$section}: 缺失\n";
            }
        }
        
        // 檢查數據類型
        echo "\n3. 檢查數據類型...\n";
        echo "   - sales_trend 類型: " . gettype($dashboardData->sales_trend) . "\n";
        echo "   - order_status 類型: " . gettype($dashboardData->order_status) . "\n";
        echo "   - recent_orders 類型: " . gettype($dashboardData->recent_orders) . "\n";
        echo "   - recent_members 類型: " . gettype($dashboardData->recent_members) . "\n";
        echo "   - recent_logs 類型: " . gettype($dashboardData->recent_logs) . "\n";
        
        // 檢查是否有 null 值
        echo "\n4. 檢查 null 值...\n";
        $nullFields = [];
        foreach ($dashboardData as $section => $sectionData) {
            if (is_object($sectionData)) {
                foreach ($sectionData as $field => $value) {
                    if ($value === null) {
                        $nullFields[] = "{$section}.{$field}";
                    }
                }
            }
        }
        
        if (empty($nullFields)) {
            echo "   - 沒有發現 null 值\n";
        } else {
            echo "   - 發現 null 值:\n";
            foreach ($nullFields as $field) {
                echo "     * {$field}\n";
            }
        }
        
    } else {
        echo "   - API 回應失敗: " . $data->message . "\n";
    }
    
} catch (Exception $e) {
    echo "   - API 測試失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 測試完成 ===\n";
echo "\n修復內容：\n";
echo "1. formatNumber 函數已添加 null 檢查\n";
echo "2. getBarHeight 函數已添加安全檢查\n";
echo "3. 模板中的 v-for 已添加預設值\n";
echo "4. 所有數據訪問都已添加安全檢查\n"; 