# Base image
FROM dunglas/frankenphp:1-php8.4 AS base

# Install PHP extensions
RUN install-php-extensions \
    mysqli \
    pdo \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache

RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Install Composer
RUN install-php-extensions \
    @composer

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_22.x | bash - && \
	apt-get update && \
	apt-get install -y nodejs

# Install NPM
RUN npm install npm -g

# Build step
FROM base AS build

# Copy application files
COPY ./app /var/www/html

# Install composer dependencies
RUN composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader

# Install NPM dependencies
RUN npm install\
	&& npm run build
