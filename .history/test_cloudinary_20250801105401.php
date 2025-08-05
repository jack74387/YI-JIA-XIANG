<?php
require_once 'vendor/autoload.php';

use Cloudinary\Cloudinary;

echo "Testing Cloudinary Connection...\n";

try {
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => 'daeb3goxf',
            'api_key' => '725733994373681',
            'api_secret' => 'YOUR_API_SECRET_HERE'  // 請修改為實際的 API Secret
        ]
    ]);

    echo "Cloudinary initialized successfully\n";
    
    // 測試取得資源列表
    $result = $cloudinary->adminApi()->resources([
        'resource_type' => 'image',
        'max_results' => 3
    ]);

    echo "Resources found: " . count($result['resources']) . "\n";
    
    foreach ($result['resources'] as $resource) {
        echo "Image: " . $resource['public_id'] . " - " . $resource['secure_url'] . "\n";
        
        // 測試提取 public_id 的函數
        $publicId = extractPublicIdFromUrl($resource['secure_url']);
        echo "Extracted public_id: " . $publicId . "\n\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

/**
 * 從 Cloudinary URL 提取 public_id
 */
function extractPublicIdFromUrl($url)
{
    if (empty($url)) {
        return null;
    }

    // 支援多種 Cloudinary URL 格式
    $patterns = [
        // 標準格式: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/image_name.jpg
        '/\/image\/upload\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
        // 無版本號格式: https://res.cloudinary.com/cloud_name/image/upload/folder/image_name.jpg
        '/\/image\/upload\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
        // 轉換格式: https://res.cloudinary.com/cloud_name/image/upload/c_fill,w_200,h_200/v1234567890/folder/image_name.jpg
        '/\/image\/upload\/[^\/]+\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
        // 轉換格式無版本號: https://res.cloudinary.com/cloud_name/image/upload/c_fill,w_200,h_200/folder/image_name.jpg
        '/\/image\/upload\/[^\/]+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i'
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }

    return null;
}

echo "Test completed.\n";
?>
