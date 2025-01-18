FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

EXPOSE 9000

CMD ["php-fpm"]
