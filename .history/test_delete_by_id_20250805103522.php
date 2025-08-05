<?php
// 測試通過public_id刪除Cloudinary圖片

require_once 'backend/vendor/autoload.php';

use Cloudinary\Cloudinary;

// 載入環境變數
$dotenv = Dotenv\Dotenv::createImmutable('backend');
$dotenv->load();

try {
    echo "測試通過public_id刪除Cloudinary圖片...\n";
    
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
            'api_key' => $_ENV['CLOUDINARY_API_KEY'],
            'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
        ]
    ]);
    
    // 測試上傳一張圖片
    echo "1. 先上傳一張測試圖片...\n";
    
    // 創建一個簡單的測試圖片
    $testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
    $tempFile = tempnam(sys_get_temp_dir(), 'test_img') . '.png';
    file_put_contents($tempFile, $testImageData);
    
    $uploadResult = $cloudinary->uploadApi()->upload($tempFile, [
        'resource_type' => 'image',
        'folder' => 'yijiaxiang/test',
        'tags' => ['test', 'auto-delete']
    ]);
    
    $publicId = $uploadResult['public_id'];
    $url = $uploadResult['secure_url'];
    
    echo "✓ 圖片上傳成功\n";
    echo "   Public ID: $publicId\n";
    echo "   URL: $url\n\n";
    
    // 測試刪除
    echo "2. 現在刪除這張圖片...\n";
    
    $deleteResult = $cloudinary->uploadApi()->destroy($publicId, [
        'invalidate' => true
    ]);
    
    echo "刪除結果: " . json_encode($deleteResult, JSON_PRETTY_PRINT) . "\n";
    
    if (isset($deleteResult['result'])) {
        if ($deleteResult['result'] === 'ok') {
            echo "✓ 圖片刪除成功！\n";
        } elseif ($deleteResult['result'] === 'not found') {
            echo "⚠ 圖片不存在（可能已被刪除）\n";
        } else {
            echo "✗ 刪除失敗：" . $deleteResult['result'] . "\n";
        }
    } else {
        echo "✗ 刪除響應格式異常\n";
    }
    
    // 清理臨時文件
    unlink($tempFile);
    
    echo "\n3. 測試刪除不存在的圖片...\n";
    
    $fakePublicId = "yijiaxiang/test/non_existent_image";
    $deleteResult2 = $cloudinary->uploadApi()->destroy($fakePublicId);
    
    echo "刪除不存在圖片的結果: " . json_encode($deleteResult2, JSON_PRETTY_PRINT) . "\n";
    
    if (isset($deleteResult2['result']) && $deleteResult2['result'] === 'not found') {
        echo "✓ 正確處理不存在的圖片\n";
    }
    
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
