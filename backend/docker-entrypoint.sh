#!/bin/bash
set -e

# 建立 storage symlink（若已存在不會報錯）
php artisan storage:link || true

# 啟動 Laravel 服務
exec php artisan serve --host=0.0.0.0 --port=8000 