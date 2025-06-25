# Tumia PHP 8.1 FPM official image
FROM php:8.1-fpm

# Install dependencies muhimu na PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Nakili composer kutoka image ya composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Weka working directory
WORKDIR /var/www/html

# Nakili files zote za project
COPY . .

# Install dependencies za PHP kwa composer
RUN composer install --no-dev --optimize-autoloader

# Cache config, routes na views za Laravel
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Fungua port 9000 kwa PHP-FPM
EXPOSE 9000

# Anzisha PHP-FPM
CMD ["php-fpm"]
