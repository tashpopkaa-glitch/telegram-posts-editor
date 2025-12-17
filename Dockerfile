# =========================
# 1️⃣ Frontend build (Vite)
# =========================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY resources resources
COPY vite.config.js .
COPY public public

RUN npm run build


# =========================
# 2️⃣ Backend (Laravel)
# =========================
FROM php:8.2-apache

# PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libssl-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /var/www/html

# Laravel files
COPY . .

# Frontend build natijalarini ko‘chiramiz
COPY --from=frontend /app/public/build public/build

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Permissions + Apache
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Apache DocumentRoot -> public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# Laravel start
CMD php artisan key:generate --force || true \
 && php artisan migrate --force || true \
 && php artisan storage:link || true \
 && php artisan config:clear \
 && apache2-foreground
