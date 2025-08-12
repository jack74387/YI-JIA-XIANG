# Laravel backend Dockerfile
FROM php:8.2-fpm

# 安裝系統套件與 PHP 擴充
RUN apt-get update \
    && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring

# 安裝 Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www

# 複製專案檔案
COPY . /var/www

# 安裝 PHP 套件
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# 權限調整
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 啟動腳本
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"] 