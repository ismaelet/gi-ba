# Use the official PHP image with Apache
FROM php:apache

# Install the database
RUN apt-get update && apt-get install -y \
    libicu-dev

RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libexif-dev \
     && docker-php-ext-install exif

RUN docker-php-ext-install intl \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-install pdo pdo_mysql \
	&& docker-php-ext-install gd \
    && docker-php-ext-enable intl \
	&& docker-php-ext-enable mysqli \
	&& docker-php-ext-configure gd --with-webp --with-jpeg

# Enable .htaccess rewrite
RUN a2enmod rewrite
