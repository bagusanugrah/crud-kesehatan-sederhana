FROM php:8.3-cli
RUN apt-get update && apt-get install -y libzip-dev zip unzip default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader
CMD php artisan serve --host=0.0.0.0 --port=8000