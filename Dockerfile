FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd soap \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www-image

# 1. Cria projeto Laravel limpo
RUN composer create-project laravel/laravel:^11.0 . --no-interaction --prefer-dist

# 2. Copia os arquivos da aplicação por cima do Laravel base
COPY app/       app/
COPY database/  database/
COPY resources/ resources/
COPY routes/    routes/
COPY .env.example .env.example

# 3. Copia entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
