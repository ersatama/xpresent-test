FROM php:8.3-fpm

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev netcat-traditional \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем проект
COPY . .

# Устанавливаем зависимости проекта
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Копируем стартовый скрипт
COPY docker/php/start-container.sh /usr/local/bin/start-container.sh
RUN chmod +x /usr/local/bin/start-container.sh

EXPOSE 9000

# Запуск контейнера через кастомный entrypoint
CMD ["start-container.sh"]
