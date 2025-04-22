FROM php:8.3-fpm

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
    nano \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Node.js (LTS) + npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
&& apt-get install -y nodejs

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy your full Laravel project from src/ into the container
COPY src/ /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx config
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy and enable start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port
EXPOSE 80

RUN composer install
RUN composer require laravel/breeze --dev
RUN npm install && npm run build

# Start services
CMD ["/start.sh"]