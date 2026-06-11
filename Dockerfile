FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git libonig-dev unzip \
    && docker-php-ext-install mbstring pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist

COPY . .

EXPOSE 1234

CMD ["php", "-S", "0.0.0.0:1234", "-t", "public", "public/index.php"]
