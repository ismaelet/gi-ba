# Base step
FROM php:8.4-fpm AS base

# Base: Install PHP extensions
RUN apt-get update && apt-get install -y \
        zlib1g-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql mysqli intl \
    && docker-php-ext-enable mysqli

# Base: Set working directory
WORKDIR /var/www/html

# Required to generate images
RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libexif-dev && \
	docker-php-ext-install exif && \
	docker-php-ext-configure gd --with-webp --with-jpeg && \
	docker-php-ext-install gd
RUN echo "upload_max_filesize=1024M" > /usr/local/etc/php/conf.d/uploads.ini \
	&& echo "post_max_size=1024M" >> /usr/local/etc/php/conf.d/uploads.ini

# Required to generate XLSX files
RUN apt-get install -y libonig-dev && \
	docker-php-ext-install mbstring

# Required to convert number to words
RUN apt-get install -y libicu-dev && \
	docker-php-ext-install intl

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install NPM dependencies
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs