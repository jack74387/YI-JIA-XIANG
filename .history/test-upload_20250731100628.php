<?php

// 创建一个1x1像素的PNG图片
$img = imagecreate(1, 1);
$white = imagecolorallocate($img, 255, 255, 255);
imagefilledrectangle($img, 0, 0, 1, 1, $white);
imagepng($img, 'test-image.png');
imagedestroy($img);

echo "创建了测试图片 test-image.png\n";

// 测试上传到后端API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://192.168.99.45:8000/api/v1/admin/upload-image');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'image' => new CURLFile('test-image.png', 'image/png', 'test-image.png')
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
