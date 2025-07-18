#!/bin/bash
set -e

echo "🔨 建立 Docker 映像..."
docker-compose build

echo "🚀 啟動服務..."
docker-compose up -d

echo "⏳ 等待資料庫啟動..."
sleep 10

echo "🗄️ 進行資料庫 migrate/seed..."
docker-compose exec backend php artisan migrate --seed

echo "✅ 部署完成！前端：http://localhost:5173  後端 API：http://localhost:8000" 