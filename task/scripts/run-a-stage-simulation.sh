#!/bin/bash

# 🏗️ A階段模擬執行腳本
# 適用於 Windows (Git Bash) 和 Linux/macOS

echo "🚀 開始執行一佳香電商網站 A階段模擬..."
echo "=================================="

# 顏色定義
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# 函數：顯示進度
show_progress() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

# 函數：顯示成功
show_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

# 函數：顯示警告
show_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# 函數：顯示錯誤
show_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# 模擬 Day 1 上午：伺服器環境配置
simulate_day1_morning() {
    echo ""
    echo "📅 Day 1 上午：伺服器環境配置"
    echo "------------------------------"
    
    show_progress "選擇雲端服務：AWS EC2 (t3.medium)"
    show_progress "作業系統：Ubuntu 22.04 LTS"
    show_progress "記憶體：4GB RAM"
    show_progress "儲存空間：50GB SSD"
    
    echo ""
    show_progress "執行基礎軟體安裝..."
    echo "sudo apt update && sudo apt upgrade -y"
    echo "sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql"
    echo "sudo apt install -y git curl wget unzip"
    echo "sudo apt install -y nodejs npm"
    
    echo ""
    show_progress "設定防火牆..."
    echo "sudo ufw allow 22    # SSH"
    echo "sudo ufw allow 80    # HTTP"
    echo "sudo ufw allow 443   # HTTPS"
    echo "sudo ufw enable"
    
    show_success "伺服器環境配置完成"
}

# 模擬 Day 1 下午：資料庫建置
simulate_day1_afternoon() {
    echo ""
    echo "📅 Day 1 下午：資料庫建置"
    echo "------------------------"
    
    show_progress "設定 MySQL 資料庫..."
    echo "sudo mysql_secure_installation"
    
    echo ""
    show_progress "建立專案資料庫..."
    cat << 'EOF'
CREATE DATABASE yijiaxiang_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'yijiaxiang_user'@'localhost' IDENTIFIED BY 'secure_password_2025';
GRANT ALL PRIVILEGES ON yijiaxiang_db.* TO 'yijiaxiang_user'@'localhost';
FLUSH PRIVILEGES;
EOF

    echo ""
    show_progress "建立基礎資料表結構..."
    cat << 'EOF'
-- 會員資料表
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 商品分類表
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    parent_id INT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 商品資料表
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) NULL,
    category_id INT,
    stock_quantity INT DEFAULT 0,
    weight DECIMAL(8,2) DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
EOF

    show_success "資料庫建置完成"
}

# 模擬 Day 2 上午：版本控制系統設定
simulate_day2_morning() {
    echo ""
    echo "📅 Day 2 上午：版本控制系統設定"
    echo "------------------------------"
    
    show_progress "初始化 Git 倉庫..."
    echo "git init"
    echo "git config user.name \"一佳香開發團隊\""
    echo "git config user.email \"dev@yijiaxiang.com\""
    
    echo ""
    show_progress "建立 .gitignore 檔案..."
    cat << 'EOF'
# 環境設定檔
.env
.env.local
.env.production

# 依賴套件
node_modules/
vendor/
composer.lock
package-lock.json

# 快取檔案
.cache/
.tmp/
*.log

# 上傳檔案
uploads/
public/uploads/

# IDE 檔案
.vscode/
.idea/
*.swp
*.swo

# 系統檔案
.DS_Store
Thumbs.db
EOF

    echo ""
    show_progress "建立初始提交..."
    echo "git add ."
    echo "git commit -m \"初始化專案結構\""
    
    show_success "版本控制系統設定完成"
}

# 模擬 Day 2 下午：開發工具安裝
simulate_day2_afternoon() {
    echo ""
    echo "📅 Day 2 下午：開發工具安裝"
    echo "--------------------------"
    
    show_progress "安裝前端開發工具..."
    echo "npm install -g @vue/cli"
    echo "npm install -g create-react-app"
    echo "npm install -g yarn"
    
    echo ""
    show_progress "安裝後端開發工具..."
    echo "composer global require laravel/installer"
    echo "composer global require phpunit/phpunit"
    
    echo ""
    show_progress "安裝資料庫管理工具..."
    echo "sudo apt install -y phpmyadmin"
    echo "sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin"
    
    echo ""
    show_progress "安裝程式碼品質工具..."
    echo "npm install -g eslint prettier"
    echo "composer global require squizlabs/php_codesniffer"
    
    show_success "開發工具安裝完成"
}

# 模擬 Day 3 上午：前端框架選擇與設定
simulate_day3_morning() {
    echo ""
    echo "📅 Day 3 上午：前端框架選擇與設定"
    echo "--------------------------------"
    
    show_progress "選擇 Vue.js 3 + Vite 作為前端框架..."
    echo "npm create vue@latest yijiaxiang-frontend"
    echo "cd yijiaxiang-frontend"
    
    echo ""
    show_progress "技術棧選擇："
    echo "✅ Vue 3 (Composition API)"
    echo "✅ TypeScript"
    echo "✅ Vite (建置工具)"
    echo "✅ Vue Router (路由)"
    echo "✅ Pinia (狀態管理)"
    echo "✅ Tailwind CSS (樣式框架)"
    echo "✅ Axios (HTTP 客戶端)"
    
    echo ""
    show_progress "安裝依賴..."
    echo "npm install"
    echo "npm install axios pinia @vueuse/core"
    echo "npm install -D tailwindcss postcss autoprefixer"
    echo "npm install -D @types/node"
    
    echo ""
    show_progress "初始化 Tailwind CSS..."
    echo "npx tailwindcss init -p"
    
    show_success "前端框架設定完成"
}

# 模擬 Day 3 下午：後端框架選擇與設定
simulate_day3_afternoon() {
    echo ""
    echo "📅 Day 3 下午：後端框架選擇與設定"
    echo "--------------------------------"
    
    show_progress "選擇 Laravel 10 作為後端框架..."
    echo "composer create-project laravel/laravel yijiaxiang-backend"
    echo "cd yijiaxiang-backend"
    
    echo ""
    show_progress "安裝必要的套件..."
    echo "composer require laravel/sanctum"
    echo "composer require spatie/laravel-permission"
    echo "composer require intervention/image"
    echo "composer require barryvdh/laravel-cors"
    
    echo ""
    show_progress "設定環境變數..."
    echo "cp .env.example .env"
    echo "php artisan key:generate"
    
    echo ""
    show_progress "資料庫連線設定..."
    cat << 'EOF'
# 編輯 .env 檔案
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yijiaxiang_db
DB_USERNAME=yijiaxiang_user
DB_PASSWORD=secure_password_2025
EOF

    show_success "後端框架設定完成"
}

# 模擬 Day 4 上午：API架構設計
simulate_day4_morning() {
    echo ""
    echo "📅 Day 4 上午：API架構設計"
    echo "------------------------"
    
    show_progress "建立 API 路由結構..."
    cat << 'EOF'
// routes/api.php

use Illuminate\Support\Facades\Route;

// 公開 API
Route::prefix('v1')->group(function () {
    // 商品相關
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    
    // 會員相關
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/line-login', [AuthController::class, 'lineLogin']);
    
    // 購物車相關
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update', [CartController::class, 'update']);
    Route::delete('/cart/remove', [CartController::class, 'remove']);
});

// 需要認證的 API
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // 會員專區
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::get('/user/orders', [OrderController::class, 'userOrders']);
    
    // 訂單相關
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    
    // 點數系統
    Route::get('/user/points', [PointController::class, 'index']);
    Route::post('/user/points/earn', [PointController::class, 'earn']);
});

// 管理員 API
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('v1/admin')->group(function () {
    // 商品管理
    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('categories', AdminCategoryController::class);
    
    // 訂單管理
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);
    
    // 會員管理
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::put('/users/{user}/points', [AdminUserController::class, 'updatePoints']);
});
EOF

    show_success "API架構設計完成"
}

# 模擬 Day 4 下午：資料庫結構設計
simulate_day4_afternoon() {
    echo ""
    echo "📅 Day 4 下午：資料庫結構設計"
    echo "----------------------------"
    
    show_progress "建立完整的資料庫結構..."
    cat << 'EOF'
-- 商品圖片表
CREATE TABLE product_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(200),
    sort_order INT DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 訂單表
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_fee DECIMAL(8,2) DEFAULT 0,
    discount_amount DECIMAL(8,2) DEFAULT 0,
    final_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('credit_card', 'line_pay', 'bank_transfer', 'cod') NOT NULL,
    shipping_method ENUM('home_delivery', 'store_pickup', 'convenience_store') NOT NULL,
    recipient_name VARCHAR(100) NOT NULL,
    recipient_phone VARCHAR(20) NOT NULL,
    recipient_address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 訂單明細表
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- 購物車表
CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    session_id VARCHAR(100),
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 優惠券表
CREATE TABLE coupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    minimum_amount DECIMAL(10,2) DEFAULT 0,
    usage_limit INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    valid_from TIMESTAMP NULL,
    valid_until TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 點數記錄表
CREATE TABLE point_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    points INT NOT NULL,
    type ENUM('earn', 'spend', 'expire') NOT NULL,
    description VARCHAR(200),
    order_id INT NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- LINE 用戶關聯表
CREATE TABLE line_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    line_user_id VARCHAR(100) UNIQUE NOT NULL,
    line_display_name VARCHAR(100),
    line_picture_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
EOF

    show_success "資料庫結構設計完成"
}

# 模擬 Day 5：第三方服務申請
simulate_day5() {
    echo ""
    echo "📅 Day 5：第三方服務申請"
    echo "----------------------"
    
    echo ""
    show_progress "Day 5 上午：金流服務申請"
    echo "1. 綠界科技 (ECPay) 申請"
    echo "   - 申請網址：https://www.ecpay.com.tw/"
    echo "   - 所需文件：公司登記證明、負責人身分證、銀行帳戶資料"
    echo ""
    echo "2. 藍新金流 (NewebPay) 申請"
    echo "   - 申請網址：https://www.newebpay.com/"
    echo "   - 所需文件：公司登記證明、負責人身分證、銀行帳戶資料"
    echo ""
    echo "3. LINE Pay 申請"
    echo "   - 申請網址：https://pay.line.me/"
    echo "   - 所需文件：公司登記證明、網站架設證明、商品目錄"
    
    echo ""
    show_progress "Day 5 下午：物流服務申請"
    echo "1. 黑貓宅急便 API 申請"
    echo "   - 申請網址：https://www.t-cat.com.tw/"
    echo "   - 所需文件：公司登記證明、負責人身分證、銀行帳戶資料"
    echo ""
    echo "2. 宅配通 API 申請"
    echo "   - 申請網址：https://www.e-can.com.tw/"
    echo "   - 所需文件：公司登記證明、負責人身分證"
    echo ""
    echo "3. 超商取貨 API 申請"
    echo "   - 7-ELEVEN 取貨：https://www.7-11.com.tw/"
    echo "   - 全家取貨：https://www.family.com.tw/"
    
    echo ""
    show_progress "Day 5 晚上：LINE官方帳號申請"
    echo "LINE 官方帳號申請"
    echo "- 申請網址：https://developers.line.biz/"
    echo "- 所需文件：公司登記證明、網站架設證明、隱私權政策"
    echo ""
    echo "申請步驟："
    echo "1. 註冊 LINE Developers 帳號"
    echo "2. 建立 Provider"
    echo "3. 建立 Channel (Messaging API)"
    echo "4. 設定 Webhook URL"
    echo "5. 取得 Channel Access Token"
    
    show_success "第三方服務申請流程說明完成"
}

# 顯示技術棧選擇
show_tech_stack() {
    echo ""
    echo "🔧 技術棧選擇"
    echo "============"
    
    echo ""
    echo "前端技術棧："
    echo "| 技術 | 版本 | 用途 | 選擇理由 |"
    echo "|------|------|------|----------|"
    echo "| Vue.js | 3.x | 前端框架 | 學習曲線平緩，生態系統豐富 |"
    echo "| TypeScript | 5.x | 型別安全 | 提升程式碼品質，減少錯誤 |"
    echo "| Vite | 5.x | 建置工具 | 開發速度快，熱重載效率高 |"
    echo "| Tailwind CSS | 3.x | 樣式框架 | 快速開發，響應式設計 |"
    echo "| Pinia | 2.x | 狀態管理 | Vue 3 官方推薦，TypeScript 支援好 |"
    echo "| Axios | 1.x | HTTP 客戶端 | 功能完整，攔截器支援 |"
    
    echo ""
    echo "後端技術棧："
    echo "| 技術 | 版本 | 用途 | 選擇理由 |"
    echo "|------|------|------|----------|"
    echo "| Laravel | 10.x | 後端框架 | 開發效率高，安全性強 |"
    echo "| MySQL | 8.0 | 資料庫 | 穩定可靠，社群支援好 |"
    echo "| PHP | 8.1 | 程式語言 | Laravel 生態系統完整 |"
    echo "| Redis | 7.x | 快取系統 | 提升效能，支援 Session |"
    echo "| Nginx | 1.24 | Web 伺服器 | 效能優異，配置靈活 |"
    
    echo ""
    echo "第三方服務："
    echo "| 服務 | 用途 | 選擇理由 |"
    echo "|------|------|----------|"
    echo "| 綠界科技 | 金流服務 | 台灣在地服務，支援多種付款方式 |"
    echo "| 黑貓宅急便 | 物流服務 | 配送範圍廣，服務品質穩定 |"
    echo "| LINE Messaging API | 社群整合 | 台灣用戶使用率高，功能完整 |"
    echo "| AWS S3 | 檔案儲存 | 可靠穩定，成本效益高 |"
}

# 顯示完成檢查清單
show_completion_checklist() {
    echo ""
    echo "✅ 完成檢查清單"
    echo "=============="
    
    echo ""
    echo "基礎環境："
    echo "☐ 伺服器環境配置完成"
    echo "☐ 資料庫建置完成"
    echo "☐ 版本控制系統設定完成"
    echo "☐ 開發工具安裝完成"
    
    echo ""
    echo "專案架構："
    echo "☐ 前端框架選擇與設定完成"
    echo "☐ 後端框架選擇與設定完成"
    echo "☐ API架構設計完成"
    echo "☐ 資料庫結構設計完成"
    
    echo ""
    echo "第三方服務："
    echo "☐ 金流服務申請完成"
    echo "☐ 物流服務申請完成"
    echo "☐ LINE官方帳號申請完成"
    echo "☐ 社群平台API申請完成"
    
    echo ""
    echo "開發準備："
    echo "☐ 開發環境可正常運行"
    echo "☐ 前後端可正常連線"
    echo "☐ 資料庫連線正常"
    echo "☐ 版本控制正常運作"
}

# 顯示階段成果
show_stage_results() {
    echo ""
    echo "📊 階段成果"
    echo "=========="
    
    echo ""
    echo "交付物："
    echo "1. 完整的開發環境 - 可立即開始功能開發"
    echo "2. 專案架構文件 - 包含技術選擇和架構設計"
    echo "3. 資料庫設計文件 - 包含完整的資料表結構"
    echo "4. API 規格文件 - 包含所有 API 端點定義"
    echo "5. 第三方服務申請狀態 - 包含申請進度和帳號資訊"
    
    echo ""
    echo "成功指標："
    echo "☐ 開發環境可在 30 分鐘內完成建置"
    echo "☐ 前後端可正常連線並進行 API 測試"
    echo "☐ 資料庫結構符合業務需求"
    echo "☐ 第三方服務申請進度達 80% 以上"
}

# 顯示完成訊息
show_completion_message() {
    echo ""
    echo "🎉 A階段模擬執行完成！"
    echo "====================="
    echo ""
    echo "📋 模擬內容："
    echo "   ✅ Day 1: 基礎環境建置"
    echo "   ✅ Day 2: 版本控制與開發工具"
    echo "   ✅ Day 3: 前後端框架設定"
    echo "   ✅ Day 4: API架構與資料庫設計"
    echo "   ✅ Day 5: 第三方服務申請"
    echo ""
    echo "📁 專案結構："
    echo "   yijiaxiang/"
    echo "   ├── frontend/     # Vue.js 前端專案"
    echo "   ├── backend/      # Laravel 後端專案"
    echo "   ├── docs/         # 專案文件"
    echo "   └── scripts/      # 部署腳本"
    echo ""
    echo "🔗 開發網址："
    echo "   - 前端：http://localhost:3000"
    echo "   - 後端：http://localhost:8000"
    echo "   - API：http://localhost:8000/api/v1"
    echo ""
    echo "📚 相關文件："
    echo "   - 詳細實施計劃：task/A階段-開發環境建置模擬實施計劃.md"
    echo "   - 完整建置腳本：task/scripts/setup-development-environment.sh"
    echo ""
    echo "✅ A階段模擬完成，可以進入B階段：後台系統開發"
}

# 主執行流程
main() {
    simulate_day1_morning
    simulate_day1_afternoon
    simulate_day2_morning
    simulate_day2_afternoon
    simulate_day3_morning
    simulate_day3_afternoon
    simulate_day4_morning
    simulate_day4_afternoon
    simulate_day5
    show_tech_stack
    show_completion_checklist
    show_stage_results
    show_completion_message
}

# 執行主流程
main "$@" 