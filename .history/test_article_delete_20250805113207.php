<?php

require_once __DIR__ . '/backend/vendor/autoload.php';

// 載入環境變數
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

echo "=== 測試文章刪除時 Cloudinary 資源清理功能 ===\n\n";

// 模擬文章數據
class MockArticle {
    public $id = 999;
    public $title = '測試文章';
    public $images_public_ids = ['yijiaxiang/articles/test/sample1', 'yijiaxiang/articles/test/sample2'];
    public $videos_public_ids = ['yijiaxiang/articles/test/video1'];
    public $images = ['https://res.cloudinary.com/daeb3goxf/image/upload/v1234567890/legacy_image.jpg'];
    public $videos = [];
}

$article = new MockArticle();

echo "模擬文章數據:\n";
echo "ID: " . $article->id . "\n";
echo "標題: " . $article->title . "\n";
echo "圖片 public_ids: " . implode(', ', $article->images_public_ids) . "\n";
echo "影片 public_ids: " . implode(', ', $article->videos_public_ids) . "\n";
echo "舊格式圖片: " . implode(', ', $article->images) . "\n\n";

// 測試 URL 解析功能
function extractPublicIdFromUrl($url) {
    if (empty($url)) {
        return null;
    }

    // 解析 Cloudinary URL 格式
    $pattern = '/\/upload\/(?:v\d+\/)?(.+?)(?:\.[^.]+)?$/';
    
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }

    return null;
}

echo "📝 測試 URL 解析功能:\n";
$testUrls = [
    'https://res.cloudinary.com/daeb3goxf/image/upload/v1234567890/legacy_image.jpg',
    'https://res.cloudinary.com/daeb3goxf/image/upload/yijiaxiang/articles/test_image.png',
    'https://res.cloudinary.com/daeb3goxf/video/upload/v1234567890/yijiaxiang/videos/test.mp4',
];

foreach ($testUrls as $url) {
    $publicId = extractPublicIdFromUrl($url);
    echo "URL: " . $url . "\n";
    echo "提取的 public_id: " . ($publicId ?: '無法提取') . "\n\n";
}

echo "🔧 文章刪除時會執行的操作:\n";
echo "1. 刪除 images_public_ids 中的每個圖片\n";
echo "2. 刪除 videos_public_ids 中的每個影片\n";
echo "3. 嘗試從舊格式 URL 提取 public_id 並刪除\n";
echo "4. 記錄所有操作結果\n";
echo "5. 即使 Cloudinary 刪除失敗，也會繼續刪除文章記錄\n\n";

echo "✅ 文章刪除功能測試準備完成\n";
echo "💡 實際測試請在前端介面進行文章刪除操作\n";
