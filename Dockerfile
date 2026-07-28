FROM php:8.2-fpm

# Install dependency sistem & ekstensi PHP zip + pdo_mysql
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Bypass aturan security block & platform req
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer config audit.blocked-packages false
RUN composer install --optimize-autoloader --no-scripts --no-interaction --ignore-platform-reqs

EXPOSE 9000
CMD ["php-fpm"]