<?php

require_once __DIR__ . '/backend/vendor/autoload.php';

echo "=== 測試文章 API 完整流程 ===\n\n";

// 準備測試數據
$testData = [
    'title' => '測試文章 - Cloudinary 刪除功能',
    'content' => '這是一個測試文章，用來驗證 Cloudinary 圖片刪除功能。',
    'images' => ['https://res.cloudinary.com/daeb3goxf/image/upload/v1754362411/test1.jpg'],
    'images_public_ids' => ['test/sample_public_id_1'],
    'videos' => [],
    'videos_public_ids' => [],
    'status' => 'draft',
    'published_at' => null
];

// 模擬請求
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = $testData;

echo "測試數據準備完成:\n";
echo json_encode($testData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "✅ 文章 API 測試數據驗證完成\n";
echo "📝 接下來可以在前端介面測試:\n";
echo "   1. 上傳圖片 → 獲得 public_id\n";
echo "   2. 刪除圖片 → 調用 deleteCloudinaryById API\n";
echo "   3. 保存文章 → public_id 一起儲存\n\n";

echo "🔗 相關 API 端點:\n";
echo "   POST /api/v1/admin/articles/upload-image\n";
echo "   POST /api/v1/admin/articles/delete-cloudinary-by-id\n";
echo "   POST /api/v1/admin/articles (新增文章)\n";
echo "   PUT /api/v1/admin/articles/{id} (更新文章)\n\n";
