# 一佳香網站開發環境啟動腳本 (Windows)

Write-Host "🚀 啟動一佳香網站開發環境..." -ForegroundColor Green

# 啟動後端伺服器
Write-Host "`n🔧 啟動後端伺服器..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd backend; php artisan serve"

# 等待後端啟動
Start-Sleep -Seconds 3

# 啟動前端開發伺服器
Write-Host "`n📱 啟動前端開發伺服器..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd frontend; npm run dev"

Write-Host "`n✅ 開發環境已啟動！" -ForegroundColor Green
Write-Host "`n📱 前端網址: http://localhost:3000" -ForegroundColor Cyan
Write-Host "🔧 後端API: http://localhost:8000" -ForegroundColor Cyan
Write-Host "📊 API 文件: http://localhost:8000/api/documentation" -ForegroundColor Cyan

Write-Host "`n💡 提示：" -ForegroundColor Yellow
Write-Host "- 按 Ctrl+C 停止伺服器" -ForegroundColor White
Write-Host "- 修改程式碼會自動重新載入" -ForegroundColor White
Write-Host "- 查看終端機輸出以監控錯誤" -ForegroundColor White 