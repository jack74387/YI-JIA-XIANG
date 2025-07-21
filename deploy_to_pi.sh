#!/bin/bash
PI_IP=192.168.99.27
PI_USER=pi
PI_PASS=0000

# 自動寫入前端 API baseURL
sed -i '' "s|^VITE_API_BASE_URL=.*|VITE_API_BASE_URL=http://$PI_IP:8000|g" frontend/.env

# 1. 清除舊的 SSH host key（避免 host key changed 錯誤）
ssh-keygen -R $PI_IP

# 2. 傳送檔案
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no yijiaxiang_arm64.tar $PI_USER@$PI_IP:/home/pi/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no docker-compose.yml $PI_USER@$PI_IP:/home/pi/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no backend/.env.example $PI_USER@$PI_IP:/home/pi/.env
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no -r backend/database/migrations $PI_USER@$PI_IP:/home/pi/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no -r backend/database/seeders $PI_USER@$PI_IP:/home/pi/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no -r frontend $PI_USER@$PI_IP:/home/pi/
# ...如有其他必要檔案請自行補上

# 3. 遠端自動安裝 Docker & compose
sshpass -p $PI_PASS ssh -o StrictHostKeyChecking=no $PI_USER@$PI_IP <<'ENDSSH'
sudo apt-get update
sudo apt-get install -y docker.io docker-compose-plugin sshpass
sudo usermod -aG docker $USER
if ! groups | grep -q docker; then
  sudo reboot
fi
ENDSSH

# 4. 載入 image、啟動 compose
sshpass -p $PI_PASS ssh -o StrictHostKeyChecking=no $PI_USER@$PI_IP <<'ENDSSH'
docker load -i yijiaxiang_arm64.tar
docker compose -f docker-compose.yml up -d
# 等待 backend 服務啟動
sleep 20
# 取得 backend 容器名稱
BACKEND_CONTAINER=$(docker ps --format '{{.Names}}' | grep backend)
# 執行 migration
if [ -n "$BACKEND_CONTAINER" ]; then
  docker exec -i "$BACKEND_CONTAINER" php artisan migrate --force
  docker exec -i "$BACKEND_CONTAINER" php artisan db:seed --force
else
  echo "找不到 backend 容器，無法執行 migration/seed"
fi
ENDSSH 