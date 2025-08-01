<?php

echo "=== 一佳香管理员登录和图片上传测试 ===\n\n";

// 1. 创建一个1x1像素的PNG图片
$img = imagecreate(1, 1);
$white = imagecolorallocate($img, 255, 255, 255);
imagefilledrectangle($img, 0, 0, 1, 1, $white);
imagepng($img, 'test-image.png');
imagedestroy($img);
echo "✅ 创建了测试图片 test-image.png\n";

// 2. 管理员登录
echo "🔐 正在登录管理员账户...\n";
$loginCh = curl_init();
curl_setopt($loginCh, CURLOPT_URL, 'http://192.168.99.45:8000/api/v1/auth/admin-login');
curl_setopt($loginCh, CURLOPT_POST, true);
curl_setopt($loginCh, CURLOPT_POSTFIELDS, json_encode([
    'email' => 'admin@example.com',
    'password' => '123'
]));
curl_setopt($loginCh, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($loginCh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($loginCh, CURLOPT_HEADER, true);

$loginResponse = curl_exec($loginCh);
$loginHttpCode = curl_getinfo($loginCh, CURLINFO_HTTP_CODE);
curl_close($loginCh);

echo "登录HTTP状态码: $loginHttpCode\n";

// 分离头部和响应体
$headerSize = curl_getinfo($loginCh, CURLINFO_HEADER_SIZE);
list($headers, $body) = explode("\r\n\r\n", $loginResponse, 2);

echo "登录响应: $body\n";

if ($loginHttpCode !== 200) {
    echo "❌ 登录失败\n";
    exit(1);
}

$loginData = json_decode($body, true);
if (!$loginData || !$loginData['success'] || !$loginData['token']) {
    echo "❌ 登录响应格式错误\n";
    exit(1);
}

$token = $loginData['token'];
echo "✅ 登录成功，获得token: " . substr($token, 0, 20) . "...\n\n";

// 3. 使用token上传图片
echo "📤 正在测试图片上传...\n";
$uploadCh = curl_init();
curl_setopt($uploadCh, CURLOPT_URL, 'http://192.168.99.45:8000/api/v1/admin/upload-image');
curl_setopt($uploadCh, CURLOPT_POST, true);
curl_setopt($uploadCh, CURLOPT_POSTFIELDS, [
    'image' => new CURLFile('test-image.png', 'image/png', 'test-image.png')
]);
curl_setopt($uploadCh, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
curl_setopt($uploadCh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($uploadCh, CURLOPT_VERBOSE, true);

$uploadResponse = curl_exec($uploadCh);
$uploadHttpCode = curl_getinfo($uploadCh, CURLINFO_HTTP_CODE);
curl_close($uploadCh);

echo "上传HTTP状态码: $uploadHttpCode\n";
echo "上传响应: $uploadResponse\n";

if ($uploadHttpCode === 200) {
    $uploadData = json_decode($uploadResponse, true);
    if ($uploadData && $uploadData['success']) {
        echo "🎉 图片上传成功到Cloudinary!\n";
        echo "图片URL: " . $uploadData['url'] . "\n";
    } else {
        echo "❌ 上传失败: " . ($uploadData['message'] ?? '未知错误') . "\n";
    }
} else {
    echo "❌ 上传请求失败\n";
}

// 清理
unlink('test-image.png');
echo "\n🧹 清理完成\n";
