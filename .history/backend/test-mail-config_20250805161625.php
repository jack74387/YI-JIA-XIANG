<?php

// 測試郵件配置
// 運行方式：php test-mail-config.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== 郵件配置檢查 ===\n\n";

// 檢查環境變數
echo "MAIL_MAILER: " . env('MAIL_MAILER') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? '***已設定***' : '未設定') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n\n";

// 檢查配置值
$config = config('mail');
echo "=== 實際配置值 ===\n";
echo "Default mailer: " . $config['default'] . "\n";
echo "SMTP host: " . $config['mailers']['smtp']['host'] . "\n";
echo "SMTP port: " . $config['mailers']['smtp']['port'] . "\n";
echo "SMTP username: " . $config['mailers']['smtp']['username'] . "\n";
echo "SMTP password: " . ($config['mailers']['smtp']['password'] ? '***已設定***' : '未設定') . "\n";
echo "From address: " . $config['from']['address'] . "\n";
echo "From name: " . $config['from']['name'] . "\n\n";

// 簡單測試
try {
    echo "=== 測試郵件發送 ===\n";
    
    if (env('MAIL_MAILER') === 'log') {
        echo "⚠️  目前使用 log 模式，郵件不會真實發送\n";
        echo "請更新 .env 中的 MAIL_MAILER=smtp\n";
    } else {
        echo "✅ 使用 SMTP 模式\n";
        
        if (!env('MAIL_PASSWORD') || env('MAIL_PASSWORD') === 'your_app_password_here') {
            echo "❌ 請設定正確的 Gmail 應用程式密碼\n";
            echo "請參考 gmail-setup-guide.md 設定說明\n";
        } else {
            echo "✅ 密碼已設定\n";
            
            // 嘗試發送測試郵件
            try {
                \Illuminate\Support\Facades\Mail::raw('這是一封測試郵件', function ($message) {
                    $message->to('yijiaxiang88@gmail.com')
                           ->subject('測試郵件 - ' . date('Y-m-d H:i:s'));
                });
                echo "✅ 測試郵件發送成功！請檢查 yijiaxiang88@gmail.com 信箱\n";
            } catch (\Exception $e) {
                echo "❌ 測試郵件發送失敗：" . $e->getMessage() . "\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "❌ 配置錯誤：" . $e->getMessage() . "\n";
}

echo "\n=== 下一步 ===\n";
echo "1. 如果使用 log 模式，請更新 .env 設定為 smtp\n";
echo "2. 如果密碼未設定，請參考 gmail-setup-guide.md\n";
echo "3. 設定完成後執行：php artisan config:clear\n";
echo "4. 再次測試聯絡表單功能\n";
