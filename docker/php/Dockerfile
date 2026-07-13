FROM composer:2 AS composer

FROM php:8.3-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update \
    && apt-get install -y libonig-dev unzip \
    && docker-php-ext-install mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
