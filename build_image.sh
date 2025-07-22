#!/bin/bash
# 建立 Raspberry Pi 5 相容的 arm64 image
docker buildx build --platform linux/arm64/v8 --load -t yijiaxiang:arm64 -f backend/Dockerfile ./backend
docker save -o yijiaxiang_arm64.tar yijiaxiang:arm64 