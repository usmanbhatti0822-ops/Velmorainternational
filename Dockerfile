FROM public.ecr.aws/docker/library/php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libpq-dev libsqlite3-dev nodejs npm \
    && docker-php-ext-install pdo_mysql pdo_sqlite bcmath intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=public.ecr.aws/docker/library/composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm ci && npm run build && rm -rf node_modules

CMD touch /data/database.sqlite && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
