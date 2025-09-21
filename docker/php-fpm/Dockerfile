FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

ARG UID
ARG GID

RUN groupmod -g $GID www-data && usermod -u $UID -g $GID www-data

USER www-data

WORKDIR /var/www/html

