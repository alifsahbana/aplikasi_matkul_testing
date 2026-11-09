# ==========================================
# Multi-stage Dockerfile: WebGIS Pamekasan
# Stage 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ==========================================
# Stage 2: Production PHP Runtime (PHP 8.2)
# ==========================================
FROM php:8.2-fpm-alpine AS production

# Install System Dependencies
RUN apk update && apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite-dev \
    postgresql-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev

# Install PHP Extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Project Files
COPY . /var/www/html

# Copy Compiled Assets from Stage 1
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Install PHP Dependencies (No Dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

