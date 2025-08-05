<?php
// 測試提取public_id的邏輯

// 模擬改進的extractPublicIdFromUrl方法
function extractPublicIdFromUrl($url)
{
    if (empty($url)) {
        return null;
    }

    echo "提取 public_id from URL: $url\n";

    // 支援多種 Cloudinary URL 格式，正確處理轉換參數
    $patterns = [
        // 帶轉換參數和版本號: https://res.cloudinary.com/cloud_name/image/upload/c_fill,w_200,h_200/v1234567890/folder/image_name.jpg
        '/\/image\/upload\/[^\/]+\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
        // 帶轉換參數無版本號: https://res.cloudinary.com/cloud_name/image/upload/c_fill,w_200,h_200/folder/image_name.jpg
        '/\/image\/upload\/[^\/]+\/([^\/].+)\.(jpg|jpeg|png|gif|webp)$/i',
        // 標準格式帶版本號: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/image_name.jpg
        '/\/image\/upload\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)$/i',
        // 標準格式無版本號: https://res.cloudinary.com/cloud_name/image/upload/folder/image_name.jpg
        '/\/image\/upload\/(.+)\.(jpg|jpeg|png|gif|webp)$/i'
    ];

    foreach ($patterns as $i => $pattern) {
        echo "嘗試模式 $i: $pattern\n";
        if (preg_match($pattern, $url, $matches)) {
            $publicId = $matches[1];
            
            // 如果public_id包含轉換參數，需要進一步處理
            if (strpos($publicId, ',') !== false && strpos($publicId, '/') !== false) {
                echo "  包含轉換參數，跳過此匹配\n";
                continue;
            }
            
            echo "✓ 匹配成功，提取的 public_id: $publicId\n";
            return $publicId;
        }
    }

    // 如果上面的模式都不匹配，嘗試更靈活的解析
    echo "嘗試靈活解析...\n";
    if (preg_match('/\/image\/upload\/(.+)$/i', $url, $matches)) {
        $afterUpload = $matches[1];
        echo "  找到upload後的內容: $afterUpload\n";
        
        // 移除檔案副檔名
        $afterUpload = preg_replace('/\.(jpg|jpeg|png|gif|webp)$/i', '', $afterUpload);
        echo "  移除副檔名後: $afterUpload\n";
        
        // 分割路徑片段
        $segments = explode('/', $afterUpload);
        echo "  路徑片段: " . implode(', ', $segments) . "\n";
        
        // 過濾掉轉換參數（包含逗號的段落）和版本號（v開頭的數字）
        $cleanSegments = [];
        foreach ($segments as $segment) {
            if (strpos($segment, ',') === false && !preg_match('/^v\d+$/', $segment)) {
                $cleanSegments[] = $segment;
                echo "    保留片段: $segment\n";
            } else {
                echo "    丟棄片段: $segment (轉換參數或版本號)\n";
            }
        }
        
        if (!empty($cleanSegments)) {
            $publicId = implode('/', $cleanSegments);
            echo "✓ 靈活解析成功，提取的 public_id: $publicId\n";
            return $publicId;
        }
    }

    echo "✗ 所有方法都無法提取public_id\n";
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
