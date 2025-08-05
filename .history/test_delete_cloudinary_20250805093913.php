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
    echo "獲取資源列表...\n";
    $result = $cloudinary->adminApi()->resources([
        'resource_type' => 'image',
        'max_results' => 5,
        'folder' => 'yijiaxiang/products'
    ]);
    
    echo "找到 " . count($result['resources']) . " 個圖片\n\n";
    
    foreach ($result['resources'] as $resource) {
        echo "Public ID: " . $resource['public_id'] . "\n";
        echo "URL: " . $resource['secure_url'] . "\n";
        echo "Size: " . $resource['bytes'] . " bytes\n";
        echo "---\n";
    }
    
    // 如果有圖片，測試刪除第一個（注意：這會真的刪除圖片！）
    if (count($result['resources']) > 0) {
        echo "\n是否要測試刪除第一個圖片？ (y/N): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        
        if (trim($line) === 'y' || trim($line) === 'Y') {
            $publicId = $result['resources'][0]['public_id'];
            echo "嘗試刪除: $publicId\n";
            
            $deleteResult = $cloudinary->uploadApi()->destroy($publicId);
            echo "刪除結果: " . json_encode($deleteResult) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
