#Get PHP 8.1 (Would like to use 8.2 but couldn't find a version of sqlite for 8.2 within limited timeframe!)
FROM php:8.1-fpm

# Arguments defined in docker-compose.yml - these users are passed into nginx
ARG user
ARG uid

# Install system dependencies - these are the php modules we need at a minimum for laravel to function.
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache - cache is annoying and causes issues that are not issues.
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions - These are the base level extensions we need for laravel to run
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer - Latest composer, best composer.  Incidentally has anyone worked with composer 1 legacy stuff recently?
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user
