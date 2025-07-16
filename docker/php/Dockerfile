# docker/php/Dockerfile

FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libjpeg-dev libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Installer Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Facultatif : activer le mode coverage
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


COPY www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html
