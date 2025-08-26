#!/bin/bash
PI_IP=192.168.99.27
PI_USER=pi
PI_PASS=0000
PI_DIR="yijiaxiang"

# 自動寫入前端 API baseURL
sed -i  "s|^VITE_API_BASE_URL=.*|VITE_API_BASE_URL=https://course.ingenuity.com.tw|g" frontend/.env

# 1. 清除舊的 SSH host key（避免 host key changed 錯誤）
ssh-keygen -R $PI_IP

# 2. 建立遠端 yijiaxiang 資料夾
sshpass -p $PI_PASS ssh -o StrictHostKeyChecking=no $PI_USER@$PI_IP "mkdir -p ~/$PI_DIR"

# 3. 傳送檔案到 Pi 的 yijiaxiang 資料夾
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no yijiaxiang-arm64_0.0.1.tar $PI_USER@$PI_IP:~/$PI_DIR/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no docker-compose.yml $PI_USER@$PI_IP:~/$PI_DIR/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no backend/.env $PI_USER@$PI_IP:~/$PI_DIR/.env
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no -r backend/database/migrations $PI_USER@$PI_IP:~/$PI_DIR/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no -r backend/database/seeders $PI_USER@$PI_IP:~/$PI_DIR/
sshpass -p $PI_PASS scp -o StrictHostKeyChecking=no -r frontend $PI_USER@$PI_IP:~/$PI_DIR/
# ...如有其他必要檔案請自行補上

# 4. 遠端自動安裝 Docker & compose
sshpass -p $PI_PASS ssh -o StrictHostKeyChecking=no $PI_USER@$PI_IP <<'ENDSSH'
sudo apt-get update
sudo apt-get install -y docker.io docker-compose-plugin sshpass
sudo usermod -aG docker $USER
if ! groups | grep -q docker; then
  sudo reboot
fi
ENDSSH

# 5. 載入 image、啟動 compose、執行 migration/seed
sshpass -p $PI_PASS ssh -o StrictHostKeyChecking=no $PI_USER@$PI_IP <<'ENDSSH'
cd ~/yijiaxiang
docker load -i yijiaxiang-arm64_0.0.1.tar
docker compose up -d
sleep 20
BACKEND_CONTAINER=$(docker compose ps -q backend)
if [ -n "$BACKEND_CONTAINER" ]; then
  docker compose exec backend php artisan config:clear
  docker compose exec backend php artisan route:clear
  docker compose exec backend php artisan cache:clear
  docker compose exec backend php artisan config:cache
  docker compose exec backend php artisan migrate --force
  docker compose exec backend php artisan db:seed --force
else
  echo "找不到 backend 容器，無法執行 migration/seed"
fi
ENDSSH