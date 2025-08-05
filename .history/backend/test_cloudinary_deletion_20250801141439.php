<?php
/**
 * 測試 Cloudinary 圖片刪除功能
 * 使用方法：在後端目錄運行 php test_cloudinary_deletion.php
 */

require_once 'vendor/autoload.php';

use Cloudinary\Cloudinary;

// 載入環境變數
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv($line);
        }
    }
}

echo "=== Cloudinary 圖片刪除功能測試 ===\n\n";

try {
    // 初始化 Cloudinary
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
            'api_key' => getenv('CLOUDINARY_API_KEY'),
            'api_secret' => getenv('CLOUDINARY_API_SECRET')
        ]
    ]);

    echo "✅ Cloudinary 初始化成功\n";
    echo "Cloud Name: " . getenv('CLOUDINARY_CLOUD_NAME') . "\n";
    echo "API Key: ***" . substr(getenv('CLOUDINARY_API_KEY'), -4) . "\n\n";

    // 測試取得資源列表
    echo "📋 取得 Cloudinary 資源列表...\n";
    $resources = $cloudinary->adminApi()->resources([
        'resource_type' => 'image',
        'max_results' => 10,
        'prefix' => 'yijiaxiang/'  // 如果有指定資料夾
    ]);

    echo "✅ 找到 " . count($resources['resources']) . " 個圖片資源\n\n";

    // 顯示前幾個資源
    foreach (array_slice($resources['resources'], 0, 5) as $resource) {
        echo "📷 " . $resource['public_id'] . " (" . $resource['format'] . ")\n";
        echo "   URL: " . $resource['secure_url'] . "\n";
        echo "   Size: " . number_format($resource['bytes']) . " bytes\n";
        echo "   Created: " . $resource['created_at'] . "\n\n";
    }

    // 測試 public_id 提取函數
    echo "🔍 測試 public_id 提取函數...\n";
    
    $testUrls = [
        'https://res.cloudinary.com/daeb3goxf/image/upload/v1234567890/yijiaxiang/products/test_image.jpg',
        'https://res.cloudinary.com/daeb3goxf/image/upload/yijiaxiang/products/test_image.jpg',
        'https://res.cloudinary.com/daeb3goxf/image/upload/c_fill,w_200,h_200/v1234567890/yijiaxiang/products/test_image.jpg',
        'https://res.cloudinary.com/daeb3goxf/image/upload/c_fill,w_200,h_200/yijiaxiang/products/test_image.jpg'
    ];

    function extractPublicIdFromUrl($url) {
        $patterns = [
            '/\/image\/upload\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
            '/\/image\/upload\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
            '/\/image\/upload\/[^\/]+\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
            '/\/image\/upload\/[^\/]+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    foreach ($testUrls as $url) {
        $publicId = extractPublicIdFromUrl($url);
        echo "URL: " . $url . "\n";
        echo "Public ID: " . ($publicId ?: 'Not found') . "\n\n";
    }

    echo "✅ 所有測試完成！\n";

} catch (Exception $e) {
    echo "❌ 錯誤: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
