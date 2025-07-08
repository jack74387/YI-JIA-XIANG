# 🏗️ A階段：開發環境建置模擬實施計劃

## 📋 階段概覽
**時程：** 第1週（5個工作日）  
**負責人：** 後端開發工程師  
**狀態：** 🔴 未開始 → 🟡 進行中 → 🟢 完成

---

## 🎯 階段目標
建立完整的開發環境，為後續功能開發奠定堅實基礎

---

## 📅 詳細時程安排

### Day 1-2：基礎環境建置

#### A1.1 開發環境建置

**Day 1 上午：伺服器環境配置**
```bash
# 1. 開發伺服器設定
- 選擇雲端服務：AWS EC2 (t3.medium) 或 GCP Compute Engine
- 作業系統：Ubuntu 22.04 LTS
- 記憶體：4GB RAM
- 儲存空間：50GB SSD

# 2. 基礎軟體安裝
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql
sudo apt install -y git curl wget unzip
sudo apt install -y nodejs npm

# 3. 防火牆設定
sudo ufw allow 22    # SSH
sudo ufw allow 80    # HTTP
sudo ufw allow 443   # HTTPS
sudo ufw enable
```

**Day 1 下午：資料庫建置**
```sql
-- 1. MySQL 資料庫設定
sudo mysql_secure_installation

-- 2. 建立專案資料庫
CREATE DATABASE yijiaxiang_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'yijiaxiang_user'@'localhost' IDENTIFIED BY 'secure_password_2025';
GRANT ALL PRIVILEGES ON yijiaxiang_db.* TO 'yijiaxiang_user'@'localhost';
FLUSH PRIVILEGES;

-- 3. 建立基礎資料表結構
USE yijiaxiang_db;

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
```

**Day 2 上午：版本控制系統設定**
```bash
# 1. Git 倉庫初始化
git init
git config user.name "一佳香開發團隊"
git config user.email "dev@yijiaxiang.com"

# 2. 建立 .gitignore 檔案
cat > .gitignore << EOF
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

# 3. 建立初始提交
git add .
git commit -m "初始化專案結構"
```

**Day 2 下午：開發工具安裝**
```bash
# 1. 前端開發工具
npm install -g @vue/cli
npm install -g create-react-app
npm install -g yarn

# 2. 後端開發工具
composer global require laravel/installer
composer global require phpunit/phpunit

# 3. 資料庫管理工具
sudo apt install -y phpmyadmin
sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin

# 4. 程式碼品質工具
npm install -g eslint prettier
composer global require squizlabs/php_codesniffer
```

---

### Day 3-4：專案架構建立

#### A1.2 專案架構建立

**Day 3 上午：前端框架選擇與設定**
```bash
# 選擇 Vue.js 3 + Vite 作為前端框架
npm create vue@latest yijiaxiang-frontend
cd yijiaxiang-frontend

# 選擇的技術棧：
# ✅ Vue 3 (Composition API)
# ✅ TypeScript
# ✅ Vite (建置工具)
# ✅ Vue Router (路由)
# ✅ Pinia (狀態管理)
# ✅ Tailwind CSS (樣式框架)
# ✅ Axios (HTTP 客戶端)

# 安裝依賴
npm install
npm install axios pinia @vueuse/core
npm install -D tailwindcss postcss autoprefixer
npm install -D @types/node

# 初始化 Tailwind CSS
npx tailwindcss init -p
```

**Day 3 下午：後端框架選擇與設定**
```bash
# 選擇 Laravel 10 作為後端框架
composer create-project laravel/laravel yijiaxiang-backend
cd yijiaxiang-backend

# 安裝必要的套件
composer require laravel/sanctum
composer require spatie/laravel-permission
composer require intervention/image
composer require barryvdh/laravel-cors

# 設定環境變數
cp .env.example .env
php artisan key:generate

# 資料庫連線設定
# 編輯 .env 檔案
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yijiaxiang_db
DB_USERNAME=yijiaxiang_user
DB_PASSWORD=secure_password_2025
```

**Day 4 上午：API架構設計**
```php
// 建立 API 路由結構
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
```

**Day 4 下午：資料庫結構設計**
```sql
-- 完整的資料庫結構設計

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
```

---

### Day 5：第三方服務申請與整合準備

#### A1.3 第三方服務申請

**Day 5 上午：金流服務申請**
```bash
# 1. 綠界科技 (ECPay) 申請
# 申請網址：https://www.ecpay.com.tw/
# 所需文件：
# - 公司登記證明
# - 負責人身分證正反面
# - 銀行帳戶資料
# - 網站架設證明

# 2. 藍新金流 (NewebPay) 申請
# 申請網址：https://www.newebpay.com/
# 所需文件：
# - 公司登記證明
# - 負責人身分證正反面
# - 銀行帳戶資料

# 3. LINE Pay 申請
# 申請網址：https://pay.line.me/
# 所需文件：
# - 公司登記證明
# - 網站架設證明
# - 商品目錄
```

**Day 5 下午：物流服務申請**
```bash
# 1. 黑貓宅急便 API 申請
# 申請網址：https://www.t-cat.com.tw/
# 所需文件：
# - 公司登記證明
# - 負責人身分證正反面
# - 銀行帳戶資料

# 2. 宅配通 API 申請
# 申請網址：https://www.e-can.com.tw/
# 所需文件：
# - 公司登記證明
# - 負責人身分證正反面

# 3. 超商取貨 API 申請
# - 7-ELEVEN 取貨：https://www.7-11.com.tw/
# - 全家取貨：https://www.family.com.tw/
```

**Day 5 晚上：LINE官方帳號申請**
```bash
# LINE 官方帳號申請
# 申請網址：https://developers.line.biz/
# 所需文件：
# - 公司登記證明
# - 網站架設證明
# - 隱私權政策

# 申請步驟：
# 1. 註冊 LINE Developers 帳號
# 2. 建立 Provider
# 3. 建立 Channel (Messaging API)
# 4. 設定 Webhook URL
# 5. 取得 Channel Access Token
```

---

## 🔧 技術棧選擇

### 前端技術棧
| 技術 | 版本 | 用途 | 選擇理由 |
|------|------|------|----------|
| **Vue.js** | 3.x | 前端框架 | 學習曲線平緩，生態系統豐富 |
| **TypeScript** | 5.x | 型別安全 | 提升程式碼品質，減少錯誤 |
| **Vite** | 5.x | 建置工具 | 開發速度快，熱重載效率高 |
| **Tailwind CSS** | 3.x | 樣式框架 | 快速開發，響應式設計 |
| **Pinia** | 2.x | 狀態管理 | Vue 3 官方推薦，TypeScript 支援好 |
| **Axios** | 1.x | HTTP 客戶端 | 功能完整，攔截器支援 |

### 後端技術棧
| 技術 | 版本 | 用途 | 選擇理由 |
|------|------|------|----------|
| **Laravel** | 10.x | 後端框架 | 開發效率高，安全性強 |
| **MySQL** | 8.0 | 資料庫 | 穩定可靠，社群支援好 |
| **PHP** | 8.1 | 程式語言 | Laravel 生態系統完整 |
| **Redis** | 7.x | 快取系統 | 提升效能，支援 Session |
| **Nginx** | 1.24 | Web 伺服器 | 效能優異，配置靈活 |

### 第三方服務
| 服務 | 用途 | 選擇理由 |
|------|------|----------|
| **綠界科技** | 金流服務 | 台灣在地服務，支援多種付款方式 |
| **黑貓宅急便** | 物流服務 | 配送範圍廣，服務品質穩定 |
| **LINE Messaging API** | 社群整合 | 台灣用戶使用率高，功能完整 |
| **AWS S3** | 檔案儲存 | 可靠穩定，成本效益高 |

---

## 📁 專案結構

```
yijiaxiang/
├── frontend/                 # Vue.js 前端專案
│   ├── src/
│   │   ├── components/      # 可重用元件
│   │   ├── views/          # 頁面元件
│   │   ├── stores/         # Pinia 狀態管理
│   │   ├── router/         # Vue Router 路由
│   │   ├── api/            # API 呼叫
│   │   └── utils/          # 工具函數
│   ├── public/             # 靜態資源
│   └── package.json
├── backend/                 # Laravel 後端專案
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/ # 控制器
│   │   │   └── Middleware/  # 中介層
│   │   ├── Models/         # Eloquent 模型
│   │   └── Services/       # 業務邏輯服務
│   ├── database/
│   │   ├── migrations/     # 資料庫遷移
│   │   └── seeders/        # 資料填充
│   ├── routes/             # 路由定義
│   └── composer.json
├── docs/                   # 專案文件
├── scripts/                # 部署腳本
└── README.md
```

---

## ✅ 完成檢查清單

### 基礎環境
- [ ] 伺服器環境配置完成
- [ ] 資料庫建置完成
- [ ] 版本控制系統設定完成
- [ ] 開發工具安裝完成

### 專案架構
- [ ] 前端框架選擇與設定完成
- [ ] 後端框架選擇與設定完成
- [ ] API架構設計完成
- [ ] 資料庫結構設計完成

### 第三方服務
- [ ] 金流服務申請完成
- [ ] 物流服務申請完成
- [ ] LINE官方帳號申請完成
- [ ] 社群平台API申請完成

### 開發準備
- [ ] 開發環境可正常運行
- [ ] 前後端可正常連線
- [ ] 資料庫連線正常
- [ ] 版本控制正常運作

---

## ⚠️ 注意事項與風險

### 高風險項目
1. **第三方服務申請延遲** - 可能影響後續開發時程
2. **伺服器配置問題** - 可能影響開發效率
3. **資料庫設計缺陷** - 可能影響後續功能開發

### 應對策略
1. **提前申請第三方服務** - 準備完整文件，並行處理
2. **建立備用環境** - 準備本地開發環境作為備案
3. **資料庫設計審查** - 邀請資深工程師進行設計審查

### 品質保證
1. **程式碼審查** - 每個功能完成後進行程式碼審查
2. **測試驅動開發** - 建立單元測試確保程式碼品質
3. **文件記錄** - 詳細記錄所有配置和設定

---

## 📊 階段成果

### 交付物
1. **完整的開發環境** - 可立即開始功能開發
2. **專案架構文件** - 包含技術選擇和架構設計
3. **資料庫設計文件** - 包含完整的資料表結構
4. **API 規格文件** - 包含所有 API 端點定義
5. **第三方服務申請狀態** - 包含申請進度和帳號資訊

### 成功指標
- [ ] 開發環境可在 30 分鐘內完成建置
- [ ] 前後端可正常連線並進行 API 測試
- [ ] 資料庫結構符合業務需求
- [ ] 第三方服務申請進度達 80% 以上

---

*最後更新：2025年1月*  
*階段負責人：後端開發工程師* 