FROM public.ecr.aws/docker/library/php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libpq-dev libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite bcmath intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=public.ecr.aws/docker/library/composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

CMD touch /data/database.sqlite && php artisan migrate --force && php -S 0.0.0.0:${PORT:-8080} -t public vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
