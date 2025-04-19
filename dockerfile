FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Setup working directory
WORKDIR /var/www/html
COPY .env /var/www/html/.env

# Copy Nginx configuration
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy and install Laravel
COPY . /var/www/html
RUN composer create-project --prefer-dist laravel/laravel .

# Copy nginx startup script
CMD service php8.1-fpm start && nginx -g 'daemon off;'