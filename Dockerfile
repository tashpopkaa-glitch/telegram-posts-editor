FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libssl-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /var/www/html

COPY . .

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

EXPOSE 80


