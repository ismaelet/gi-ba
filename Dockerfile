# Use the official PHP image with Apache
FROM php:apache

# Install the database
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install intl \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-enable intl \
	&& docker-php-ext-enable mysqli

# Enable .htaccess rewrite
RUN a2enmod rewrite
