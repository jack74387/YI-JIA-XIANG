<?php
echo "=== Cloudinary Upload Test with New Credentials ===\n";

// 测试上传到 Cloudinary 的功能
function testCloudinaryUpload() {
    // 使用新的 API 密钥
    $cloudName = 'daeb3goxf';
    $apiKey = '697592912781924';
    $apiSecret = '20hBk7nMJHVu856JQShTuuDkwyw';
    
    echo "Cloud Name: $cloudName\n";
    echo "API Key: $apiKey\n";
    echo "API Secret: " . substr($apiSecret, 0, 6) . "****\n\n";
    
    // 直接测试后端上传 API 路由
    $backendUrl = 'http://localhost:8000/api/admin/upload-image';
    
    // 创建一个测试图片文件
    $testImageContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k=');
    $tempFile = tempnam(sys_get_temp_dir(), 'test_image') . '.jpg';
    file_put_contents($tempFile, $testImageContent);
    
    // 创建 cURL 请求测试无权限检查的上传
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $backendUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'image' => new CURLFile($tempFile, 'image/jpeg', 'test.jpg')
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);
    
    echo "Testing upload to: $backendUrl\n";
    echo "Testing without authentication (permission check removed)...\n";
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    unlink($tempFile); // 清理临时文件
    
    echo "HTTP Status: $httpCode\n";
    if ($error) {
        echo "cURL Error: $error\n";
    }
    
    if ($response) {
        echo "Response: " . json_encode(json_decode($response, true), JSON_PRETTY_PRINT) . "\n";
        
        $responseData = json_decode($response, true);
        if (isset($responseData['success']) && $responseData['success']) {
            echo "✅ Upload test PASSED - Image uploaded successfully!\n";
            if (isset($responseData['url'])) {
                echo "✅ Cloudinary URL: {$responseData['url']}\n";
            }
            if (isset($responseData['public_id'])) {
                echo "✅ Public ID: {$responseData['public_id']}\n";
            }
        } else {
            echo "❌ Upload test FAILED\n";
            if (isset($responseData['message'])) {
                echo "Error message: {$responseData['message']}\n";
            }
        }
    } else {
        echo "❌ No response received\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
}

// 测试文章上传 API
function testArticleUpload() {
    $backendUrl = 'http://localhost:8000/api/admin/articles/upload-image';
    
    // 创建一个测试图片文件
    $testImageContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k=');
    $tempFile = tempnam(sys_get_temp_dir(), 'test_article_image') . '.jpg';
    file_put_contents($tempFile, $testImageContent);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $backendUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'image' => new CURLFile($tempFile, 'image/jpeg', 'test_article.jpg')
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);
    
    echo "Testing article image upload to: $backendUrl\n";
    echo "Testing without authentication (permission check removed)...\n";
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    unlink($tempFile);
    
    echo "HTTP Status: $httpCode\n";
    if ($error) {
        echo "cURL Error: $error\n";
    }
    
    if ($response) {
        echo "Response: " . json_encode(json_decode($response, true), JSON_PRETTY_PRINT) . "\n";
        
        $responseData = json_decode($response, true);
        if (isset($responseData['success']) && $responseData['success']) {
            echo "✅ Article upload test PASSED!\n";
            if (isset($responseData['url'])) {
                echo "✅ Cloudinary URL: {$responseData['url']}\n";
            }
        } else {
            echo "❌ Article upload test FAILED\n";
        }
    } else {
        echo "❌ No response received\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
}

// 运行测试
testCloudinaryUpload();
testArticleUpload();

echo "Upload tests completed!\n";
echo "New API credentials: 697592912781924 / 20hBk7nMJHVu856JQShTuuDkwyw\n";
echo "Upload Preset: yijiaxiang\n";
echo "Permission checks: REMOVED ✅\n";
?>
