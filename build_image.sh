#!/bin/bash
# 建立 Raspberry Pi 5 相容的 arm64 image
docker buildx build --platform linux/arm64/v8 --load -t yijiaxiang-arm64:0.0.1 -f backend/Dockerfile ./backend
docker save -o yijiaxiang-arm64_0.0.1.tar yijiaxiang-arm64:0.0.1