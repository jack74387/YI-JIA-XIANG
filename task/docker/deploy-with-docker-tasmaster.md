# 專案 Docker 部署任務清單（tasmaster 格式）

## 目標
將本專案（Laravel + Vue 前後端）以 Docker 容器化部署，實現一鍵啟動開發或測試環境。

---

## 任務分解

### 1. 建立後端 Dockerfile
- 於 `backend/` 目錄下建立 `Dockerfile`
- 內容需：
  - 以官方 PHP + Composer 映像為基礎
  - 安裝必要 PHP 擴充（pdo、mbstring、gd 等）
  - 複製專案檔案、安裝 composer 套件
  - 設定啟動指令（如 php-fpm 或 artisan serve）

### 2. 建立前端 Dockerfile
- 於 `frontend/` 目錄下建立 `Dockerfile`
- 內容需：
  - 以 node:lts 映像為基礎
  - 複製前端專案檔案
  - 安裝 npm 套件並 build
  - 設定啟動指令（如 serve -s dist 或 nginx）

### 3. 建立 docker-compose.yml
- 於專案根目錄建立 `docker-compose.yml`
- 內容需：
  - 定義 backend、frontend、db（如 mysql）三個服務
  - 設定網路、資料卷、port 映射
  - 連接環境變數與資料庫

### 4. 設定 .env 檔案
- 複製 `.env.example` 為 `.env`（backend、frontend 各自）
- 調整資料庫、redis、mail 等連線資訊，對應 docker-compose 設定

### 5. 構建與啟動容器
- 執行 `docker-compose build` 建立映像
- 執行 `docker-compose up -d` 啟動所有服務

### 6. 初始化資料庫
- 進入 backend 容器，執行 migration/seeder：
  - `docker-compose exec backend bash`
  - `php artisan migrate --seed`

### 7. 測試網站可用性
- 確認前端、後端、資料庫皆可正常運作
- 於瀏覽器開啟對應 port 測試

---

## 附註
- 若需自訂 port、資料卷路徑，請於 docker-compose.yml 及 .env 中調整
- 若遇權限問題，請確認資料夾權限與 UID/GID 設定
- 可依需求加入 Redis、MailHog 等服務

---

## 進階
- 可撰寫 Makefile 或 shell script 一鍵執行上述流程
- 可於 CI/CD 流程中自動化上述步驟 