# Get the base image
FROM php:apache

# Set working directory
WORKDIR /var/www/html

# Setup dependencies
RUN apt-get update --fix-missing
RUN curl -I http://deb.debian.org/debian-security/dists/bookworm-security/InRelease

# Install MySQLi
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql

# Install GD extension
RUN apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Enable htaccess rewrite
RUN a2enmod rewrite