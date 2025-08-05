<?php
// 測試刪除Cloudinary圖片的簡單腳本

require_once 'backend/vendor/autoload.php';

use Cloudinary\Cloudinary;

// 載入環境變數
$dotenv = Dotenv\Dotenv::createImmutable('backend');
$dotenv->load();

try {
    echo "測試Cloudinary連接...\n";
    
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
            'api_key' => $_ENV['CLOUDINARY_API_KEY'],
            'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
        ]
    ]);
    
    echo "Cloudinary配置:\n";
    echo "Cloud Name: " . $_ENV['CLOUDINARY_CLOUD_NAME'] . "\n";
    echo "API Key: " . $_ENV['CLOUDINARY_API_KEY'] . "\n";
    echo "API Secret: " . (strlen($_ENV['CLOUDINARY_API_SECRET']) > 0 ? "已設置" : "未設置") . "\n\n";
    
    // 測試獲取資源列表
    echo "測試基本功能...\n";
    
    // 測試一個簡單的刪除操作（使用一個不存在的public_id來測試API響應）
    echo "測試刪除API...\n";
    $testPublicId = "test_non_existent_image";
    $deleteResult = $cloudinary->uploadApi()->destroy($testPublicId);
    echo "測試刪除結果: " . json_encode($deleteResult) . "\n";
    
    // 如果結果包含 'result' 鍵，則API工作正常
    if (isset($deleteResult['result'])) {
        echo "✓ Cloudinary刪除API工作正常\n";
        if ($deleteResult['result'] === 'not found') {
            echo "✓ 測試成功：圖片不存在時正確返回'not found'\n";
        }
    } else {
        echo "✗ Cloudinary刪除API返回異常結果\n";
    }
    
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
