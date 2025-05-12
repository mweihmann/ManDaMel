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

# Copy custom Xdebug config
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Enable Apache Rewrite Module (optional)
RUN a2enmod rewrite

RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set custom DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini