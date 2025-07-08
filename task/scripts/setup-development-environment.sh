#!/bin/bash

# 🏗️ 一佳香電商網站 - 開發環境建置腳本
# 版本：1.0
# 日期：2025年1月

set -e  # 遇到錯誤立即停止

echo "🚀 開始建置一佳香電商網站開發環境..."
echo "=================================="

# 顏色定義
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

# 檢查作業系統
check_os() {
    show_progress "檢查作業系統..."
    
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        if [ -f /etc/os-release ]; then
            . /etc/os-release
            OS=$NAME
            VER=$VERSION_ID
        fi
        show_success "檢測到作業系統: $OS $VER"
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        show_success "檢測到作業系統: macOS"
    else
        show_error "不支援的作業系統: $OSTYPE"
        exit 1
    fi
}

# 安裝基礎套件 (Ubuntu/Debian)
install_base_packages() {
    show_progress "安裝基礎套件..."
    
    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        sudo apt update
        sudo apt install -y curl wget git unzip software-properties-common
        show_success "基礎套件安裝完成"
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        # 檢查是否安裝 Homebrew
        if ! command -v brew &> /dev/null; then
            show_progress "安裝 Homebrew..."
            /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
        fi
        show_success "Homebrew 已準備就緒"
    fi
}

# 安裝 Node.js
install_nodejs() {
    show_progress "安裝 Node.js..."
    
    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        # 安裝 Node.js 18.x
        curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
        sudo apt-get install -y nodejs
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        brew install node@18
    fi
    
    # 驗證安裝
    node_version=$(node --version)
    npm_version=$(npm --version)
    show_success "Node.js $node_version 安裝完成"
    show_success "npm $npm_version 安裝完成"
}

# 安裝 PHP
install_php() {
    show_progress "安裝 PHP 8.1..."
    
    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        # 添加 PHP 8.1 倉庫
        sudo add-apt-repository ppa:ondrej/php -y
        sudo apt update
        sudo apt install -y php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-mbstring php8.1-zip php8.1-gd php8.1-bcmath
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        brew install php@8.1
        brew link php@8.1 --force
    fi
    
    # 驗證安裝
    php_version=$(php --version | head -n 1)
    show_success "PHP 安裝完成: $php_version"
}

# 安裝 Composer
install_composer() {
    show_progress "安裝 Composer..."
    
    if ! command -v composer &> /dev/null; then
        curl -sS https://getcomposer.org/installer | php
        sudo mv composer.phar /usr/local/bin/composer
    fi
    
    composer_version=$(composer --version | head -n 1)
    show_success "Composer 安裝完成: $composer_version"
}

# 安裝 MySQL
install_mysql() {
    show_progress "安裝 MySQL..."
    
    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        sudo apt install -y mysql-server
        sudo systemctl start mysql
        sudo systemctl enable mysql
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        brew install mysql
        brew services start mysql
    fi
    
    show_success "MySQL 安裝完成"
}

# 設定 MySQL
setup_mysql() {
    show_progress "設定 MySQL 資料庫..."
    
    # 建立資料庫和用戶
    mysql -u root -e "
    CREATE DATABASE IF NOT EXISTS yijiaxiang_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER IF NOT EXISTS 'yijiaxiang_user'@'localhost' IDENTIFIED BY 'yijiaxiang_2025!';
    GRANT ALL PRIVILEGES ON yijiaxiang_db.* TO 'yijiaxiang_user'@'localhost';
    FLUSH PRIVILEGES;
    "
    
    show_success "MySQL 資料庫設定完成"
}

# 安裝 Nginx
install_nginx() {
    show_progress "安裝 Nginx..."
    
    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        sudo apt install -y nginx
        sudo systemctl start nginx
        sudo systemctl enable nginx
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        brew install nginx
        brew services start nginx
    fi
    
    show_success "Nginx 安裝完成"
}

# 建立專案目錄
create_project_structure() {
    show_progress "建立專案目錄結構..."
    
    # 建立主目錄
    mkdir -p yijiaxiang/{frontend,backend,docs,scripts}
    
    # 建立前端目錄結構
    mkdir -p yijiaxiang/frontend/{src/{components,views,stores,router,api,utils},public}
    
    # 建立後端目錄結構
    mkdir -p yijiaxiang/backend/{app/{Http/{Controllers,Middleware},Models,Services},database/{migrations,seeders},routes}
    
    show_success "專案目錄結構建立完成"
}

# 初始化 Git 倉庫
init_git_repository() {
    show_progress "初始化 Git 倉庫..."
    
    cd yijiaxiang
    
    # 建立 .gitignore
    cat > .gitignore << 'EOF'
# 環境設定檔
.env
.env.local
.env.production
.env.testing

# 依賴套件
node_modules/
vendor/
composer.lock
package-lock.json
yarn.lock

# 快取檔案
.cache/
.tmp/
*.log
storage/logs/
storage/framework/cache/

# 上傳檔案
uploads/
public/uploads/
storage/app/public/

# IDE 檔案
.vscode/
.idea/
*.swp
*.swo

# 系統檔案
.DS_Store
Thumbs.db

# 建置檔案
dist/
build/
public/build/

# 測試檔案
coverage/
.phpunit.result.cache
EOF

    # 初始化 Git
    git init
    git config user.name "一佳香開發團隊"
    git config user.email "dev@yijiaxiang.com"
    
    # 建立初始提交
    git add .
    git commit -m "初始化專案結構"
    
    show_success "Git 倉庫初始化完成"
}

# 建立前端專案
setup_frontend() {
    show_progress "建立 Vue.js 前端專案..."
    
    cd frontend
    
    # 建立 package.json
    cat > package.json << 'EOF'
{
  "name": "yijiaxiang-frontend",
  "version": "1.0.0",
  "description": "一佳香肉脯行電商網站前端",
  "scripts": {
    "dev": "vite",
    "build": "vue-tsc && vite build",
    "preview": "vite preview",
    "lint": "eslint . --ext .vue,.js,.jsx,.cjs,.mjs,.ts,.tsx,.cts,.mts --fix --ignore-path .gitignore"
  },
  "dependencies": {
    "vue": "^3.3.4",
    "vue-router": "^4.2.4",
    "pinia": "^2.1.6",
    "axios": "^1.4.0",
    "@vueuse/core": "^10.2.1"
  },
  "devDependencies": {
    "@types/node": "^20.4.5",
    "@vitejs/plugin-vue": "^4.2.3",
    "@vue/tsconfig": "^0.4.0",
    "typescript": "~5.1.6",
    "vite": "^4.4.5",
    "vue-tsc": "^1.8.5",
    "tailwindcss": "^3.3.3",
    "postcss": "^8.4.27",
    "autoprefixer": "^10.4.14",
    "@typescript-eslint/eslint-plugin": "^6.2.0",
    "@typescript-eslint/parser": "^6.2.0",
    "eslint": "^8.45.0",
    "eslint-plugin-vue": "^9.15.1"
  }
}
EOF

    # 建立 Vite 配置
    cat > vite.config.ts << 'EOF'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
  server: {
    port: 3000,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true
      }
    }
  }
})
EOF

    # 建立 TypeScript 配置
    cat > tsconfig.json << 'EOF'
{
  "extends": "@vue/tsconfig/tsconfig.dom.json",
  "include": ["env.d.ts", "src/**/*", "src/**/*.vue"],
  "exclude": ["src/**/__tests__/*"],
  "compilerOptions": {
    "composite": true,
    "baseUrl": ".",
    "paths": {
      "@/*": ["./src/*"]
    }
  }
}
EOF

    # 建立 Tailwind CSS 配置
    cat > tailwind.config.js << 'EOF'
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#fef7ee',
          100: '#fdedd6',
          200: '#fad7ac',
          300: '#f6ba77',
          400: '#f1933d',
          500: '#ed7516',
          600: '#de5a0c',
          700: '#b8430c',
          800: '#933512',
          900: '#762e12',
        }
      }
    },
  },
  plugins: [],
}
EOF

    # 建立 PostCSS 配置
    cat > postcss.config.js << 'EOF'
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOF

    # 建立主要 Vue 檔案
    mkdir -p src/{components,views,stores,router,api,utils}
    
    # 建立 main.ts
    cat > src/main.ts << 'EOF'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './style.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
EOF

    # 建立 App.vue
    cat > src/App.vue << 'EOF'
<template>
  <div id="app">
    <nav class="bg-primary-600 text-white p-4">
      <div class="container mx-auto">
        <h1 class="text-2xl font-bold">一佳香肉脯行</h1>
      </div>
    </nav>
    <main class="container mx-auto p-4">
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
// 組件邏輯
</script>
EOF

    # 建立樣式檔案
    cat > src/style.css << 'EOF'
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  html {
    font-family: 'Noto Sans TC', sans-serif;
  }
}

@layer components {
  .btn-primary {
    @apply bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded;
  }
  
  .btn-secondary {
    @apply bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded;
  }
}
EOF

    # 建立路由配置
    cat > src/router/index.ts << 'EOF'
import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/products',
      name: 'products',
      component: () => import('@/views/ProductsView.vue')
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('@/views/CartView.vue')
    }
  ]
})

export default router
EOF

    # 建立首頁元件
    cat > src/views/HomeView.vue << 'EOF'
<template>
  <div class="home">
    <section class="hero bg-gradient-to-r from-primary-500 to-primary-700 text-white py-16">
      <div class="container mx-auto text-center">
        <h1 class="text-4xl font-bold mb-4">台東在地好味道</h1>
        <p class="text-xl mb-8">傳承數十年的黃金嬰兒豬肉鬆，讓您品嚐最純粹的美味</p>
        <router-link to="/products" class="btn-primary text-lg px-8 py-3">
          立即選購
        </router-link>
      </div>
    </section>
    
    <section class="featured-products py-16">
      <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">熱門商品</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="product-card bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-48 bg-gray-200"></div>
            <div class="p-6">
              <h3 class="text-xl font-semibold mb-2">黃金嬰兒豬肉鬆</h3>
              <p class="text-gray-600 mb-4">精選上等豬肉，獨家配方製作</p>
              <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-primary-600">NT$ 280</span>
                <button class="btn-primary">加入購物車</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
// 首頁邏輯
</script>
EOF

    # 建立 index.html
    cat > index.html << 'EOF'
<!DOCTYPE html>
<html lang="zh-TW">
  <head>
    <meta charset="UTF-8">
    <link rel="icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>一佳香肉脯行 - 台東在地好味道</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <div id="app"></div>
    <script type="module" src="/src/main.ts"></script>
  </body>
</html>
EOF

    show_success "Vue.js 前端專案建立完成"
}

# 建立後端專案
setup_backend() {
    show_progress "建立 Laravel 後端專案..."
    
    cd ../backend
    
    # 使用 Composer 建立 Laravel 專案
    composer create-project laravel/laravel . --prefer-dist
    
    # 安裝額外套件
    composer require laravel/sanctum
    composer require spatie/laravel-permission
    composer require intervention/image
    composer require barryvdh/laravel-cors
    
    # 設定環境變數
    cp .env.example .env
    php artisan key:generate
    
    # 更新 .env 檔案
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=yijiaxiang_db/' .env
    sed -i 's/DB_USERNAME=.*/DB_USERNAME=yijiaxiang_user/' .env
    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=yijiaxiang_2025!/' .env
    
    # 執行資料庫遷移
    php artisan migrate
    
    show_success "Laravel 後端專案建立完成"
}

# 建立 API 路由
setup_api_routes() {
    show_progress "設定 API 路由..."
    
    cd backend
    
    # 建立 API 路由檔案
    cat > routes/api.php << 'EOF'
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 公開 API
Route::prefix('v1')->group(function () {
    // 商品相關
    Route::get('/products', function () {
        return response()->json([
            'message' => '商品列表 API',
            'data' => []
        ]);
    });
    
    Route::get('/categories', function () {
        return response()->json([
            'message' => '分類列表 API',
            'data' => []
        ]);
    });
    
    // 會員相關
    Route::post('/auth/register', function () {
        return response()->json([
            'message' => '註冊 API'
        ]);
    });
    
    Route::post('/auth/login', function () {
        return response()->json([
            'message' => '登入 API'
        ]);
    });
    
    // 購物車相關
    Route::get('/cart', function () {
        return response()->json([
            'message' => '購物車 API',
            'data' => []
        ]);
    });
});

// 需要認證的 API
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // 會員專區
    Route::get('/user/profile', function () {
        return response()->json([
            'message' => '會員資料 API'
        ]);
    });
    
    // 訂單相關
    Route::post('/orders', function () {
        return response()->json([
            'message' => '建立訂單 API'
        ]);
    });
});

// 管理員 API
Route::middleware(['auth:sanctum'])->prefix('v1/admin')->group(function () {
    // 商品管理
    Route::get('/products', function () {
        return response()->json([
            'message' => '管理員商品列表 API'
        ]);
    });
    
    // 訂單管理
    Route::get('/orders', function () {
        return response()->json([
            'message' => '管理員訂單列表 API'
        ]);
    });
});
EOF

    show_success "API 路由設定完成"
}

# 建立資料庫遷移檔案
setup_database_migrations() {
    show_progress "建立資料庫遷移檔案..."
    
    cd backend
    
    # 建立商品遷移
    php artisan make:migration create_products_table
    php artisan make:migration create_categories_table
    php artisan make:migration create_orders_table
    php artisan make:migration create_order_items_table
    php artisan make:migration create_cart_items_table
    php artisan make:migration create_coupons_table
    php artisan make:migration create_point_transactions_table
    
    show_success "資料庫遷移檔案建立完成"
}

# 建立專案文件
create_documentation() {
    show_progress "建立專案文件..."
    
    cd ../docs
    
    # 建立 README
    cat > README.md << 'EOF'
# 一佳香肉脯行電商網站

## 專案概述
一佳香肉脯行電商網站建置專案，提供完整的線上購物體驗。

## 技術棧
- 前端：Vue.js 3 + TypeScript + Tailwind CSS
- 後端：Laravel 10 + MySQL
- 其他：Nginx, Redis

## 快速開始

### 前端開發
```bash
cd frontend
npm install
npm run dev
```

### 後端開發
```bash
cd backend
composer install
php artisan serve
```

### 資料庫
```bash
# 執行遷移
php artisan migrate

# 填充測試資料
php artisan db:seed
```

## API 文件
API 端點文件請參考 `docs/api.md`

## 部署
部署相關資訊請參考 `docs/deployment.md`
EOF

    # 建立 API 文件
    cat > api.md << 'EOF'
# API 文件

## 基礎資訊
- 基礎 URL: `http://localhost:8000/api/v1`
- 認證方式: Bearer Token (Laravel Sanctum)

## 端點列表

### 商品相關
- `GET /products` - 取得商品列表
- `GET /products/{id}` - 取得商品詳情

### 會員相關
- `POST /auth/register` - 會員註冊
- `POST /auth/login` - 會員登入

### 購物車相關
- `GET /cart` - 取得購物車內容
- `POST /cart/add` - 加入購物車
- `PUT /cart/update` - 更新購物車
- `DELETE /cart/remove` - 移除購物車商品

### 訂單相關
- `POST /orders` - 建立訂單
- `GET /orders/{id}` - 取得訂單詳情
EOF

    show_success "專案文件建立完成"
}

# 建立部署腳本
create_deployment_scripts() {
    show_progress "建立部署腳本..."
    
    cd ../scripts
    
    # 建立部署腳本
    cat > deploy.sh << 'EOF'
#!/bin/bash

# 一佳香電商網站部署腳本

echo "🚀 開始部署一佳香電商網站..."

# 前端建置
echo "📦 建置前端..."
cd frontend
npm install
npm run build

# 後端部署
echo "🔧 部署後端..."
cd ../backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ 部署完成！"
EOF

    chmod +x deploy.sh
    
    show_success "部署腳本建立完成"
}

# 安裝前端依賴
install_frontend_dependencies() {
    show_progress "安裝前端依賴..."
    
    cd frontend
    npm install
    
    show_success "前端依賴安裝完成"
}

# 安裝後端依賴
install_backend_dependencies() {
    show_progress "安裝後端依賴..."
    
    cd ../backend
    composer install
    
    show_success "後端依賴安裝完成"
}

# 測試環境
test_environment() {
    show_progress "測試開發環境..."
    
    # 測試前端
    cd frontend
    if npm run build > /dev/null 2>&1; then
        show_success "前端建置測試通過"
    else
        show_error "前端建置測試失敗"
    fi
    
    # 測試後端
    cd ../backend
    if php artisan --version > /dev/null 2>&1; then
        show_success "Laravel 測試通過"
    else
        show_error "Laravel 測試失敗"
    fi
    
    # 測試資料庫連線
    if php artisan migrate:status > /dev/null 2>&1; then
        show_success "資料庫連線測試通過"
    else
        show_error "資料庫連線測試失敗"
    fi
}

# 顯示完成訊息
show_completion_message() {
    echo ""
    echo "🎉 一佳香電商網站開發環境建置完成！"
    echo "=================================="
    echo ""
    echo "📁 專案結構："
    echo "   yijiaxiang/"
    echo "   ├── frontend/     # Vue.js 前端專案"
    echo "   ├── backend/      # Laravel 後端專案"
    echo "   ├── docs/         # 專案文件"
    echo "   └── scripts/      # 部署腳本"
    echo ""
    echo "🚀 啟動開發環境："
    echo "   1. 啟動後端：cd backend && php artisan serve"
    echo "   2. 啟動前端：cd frontend && npm run dev"
    echo ""
    echo "📚 文件位置："
    echo "   - API 文件：docs/api.md"
    echo "   - 部署指南：scripts/deploy.sh"
    echo ""
    echo "🔗 開發網址："
    echo "   - 前端：http://localhost:3000"
    echo "   - 後端：http://localhost:8000"
    echo "   - API：http://localhost:8000/api/v1"
    echo ""
    echo "✅ 環境建置完成，可以開始開發了！"
}

# 主執行流程
main() {
    check_os
    install_base_packages
    install_nodejs
    install_php
    install_composer
    install_mysql
    setup_mysql
    install_nginx
    create_project_structure
    init_git_repository
    setup_frontend
    setup_backend
    setup_api_routes
    setup_database_migrations
    create_documentation
    create_deployment_scripts
    install_frontend_dependencies
    install_backend_dependencies
    test_environment
    show_completion_message
}

# 執行主流程
main "$@" 