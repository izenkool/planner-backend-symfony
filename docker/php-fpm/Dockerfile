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

ARG UID=1000
ARG GID=1000

RUN groupadd -g $GID dev && useradd -u $UID -g $GID -m dev

USER dev

WORKDIR /var/www/html
