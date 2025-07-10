FROM php:8.4-cli-alpine

# Install dependencies
RUN apk add --no-cache \
    git unzip zip curl libzip-dev \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependencies
RUN composer install