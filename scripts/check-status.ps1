# 一佳香網站專案狀態檢查腳本

Write-Host "🔍 檢查一佳香網站專案狀態..." -ForegroundColor Green

# 檢查目錄結構
Write-Host "`n📁 檢查目錄結構..." -ForegroundColor Yellow

$requiredDirs = @("frontend", "backend", "docs", "scripts")
foreach ($dir in $requiredDirs) {
    if (Test-Path $dir) {
        Write-Host "✅ $dir 目錄存在" -ForegroundColor Green
    } else {
        Write-Host "❌ $dir 目錄不存在" -ForegroundColor Red
    }
}

# 檢查前端檔案
Write-Host "`n📱 檢查前端檔案..." -ForegroundColor Yellow
$frontendFiles = @(
    "frontend/package.json",
    "frontend/vite.config.ts",
    "frontend/src/main.ts",
    "frontend/src/App.vue",
    "frontend/src/router/index.ts"
)

foreach ($file in $frontendFiles) {
    if (Test-Path $file) {
        Write-Host "✅ $file 存在" -ForegroundColor Green
    } else {
        Write-Host "❌ $file 不存在" -ForegroundColor Red
    }
}

# 檢查後端檔案
Write-Host "`n🔧 檢查後端檔案..." -ForegroundColor Yellow
$backendFiles = @(
    "backend/composer.json",
    "backend/routes/api.php",
    "backend/app/Http/Controllers/Api/ProductController.php",
    "backend/app/Models/Product.php"
)

foreach ($file in $backendFiles) {
    if (Test-Path $file) {
        Write-Host "✅ $file 存在" -ForegroundColor Green
    } else {
        Write-Host "❌ $file 不存在" -ForegroundColor Red
    }
}

# 檢查腳本檔案
Write-Host "`n📜 檢查腳本檔案..." -ForegroundColor Yellow
$scriptFiles = @(
    "scripts/setup-windows.ps1",
    "scripts/start-dev.ps1",
    "scripts/check-status.ps1"
)

foreach ($file in $scriptFiles) {
    if (Test-Path $file) {
        Write-Host "✅ $file 存在" -ForegroundColor Green
    } else {
        Write-Host "❌ $file 不存在" -ForegroundColor Red
    }
}

# 檢查必要工具
Write-Host "`n🛠️ 檢查必要工具..." -ForegroundColor Yellow

try {
    $nodeVersion = node --version
    Write-Host "✅ Node.js: $nodeVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Node.js 未安裝" -ForegroundColor Red
}

try {
    $npmVersion = npm --version
    Write-Host "✅ npm: $npmVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ npm 未安裝" -ForegroundColor Red
}

try {
    $phpVersion = php --version | Select-Object -First 1
    Write-Host "✅ PHP: $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ PHP 未安裝" -ForegroundColor Red
}

try {
    $composerVersion = composer --version | Select-Object -First 1
    Write-Host "✅ Composer: $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Composer 未安裝" -ForegroundColor Red
}

# 檢查依賴安裝狀態
Write-Host "`n📦 檢查依賴安裝狀態..." -ForegroundColor Yellow

if (Test-Path "frontend/node_modules") {
    Write-Host "✅ 前端依賴已安裝" -ForegroundColor Green
} else {
    Write-Host "❌ 前端依賴未安裝 (執行: cd frontend && npm install)" -ForegroundColor Red
}

if (Test-Path "backend/vendor") {
    Write-Host "✅ 後端依賴已安裝" -ForegroundColor Green
} else {
    Write-Host "❌ 後端依賴未安裝 (執行: cd backend && composer install)" -ForegroundColor Red
}

# 檢查環境檔案
Write-Host "`n🔧 檢查環境檔案..." -ForegroundColor Yellow

if (Test-Path "backend/.env") {
    Write-Host "✅ 後端環境檔案存在" -ForegroundColor Green
} else {
    Write-Host "⚠️ 後端環境檔案不存在 (執行: cd backend && copy .env.example .env)" -ForegroundColor Yellow
}

if (Test-Path "frontend/.env.local") {
    Write-Host "✅ 前端環境檔案存在" -ForegroundColor Green
} else {
    Write-Host "⚠️ 前端環境檔案不存在 (可選)" -ForegroundColor Yellow
}

Write-Host "`n📊 專案狀態檢查完成！" -ForegroundColor Green
Write-Host "`n💡 下一步建議：" -ForegroundColor Yellow
Write-Host "1. 如果缺少依賴，請執行安裝腳本" -ForegroundColor White
Write-Host "2. 設定環境變數檔案" -ForegroundColor White
Write-Host "3. 啟動開發環境" -ForegroundColor White
Write-Host "4. 開始開發功能" -ForegroundColor White 