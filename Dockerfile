# Stage 1 - Build frontend assets with Vite
FROM node:18 AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

# Stage 2 - Install PHP dependencies
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage 3 - Runtime image for Render
FROM php:8.2-cli
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip \
    && rm -rf /var/lib/apt/lists/*

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

EXPOSE 10000

CMD sh -c "php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"
