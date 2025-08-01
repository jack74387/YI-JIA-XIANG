<?php
// 直接测试环境变量访问
echo "=== 直接环境变量测试 ===\n";
echo "getenv('CLOUDINARY_URL'): " . var_export(getenv('CLOUDINARY_URL'), true) . "\n";
echo "getenv('CLOUDINARY_CLOUD_NAME'): " . var_export(getenv('CLOUDINARY_CLOUD_NAME'), true) . "\n";
echo "getenv('CLOUDINARY_API_KEY'): " . var_export(getenv('CLOUDINARY_API_KEY'), true) . "\n";
echo "getenv('CLOUDINARY_API_SECRET'): " . var_export(getenv('CLOUDINARY_API_SECRET'), true) . "\n";

echo "\n=== \$_ENV 测试 ===\n";
echo "CLOUDINARY_URL: " . var_export($_ENV['CLOUDINARY_URL'] ?? 'not set', true) . "\n";
echo "CLOUDINARY_CLOUD_NAME: " . var_export($_ENV['CLOUDINARY_CLOUD_NAME'] ?? 'not set', true) . "\n";
echo "CLOUDINARY_API_KEY: " . var_export($_ENV['CLOUDINARY_API_KEY'] ?? 'not set', true) . "\n";
echo "CLOUDINARY_API_SECRET: " . var_export($_ENV['CLOUDINARY_API_SECRET'] ?? 'not set', true) . "\n";

echo "\n=== 系统环境变量 ===\n";
$env_output = shell_exec('env | grep CLOUDINARY');
echo "shell_exec env output:\n" . $env_output;

echo "\n=== phpinfo 中的环境变量 ===\n";
// 获取 phpinfo 中的环境部分
ob_start();
phpinfo(INFO_ENVIRONMENT);
$phpinfo = ob_get_clean();
preg_match_all('/CLOUDINARY[^<]*/', $phpinfo, $matches);
foreach ($matches[0] as $match) {
    echo $match . "\n";
}
