FROM php:8.4-cli-alpine

# Install dependencies
RUN apk add --no-cache \
    git unzip zip curl libzip-dev \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Create the target directory if it doesn't exist (Laravel usually does, but good practice)
RUN mkdir -p storage/app/data

# Move the 'data' directory from the root of the app to storage/app/
# The 'data' folder is at /app/data after the 'COPY . .' command
# We want to move it to /app/storage/app/data
RUN mv data/* storage/app/data/

# Install dependencies
RUN composer install