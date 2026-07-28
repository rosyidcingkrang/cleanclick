FROM php:8.2-fpm

# Install dependency sistem & ekstensi PHP zip, pdo_mysql, dan gd
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install pdo_mysql zip gd

# Install Composer versi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Abaikan pemeriksaan audit keamanan (--no-audit) & reqs platform saat install
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --optimize-autoloader --no-scripts --no-interaction --no-audit --ignore-platform-reqs

EXPOSE 9000
CMD ["php-fpm"]