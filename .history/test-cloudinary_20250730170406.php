<?php
require_once 'backend/vendor/autoload.php';

// 載入 .env 檔案
$dotenv = Dotenv\Dotenv::createImmutable('backend');
$dotenv->load();

// 測試 Cloudinary 配置
echo "=== Cloudinary 配置測試 ===\n";

$cloudinaryUrl = $_ENV['CLOUDINARY_URL'] ?? null;
echo "CLOUDINARY_URL: " . ($cloudinaryUrl ? "已設定" : "未設定") . "\n";

if ($cloudinaryUrl) {
    // 解析 URL
    $urlParts = parse_url($cloudinaryUrl);
    if ($urlParts) {
        echo "API Key: " . ($urlParts['user'] ?? '未設定') . "\n";
        echo "API Secret: " . (isset($urlParts['pass']) ? str_repeat('*', strlen($urlParts['pass'])) : '未設定') . "\n";
        echo "Cloud Name: " . ($urlParts['host'] ?? '未設定') . "\n";
    } else {
        echo "無法解析 CLOUDINARY_URL\n";
    }
}

// 測試 Laravel Cloudinary 套件
try {
    // 初始化 Laravel 應用程序
    $app = require_once 'backend/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    // 測試 Cloudinary 連接
    $cloudinary = cloudinary();
    echo "\nCloudinary 套件: 已載入\n";
    
    // 測試基本配置
    $config = $cloudinary->getConfiguration();
    echo "Cloud Name: " . $config->cloud['cloud_name'] . "\n";
    echo "API Key: " . $config->cloud['api_key'] . "\n";
    echo "API Secret: " . (isset($config->cloud['api_secret']) ? str_repeat('*', strlen($config->cloud['api_secret'])) : '未設定') . "\n";
    
} catch (Exception $e) {
    echo "Cloudinary 套件測試失敗: " . $e->getMessage() . "\n";
}

echo "\n=== 測試完成 ===\n";
