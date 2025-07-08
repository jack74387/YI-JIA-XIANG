# 一佳香官方網站

專業的香品製造商官方網站，提供線上購物、會員管理、訂單處理等功能。

## 🏗️ 專案架構

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

## 🚀 快速開始

### 前置需求

- **Node.js** (v18+) - [下載](https://nodejs.org/)
- **PHP** (v8.1+) - [下載](https://windows.php.net/download/)
- **Composer** - [下載](https://getcomposer.org/download/)
- **MySQL** (v8.0+) - [下載](https://dev.mysql.com/downloads/mysql/)

### Windows 環境建置

1. **克隆專案**
   ```bash
   git clone <repository-url>
   cd yijiaxiang
   ```

2. **執行自動建置腳本**
   ```powershell
   .\scripts\setup-windows.ps1
   ```

3. **啟動開發環境**
   ```powershell
   .\scripts\start-dev.ps1
   ```

### 手動建置

#### 前端設定
```bash
cd frontend
npm install
npm run dev
```

#### 後端設定
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## 📱 功能特色

### 前端功能
- ✅ 響應式設計，支援手機、平板、電腦
- ✅ 商品展示與搜尋
- ✅ 購物車功能
- ✅ 會員註冊/登入
- ✅ 訂單管理
- ✅ 點數系統
- ✅ LINE 登入整合

### 後端功能
- ✅ RESTful API
- ✅ 會員認證與授權
- ✅ 商品管理
- ✅ 訂單處理
- ✅ 金流整合
- ✅ 物流整合
- ✅ 點數系統
- ✅ 管理後台

## 🛠️ 技術棧

### 前端
- **Vue.js 3** - 前端框架
- **TypeScript** - 型別安全
- **Vite** - 建置工具
- **Tailwind CSS** - 樣式框架
- **Pinia** - 狀態管理
- **Vue Router** - 路由管理
- **Axios** - HTTP 客戶端

### 後端
- **Laravel 10** - PHP 框架
- **MySQL** - 資料庫
- **Laravel Sanctum** - API 認證
- **Spatie Permission** - 權限管理
- **Intervention Image** - 圖片處理

### 第三方服務
- **綠界科技** - 金流服務
- **黑貓宅急便** - 物流服務
- **LINE Messaging API** - 社群整合

## 📊 API 文件

啟動後端伺服器後，可透過以下網址查看 API 文件：
- Swagger UI: `http://localhost:8000/api/documentation`
- Postman Collection: `docs/api/postman-collection.json`

## 🔧 開發指南

### 程式碼規範
- 使用 ESLint + Prettier 進行程式碼格式化
- 遵循 PSR-12 PHP 程式碼規範
- 使用 TypeScript 確保型別安全

### Git 工作流程
1. 從 `main` 分支建立功能分支
2. 開發完成後提交 Pull Request
3. 通過程式碼審查後合併到 `main` 分支

### 測試
```bash
# 前端測試
cd frontend
npm run test

# 後端測試
cd backend
php artisan test
```

## 📦 部署

### 生產環境部署
```bash
# 前端建置
cd frontend
npm run build

# 後端部署
cd backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 貢獻指南

1. Fork 專案
2. 建立功能分支 (`git checkout -b feature/AmazingFeature`)
3. 提交變更 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 開啟 Pull Request

## 📄 授權

本專案採用 MIT 授權條款 - 詳見 [LICENSE](LICENSE) 檔案

## 📞 聯絡資訊

- **公司名稱**: 一佳香
- **聯絡電話**: (02) 1234-5678
- **電子郵件**: service@yijiaxiang.com
- **地址**: 台北市信義區信義路五段7號

---

**一佳香** - 傳承百年工藝，提供優質香品 