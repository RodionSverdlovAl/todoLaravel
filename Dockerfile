# Используем образ PHP с поддержкой FPM
FROM php:8.1.0-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev

# Конфигурирование расширений PHP
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd zip pdo_mysql curl

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка рабочей директории
WORKDIR /var/www/html

# Копирование файлов приложения
COPY . /var/www/html

# Установка зависимостей Laravel
RUN composer install --ignore-platform-reqs --no-interaction

# Копирование файла .env
RUN cp .env.example .env

# Генерация ключа приложения
RUN php artisan key:generate

# Установка прав доступа к файлам
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Открытие порта
EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm"]
