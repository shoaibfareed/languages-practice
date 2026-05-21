# =========================================
# Laravel Dockerfile (PHP 8.4 - FIXED)
# =========================================

FROM php:8.4-fpm

WORKDIR /var/www

# =========================================
# System dependencies
# =========================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# =========================================
# PHP extensions
# =========================================
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# =========================================
# Composer
# =========================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN pecl install redis && docker-php-ext-enable redis

# =========================================
# Copy project
# =========================================
COPY . .

# =========================================
# Install dependencies
# =========================================
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# =========================================
# Permissions
# =========================================
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 9000

CMD ["php-fpm"]
