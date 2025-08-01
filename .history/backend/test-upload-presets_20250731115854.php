<?php
/**
 * 測試 Cloudinary Upload Presets 功能
 * 這個腳本可以幫助您驗證 Upload Preset "yijiaxiang" 是否正確配置
 */

require_once 'vendor/autoload.php';

// 讀取環境變量
$cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: env('CLOUDINARY_CLOUD_NAME', 'daeb3goxf');
$apiKey = getenv('CLOUDINARY_API_KEY') ?: env('CLOUDINARY_API_KEY', '815926459439819');
$apiSecret = getenv('CLOUDINARY_API_SECRET') ?: env('CLOUDINARY_API_SECRET', '4wRbJUBxsfJdQCR1OZk_o4_fu94');

echo "=== Cloudinary Upload Presets 測試 ===\n";
echo "Cloud Name: " . ($cloudName ?: '未設置') . "\n";
echo "API Key: " . ($apiKey ? substr($apiKey, 0, 6) . '...' : '未設置') . "\n";
echo "API Secret: " . ($apiSecret ? substr($apiSecret, 0, 6) . '...' : '未設置') . "\n\n";

if (!$cloudName || !$apiKey || !$apiSecret) {
    echo "❌ Cloudinary 配置缺失，無法進行測試\n";
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
    
    // 檢查是否有可用的 Upload Presets
    echo "=== 檢查 Upload Presets ===\n";
    
    // 嘗試列出 Upload Presets（需要管理員權限）
    try {
        $presets = $cloudinary->adminApi()->uploadPresets();
        
        echo "✅ 成功獲取 Upload Presets 列表\n";
        echo "總數: " . count($presets['presets']) . "\n\n";
        
        $yijiaxiangFound = false;
        foreach ($presets['presets'] as $preset) {
            if ($preset['name'] === 'yijiaxiang') {
                $yijiaxiangFound = true;
                echo "✅ 找到 'yijiaxiang' Upload Preset\n";
                echo "設置: " . json_encode($preset, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
                break;
            }
        }
        
        if (!$yijiaxiangFound) {
            echo "⚠️  未找到 'yijiaxiang' Upload Preset\n";
            echo "建議在 Cloudinary 控制台建立名為 'yijiaxiang' 的 Upload Preset\n\n";
            
            echo "建議的 Upload Preset 設置:\n";
            echo "- 名稱: yijiaxiang\n";
            echo "- 模式: Unsigned (用於前端直接上傳) 或 Signed (用於後端上傳)\n";
            echo "- 資料夾: yijiaxiang/\n";
            echo "- 轉換: quality_auto, fetch_format_auto\n";
            echo "- 標籤: yijiaxiang, auto-upload\n";
            echo "- 內容類型: image, video\n\n";
        }
        
    } catch (\Exception $e) {
        echo "⚠️  無法獲取 Upload Presets 列表: " . $e->getMessage() . "\n";
        echo "這可能是因為權限不足，但不影響使用 Upload Presets\n\n";
    }
    
    // 如果有測試圖片，可以嘗試上傳
    $testImagePath = __DIR__ . '/test-image.jpg';
    if (file_exists($testImagePath)) {
        echo "=== 測試上傳圖片 (使用 Upload Preset) ===\n";
        
        try {
            $uploadResult = $cloudinary->uploadApi()->upload($testImagePath, [
                'upload_preset' => 'yijiaxiang',
                'resource_type' => 'image',
                'context' => [
                    'alt' => 'Test image for yijiaxiang preset',
                    'caption' => 'Test upload from PHP script'
                ],
                'tags' => ['test', 'yijiaxiang', 'php-script']
            ]);
            
            echo "✅ 測試上傳成功！\n";
            echo "URL: " . $uploadResult['secure_url'] . "\n";
            echo "Public ID: " . $uploadResult['public_id'] . "\n";
            echo "格式: " . $uploadResult['format'] . "\n";
            echo "尺寸: " . $uploadResult['width'] . "x" . $uploadResult['height'] . "\n";
            echo "檔案大小: " . number_format($uploadResult['bytes'] / 1024, 2) . " KB\n\n";
            
        } catch (\Exception $e) {
            echo "❌ 測試上傳失敗: " . $e->getMessage() . "\n";
            echo "請檢查 'yijiaxiang' Upload Preset 是否已正確建立\n\n";
        }
    } else {
        echo "=== 無測試圖片 ===\n";
        echo "如果要測試上傳功能，請在同目錄下放置 test-image.jpg 檔案\n\n";
    }
    
    echo "=== Upload Preset 建立指南 ===\n";
    echo "1. 登入 Cloudinary 控制台 (https://cloudinary.com/console)\n";
    echo "2. 導航到 Settings > Upload\n";
    echo "3. 點擊 'Add upload preset'\n";
    echo "4. 設置以下參數:\n";
    echo "   - Preset name: yijiaxiang\n";
    echo "   - Signing mode: Signed (推薦，更安全)\n";
    echo "   - Folder: yijiaxiang (可選，但建議設置)\n";
    echo "   - 在 'Incoming transformation' 區域添加:\n";
    echo "     * Quality: Auto\n";
    echo "     * Format: Auto\n";
    echo "   - 在 'Upload manipulations' 區域:\n";
    echo "     * Auto tagging: 啟用\n";
    echo "     * Tags: yijiaxiang,auto-upload\n";
    echo "5. 保存設置\n\n";
    
    echo "✅ Upload Presets 測試完成\n";
    
} catch (\Exception $e) {
    echo "❌ 測試過程中發生錯誤: " . $e->getMessage() . "\n";
    echo "錯誤類型: " . get_class($e) . "\n";
    echo "請檢查 Cloudinary 配置和網路連接\n";
}
