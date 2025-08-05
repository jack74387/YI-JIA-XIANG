<?php

require_once __DIR__ . '/backend/vendor/autoload.php';

// 載入環境變數
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

echo "=== 完整測試：文章刪除功能 ===\n\n";

// 測試配置
$cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
$apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? '';
$apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';

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

    // 1. 上傳測試圖片
    echo "📤 步驟1: 上傳測試圖片...\n";
    
    $tempImagePath = __DIR__ . '/test_article_delete_image.jpg';
    $imageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A8A');
    file_put_contents($tempImagePath, $imageData);

    $uploadResult1 = $cloudinary->uploadApi()->upload($tempImagePath, [
        'upload_preset' => 'yijiaxiang',
        'resource_type' => 'image',
        'folder' => 'yijiaxiang/articles/delete_test',
        'tags' => ['article', 'delete_test', 'cleanup']
    ]);

    $uploadResult2 = $cloudinary->uploadApi()->upload($tempImagePath, [
        'upload_preset' => 'yijiaxiang',
        'resource_type' => 'image',
        'folder' => 'yijiaxiang/articles/delete_test',
        'tags' => ['article', 'delete_test', 'cleanup']
    ]);

    echo "✅ 圖片1上傳成功: " . $uploadResult1['public_id'] . "\n";
    echo "✅ 圖片2上傳成功: " . $uploadResult2['public_id'] . "\n\n";

    // 2. 模擬文章刪除流程
    echo "🗑️ 步驟2: 模擬文章刪除流程...\n";
    
    $publicIdsToDelete = [
        $uploadResult1['public_id'],
        $uploadResult2['public_id']
    ];
    
    $deletedAssets = [];
    
    foreach ($publicIdsToDelete as $publicId) {
        try {
            $result = $cloudinary->adminApi()->deleteAssets([$publicId]);
            $deletedAssets[] = ['public_id' => $publicId, 'result' => $result];
            echo "✅ 刪除成功: " . $publicId . "\n";
        } catch (Exception $e) {
            echo "❌ 刪除失敗: " . $publicId . " - " . $e->getMessage() . "\n";
        }
    }

    echo "\n";

    // 3. 驗證刪除結果
    echo "🔍 步驟3: 驗證刪除結果...\n";
    
    foreach ($publicIdsToDelete as $publicId) {
        try {
            $cloudinary->adminApi()->asset($publicId);
            echo "❌ 資源仍存在: " . $publicId . "\n";
        } catch (Exception $e) {
            echo "✅ 資源已刪除: " . $publicId . "\n";
        }
    }

    // 清理本地檔案
    if (file_exists($tempImagePath)) {
        unlink($tempImagePath);
    }

    echo "\n📊 測試結果總結:\n";
    echo "- 上傳圖片: 2張\n";
    echo "- 刪除圖片: " . count($deletedAssets) . "張\n";
    echo "- 刪除詳情: " . json_encode($deletedAssets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    echo "✅ 文章刪除 Cloudinary 資源清理功能測試完成\n";
    echo "💡 現在可以在前端測試完整的文章刪除功能\n";

} catch (Exception $e) {
    echo "❌ 錯誤: " . $e->getMessage() . "\n";
    echo "錯誤追蹤: " . $e->getTraceAsString() . "\n";
}
