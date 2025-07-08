# 一佳香網站開發環境建置腳本 (Windows)
# 執行前請確保已安裝：Node.js, PHP, Composer

Write-Host "🚀 開始建置一佳香網站開發環境..." -ForegroundColor Green

# 檢查必要工具
Write-Host "📋 檢查必要工具..." -ForegroundColor Yellow

# 檢查 Node.js
try {
    $nodeVersion = node --version
    Write-Host "✅ Node.js 版本: $nodeVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ 請先安裝 Node.js: https://nodejs.org/" -ForegroundColor Red
    exit 1
}

# 檢查 PHP
try {
    $phpVersion = php --version | Select-Object -First 1
    Write-Host "✅ PHP 版本: $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ 請先安裝 PHP: https://windows.php.net/download/" -ForegroundColor Red
    exit 1
}

# 檢查 Composer
try {
    $composerVersion = composer --version | Select-Object -First 1
    Write-Host "✅ Composer 版本: $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ 請先安裝 Composer: https://getcomposer.org/download/" -ForegroundColor Red
    exit 1
}

Write-Host "`n📦 安裝前端依賴..." -ForegroundColor Yellow
Set-Location frontend
npm install

Write-Host "`n📦 安裝後端依賴..." -ForegroundColor Yellow
Set-Location ../backend
composer install

Write-Host "`n🔧 設定環境變數..." -ForegroundColor Yellow
if (!(Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✅ 已建立 .env 檔案" -ForegroundColor Green
}

Write-Host "`n🗄️ 設定 SQLite 資料庫..." -ForegroundColor Yellow
if (!(Test-Path "database/database.sqlite")) {
    New-Item -ItemType File -Path "database/database.sqlite" | Out-Null
    Write-Host "✅ 已建立 SQLite 資料庫檔案 database/database.sqlite" -ForegroundColor Green
} else {
    Write-Host "✅ SQLite 資料庫檔案已存在" -ForegroundColor Green
}

Write-Host "`n🔄 執行資料庫遷移..." -ForegroundColor Yellow
php artisan migrate

Write-Host "`n🌱 填充測試資料..." -ForegroundColor Yellow
php artisan db:seed

Write-Host "`n🔑 生成應用程式金鑰..." -ForegroundColor Yellow
php artisan key:generate

Write-Host "`n📁 建立儲存目錄..." -ForegroundColor Yellow
php artisan storage:link

Set-Location ..

Write-Host "`n✅ 環境建置完成！" -ForegroundColor Green
Write-Host "`n🚀 啟動開發伺服器：" -ForegroundColor Yellow
Write-Host "前端: cd frontend && npm run dev" -ForegroundColor Cyan
Write-Host "後端: cd backend && php artisan serve" -ForegroundColor Cyan
Write-Host "`n📱 前端網址: http://localhost:3000" -ForegroundColor Cyan
Write-Host "🔧 後端API: http://localhost:8000" -ForegroundColor Cyan 