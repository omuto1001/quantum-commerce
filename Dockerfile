# Use official PHP 8.4 image with common extensions
FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs

# Install Node dependencies and build assets
RUN npm install && npm run build

# Expose the port Railway will use
EXPOSE 8080

# Start command
CMD php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}