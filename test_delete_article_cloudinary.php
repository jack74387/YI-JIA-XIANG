<?php

require_once __DIR__ . '/backend/vendor/autoload.php';

// 載入環境變數
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

echo "=== 測試文章 Cloudinary 刪除功能 ===\n\n";

// 測試配置
$cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
$apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? '';
$apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';

echo "Cloudinary 配置:\n";
echo "Cloud Name: " . ($cloudName ? '已設置' : '未設置') . "\n";
echo "API Key: " . ($apiKey ? '已設置' : '未設置') . "\n";
echo "API Secret: " . ($apiSecret ? '已設置' : '未設置') . "\n\n";

if (!$cloudName || !$apiKey || !$apiSecret) {
    echo "❌ Cloudinary 配置缺失\n";
    exit(1);
}

try {
    // 建立 Cloudinary 實例
    $config = [
        'cloud' => [
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret
        ]
    ];

    $cloudinary = new \Cloudinary\Cloudinary($config);
    echo "✅ Cloudinary 實例建立成功\n\n";

    // 測試上傳一個圖片
    echo "📤 測試上傳圖片...\n";
    
    // 創建一個簡單的測試圖片
    $tempImagePath = __DIR__ . '/test_article_image.jpg';
    $imageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A8A');
    file_put_contents($tempImagePath, $imageData);

    $uploadResult = $cloudinary->uploadApi()->upload($tempImagePath, [
        'upload_preset' => 'yijiaxiang',
        'resource_type' => 'image',
        'folder' => 'yijiaxiang/articles/test',
        'tags' => ['article', 'test', 'cleanup']
    ]);

    echo "✅ 圖片上傳成功\n";
    echo "Public ID: " . $uploadResult['public_id'] . "\n";
    echo "URL: " . $uploadResult['secure_url'] . "\n\n";

    // 測試刪除
    echo "🗑️ 測試刪除圖片...\n";
    
    $deleteResult = $cloudinary->adminApi()->deleteAssets([$uploadResult['public_id']]);
    
    echo "✅ 刪除成功\n";
    echo "刪除結果: " . json_encode($deleteResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    // 清理本地檔案
    if (file_exists($tempImagePath)) {
        unlink($tempImagePath);
    }

    echo "✅ 文章 Cloudinary 刪除功能測試完成\n";

} catch (Exception $e) {
    echo "❌ 錯誤: " . $e->getMessage() . "\n";
    echo "錯誤追蹤: " . $e->getTraceAsString() . "\n";
}
