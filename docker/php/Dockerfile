# ./php/Dockerfile

# Используем официальный образ PHP с FPM и alpine (легковесный)
FROM php:8.2-fpm-alpine

# Устанавливаем системные зависимости
RUN apk update && apk add \
    # Для установки расширений PHP \
    autoconf g++ make \
    # Для работы с MySQL\
    mysql-client \
    # Другие утилиты\
    git curl zip unzip libzip-dev \
    # Для GD расширения (работа с изображениями)\
    libpng-dev libjpeg-turbo-dev freetype-dev \
    # Для intl расширения\
    icu-dev \
    && rm -rf /var/cache/apk/*

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \          # Расширение для работы с MySQL через PDO\
    mysqli \             # Расширение mysqli\
    gd \                 # Работа с изображениями\
    zip \                # Работа с zip архивами\
    intl \               # Интернационализация\
    opcache \            # Кэширование PHP кода\
    bcmath               # Математические функции\

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