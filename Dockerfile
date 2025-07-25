FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY src .

RUN chown -R $USER:www-data /var/www

EXPOSE 9000