# 1. Use PHP with Apache
FROM php:8.2-apache

# 2. Install system dependencies + GD requirements + SSL certs
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip ca-certificates \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# 3. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 4. Change Apache Document Root to Laravel's /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy application files
COPY . .

# 8. Install PHP dependencies
# We use these flags to prevent Laravel from trying to connect to a DB during the build
RUN DB_CONNECTION=sqlite DB_DATABASE=:memory: composer install --no-dev --optimize-autoloader

# 9. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Force Apache to bind to 0.0.0.0 (Required for Render)
RUN sed -i 's/Listen 80/Listen 0.0.0.0:80/' /etc/apache2/ports.conf

# 11. Expose port 80
EXPOSE 80

# 12. The Startup Command
# This clears cache, runs migrations, and starts Apache when the server goes LIVE
CMD ["/bin/sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && apache2-foreground"]