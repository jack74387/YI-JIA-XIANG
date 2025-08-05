<?php
// 測試提取public_id的邏輯

// 模擬extractPublicIdFromUrl方法
function extractPublicIdFromUrl($url)
{
    if (empty($url)) {
        return null;
    }

    echo "提取 public_id from URL: $url\n";

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

    foreach ($patterns as $i => $pattern) {
        echo "嘗試模式 $i: $pattern\n";
        if (preg_match($pattern, $url, $matches)) {
            $publicId = $matches[1];
            echo "✓ 匹配成功，提取的 public_id: $publicId\n";
            return $publicId;
        }
    }

    echo "✗ 所有模式都不匹配\n";
    return null;
}

// 測試各種URL格式
$testUrls = [
    'https://res.cloudinary.com/daeb3goxf/image/upload/v1735995168/yijiaxiang/products/sample1.jpg',
    'https://res.cloudinary.com/daeb3goxf/image/upload/yijiaxiang/products/sample2.png',
    'https://res.cloudinary.com/daeb3goxf/image/upload/c_fill,w_200,h_200/v1735995168/yijiaxiang/products/sample3.jpg',
    'https://res.cloudinary.com/daeb3goxf/image/upload/c_fill,w_200,h_200/yijiaxiang/products/sample4.png',
];

foreach ($testUrls as $url) {
    echo "\n測試URL: $url\n";
    echo str_repeat("-", 80) . "\n";
    $publicId = extractPublicIdFromUrl($url);
    echo "結果: " . ($publicId ?: "null") . "\n";
    echo "\n";
}
