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

# Create Laravel app in a clean temp directory
WORKDIR /tmp
RUN composer create-project --prefer-dist laravel/laravel laravel

# Move Laravel into /var/www/html
RUN rm -rf /var/www/html && mv laravel /var/www/html

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    
# Copy nginx config
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy shared .env into Laravel
COPY .env /var/www/html/.env

# Copy and set permissions for start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose HTTP port
EXPOSE 80

# Start Nginx and PHP-FPM
CMD ["/start.sh"]