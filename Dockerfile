FROM public.ecr.aws/docker/library/php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libpq-dev libsqlite3-dev nodejs npm \
    && docker-php-ext-install pdo_mysql pdo_sqlite bcmath intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=public.ecr.aws/docker/library/composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENV DB_CONNECTION=sqlite DB_DATABASE=/data/database.sqlite

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm ci && npm run build && rm -rf node_modules

CMD if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then DB_FILE="${DB_DATABASE:-/data/database.sqlite}"; mkdir -p "$(dirname "$DB_FILE")"; touch "$DB_FILE"; fi && php artisan migrate --force && php artisan velmora:seed-demo-catalog --no-interaction && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
