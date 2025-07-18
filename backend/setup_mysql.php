<?php
echo "開始設置MySQL資料庫...\n";
$host = "localhost";
$port = 3306;
$database = "yi_jia_xiang";
$username = "yi_jia_xiang_user";
$password = "yi_jia_xiang_password_2024";

try {
    $pdo = new PDO("mysql:host=$host;port=$port", "root", "");
    echo "成功連接到MySQL（無密碼）\n";
} catch (PDOException $e) {
    echo "無法連接到MySQL: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "資料庫創建成功\n";
} catch (PDOException $e) {
    echo "創建資料庫失敗: " . $e->getMessage() . "\n";
}

try {
    $pdo->exec("CREATE USER IF NOT EXISTS \"$username\"@\"localhost\" IDENTIFIED BY \"$password\"");
    echo "用戶創建成功\n";
} catch (PDOException $e) {
    echo "創建用戶失敗: " . $e->getMessage() . "\n";
}

try {
    $pdo->exec("GRANT ALL PRIVILEGES ON `$database`.* TO \"$username\"@\"localhost\"");
    $pdo->exec("FLUSH PRIVILEGES");
    echo "權限授予成功\n";
} catch (PDOException $e) {
    echo "授予權限失敗: " . $e->getMessage() . "\n";
}

echo "設置完成！\n";
?>
