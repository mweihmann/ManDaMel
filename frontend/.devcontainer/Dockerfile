FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    iproute2 \
    libzip-dev \
    unzip \
    git \
    curl \
    nano \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpq-dev \
    libsqlite3-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copy Xdebug config
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


