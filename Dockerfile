# Use the official PHP image with Apache
FROM php:apache

# Set working directory
WORKDIR /var/www/html
COPY . /var/www/html

# Install the zip extension
RUN apt-get update && \
	apt-get install -y zlib1g-dev libzip-dev && \
	docker-php-ext-configure zip && \
	docker-php-ext-install zip


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

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - 
RUN apt-get install -y nodejs

# Install dependencies
RUN composer update
RUN npm install && npm run build && rm -r node_modules

# Enable .htaccess rewrite
RUN a2enmod rewrite
