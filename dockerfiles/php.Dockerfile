FROM php:8.3-fpm


WORKDIR /var/www/laravel


RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev

RUN culr -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_pgsql

RUN pecl install xdebug \
  && docker-php-ext-enable xdebug


