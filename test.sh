#!/bin/bash
set -e  # 有錯誤就中止
set -o pipefail

# ====== 設定區 ======
PI_IP="192.168.99.27"
PI_USER="pi"
PI_PASS="0000"
PI_DIR="yijiaxiang"
IMAGE_NAME="yijiaxiang-arm64"
IMAGE_TAG="0.0.1"
BACKEND_DIR="./backend"
FRONTEND_DIR="./frontend"

# ====== Step 1: Build ARM64 Docker image ======
echo "==> Building Docker image for Raspberry Pi ARM64..."
docker buildx create --use --name multiarch-builder || true
docker buildx inspect --bootstrap

docker buildx build \
  --platform linux/arm64/v8 \
  --load \
  -t ${IMAGE_NAME}:${IMAGE_TAG} \
  -f ${BACKEND_DIR}/Dockerfile ${BACKEND_DIR}

docker save -o ${IMAGE_NAME}_${IMAGE_TAG}.tar ${IMAGE_NAME}:${IMAGE_TAG}

# ====== Step 2: Build frontend (production) ======
echo "==> Building frontend..."
sed -i "s|^VITE_API_BASE_URL=.*|VITE_API_BASE_URL=http://${PI_IP}:8000|g" ${FRONTEND_DIR}/.env
cd ${FRONTEND_DIR}
npm install
npm run build
cd ..

# ====== Step 3: 清除舊 SSH host key ======
ssh-keygen -R ${PI_IP} >/dev/null 2>&1

# ====== Step 4: 建立遠端目錄 & 安裝 Docker ======
echo "==> Creating remote directory & installing Docker..."
sshpass -p ${PI_PASS} ssh -o StrictHostKeyChecking=no ${PI_USER}@${PI_IP} "
  mkdir -p ~/${PI_DIR} &&
  sudo apt-get update &&
  sudo apt-get install -y docker.io docker-compose-plugin sshpass &&
  echo 'Docker installed.'
"

# ====== Step 5: 傳檔到 Pi ======
echo "==> Transferring files to Pi..."
# 傳檔案
sshpass -p ${PI_PASS} scp -o StrictHostKeyChecking=no \
  ${IMAGE_NAME}_${IMAGE_TAG}.tar \
  docker-compose.yml \
  ${BACKEND_DIR}/.env \
  ${PI_USER}@${PI_IP}:~/${PI_DIR}/

# 傳資料夾
sshpass -p ${PI_PASS} scp -o StrictHostKeyChecking=no -r \
  ${BACKEND_DIR}/database/migrations \
  ${BACKEND_DIR}/database/seeders \
  ${FRONTEND_DIR}/dist \
  ${PI_USER}@${PI_IP}:~/${PI_DIR}/

# ====== Step 6: 啟動容器 & Laravel 初始化 ======
echo "==> Starting containers and running migrations..."
sshpass -p ${PI_PASS} ssh -o StrictHostKeyChecking=no ${PI_USER}@${PI_IP} "
  cd ~/${PI_DIR} &&
  sudo docker load -i ${IMAGE_NAME}_${IMAGE_TAG}.tar &&
  sudo docker compose up -d &&
  sleep 20 &&
  BACKEND_CONTAINER=\$(sudo docker compose ps -q backend) &&
  if [ -n \"\$BACKEND_CONTAINER\" ]; then
    sudo docker compose exec -T backend php artisan config:clear &&
    sudo docker compose exec -T backend php artisan route:clear &&
    sudo docker compose exec -T backend php artisan cache:clear &&
    sudo docker compose exec -T backend php artisan config:cache &&
    sudo docker compose exec -T backend php artisan migrate --force &&
    sudo docker compose exec -T backend php artisan db:seed --force
  else
    echo '找不到 backend 容器，無法執行 migration/seed'
  fi
"

echo "✅ 部署完成！"
