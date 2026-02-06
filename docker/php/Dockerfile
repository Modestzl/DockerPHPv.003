# ./php/Dockerfile

FROM php:8.2-fpm-alpine

# Устанавливаем системные зависимости
RUN apk update && apk add --no-cache  \
    autoconf g++ make \
    mysql-client \
    git curl zip unzip libzip-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    && rm -rf /var/cache/apk/*

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-configure intl && \
    docker-php-ext-install \
        pdo_mysql \
        mysqli \
        zip \
        gd \
        intl \
        mbstring \
        bcmath \
        opcache

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настраиваем права доступа для пользователя www-data
RUN chown -R www-data:www-data /var/www

# Переключаемся на пользователя www-data
USER www-data

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Экспонируем порт для PHP-FPM
EXPOSE 9000