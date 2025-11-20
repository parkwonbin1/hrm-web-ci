FROM php:8.1-fpm-bullseye

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

USER www-data
RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000

CMD ["php-fpm"]

