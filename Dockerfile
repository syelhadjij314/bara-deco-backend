FROM laravelsail/php83-composer:latest

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN docker-php-ext-install pdo_mysql

# 1. Projet Laravel dans /tmp puis déplacement
RUN composer create-project laravel/laravel /tmp/laravel --prefer-dist --no-interaction && \
    cp -r /tmp/laravel/. /var/www/html/ && \
    rm -rf /tmp/laravel

WORKDIR /var/www/html

# 2. Créer .env et générer la clé AU BUILD
RUN cp .env.example .env && php artisan key:generate --no-interaction

# 3. Sanctum + publish AU BUILD
RUN composer require laravel/sanctum --no-interaction && \
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --no-interaction

# 4. Nos fichiers métier
COPY app/      ./app/
COPY database/ ./database/
COPY routes/   ./routes/

# 5. Patch bootstrap/app.php
RUN sed -i "s/web: __DIR__.'\/..\/routes\/web.php',/api: __DIR__.'\/..\/routes\/api.php',\n        apiPrefix: 'api',/" bootstrap/app.php
RUN sed -i "s/->withMiddleware(function (Middleware \$middleware): void {/->withMiddleware(function (Middleware \$middleware): void {\n        \$middleware->alias(['admin' => \\\\App\\\\Http\\\\Middleware\\\\AdminMiddleware::class]);/" bootstrap/app.php

# 6. Autoload optimisé
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD sh -c "\
  until php artisan migrate --force; do \
    echo 'Attente MySQL... 5s' && sleep 5; \
  done && \
  php artisan db:seed --force && \
  php artisan storage:link || true && \
  php artisan config:cache && \
  php artisan route:cache && \
  php artisan serve --host=0.0.0.0 --port=8000"
