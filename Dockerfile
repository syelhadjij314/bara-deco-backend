FROM laravelsail/php83-composer:latest

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

# 1. Projet Laravel complet
RUN composer create-project laravel/laravel . --prefer-dist --no-interaction

# 2. Sanctum + publish AU BUILD (une seule fois)
RUN composer require laravel/sanctum --no-interaction && \
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --no-interaction

# 3. Nos fichiers métier
COPY app/      ./app/
COPY database/ ./database/
COPY routes/   ./routes/

# 4. Patch bootstrap/app.php : routes API + middleware admin
RUN sed -i "s/web: __DIR__.'\/..\/routes\/web.php',/api: __DIR__.'\/..\/routes\/api.php',\n        apiPrefix: 'api',/" bootstrap/app.php
RUN sed -i "s/->withMiddleware(function (Middleware \$middleware): void {/->withMiddleware(function (Middleware \$middleware): void {\n        \$middleware->alias(['admin' => \\\\App\\\\Http\\\\Middleware\\\\AdminMiddleware::class]);/" bootstrap/app.php

# 5. Autoload
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD sh -c "\
  php artisan key:generate --no-interaction --force && \
  until php artisan migrate --force; do \
    echo 'Attente MySQL... nouvelle tentative dans 5s' && sleep 5; \
  done && \
  php artisan db:seed --force && \
  php artisan storage:link && \
  php artisan serve --host=0.0.0.0 --port=8000"
