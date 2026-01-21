# Use PHP with Apache for easier setup
FROM php:8.2-apache

# 1. Install system dependencies + ca-certificates for Aiven SSL
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip ca-certificates \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# 2. Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# 3. Change Apache Document Root to Laravel's /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Set working directory
WORKDIR /var/www/html

# 6. Copy application files
COPY . .

# 7. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 8. Set permissions for Laravel and the build script
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod +x /var/www/html/render-build.sh

# 9. Force Apache to bind to 0.0.0.0 (Public) instead of ::1 (Localhost)
# This is the fix for the "No open ports detected" error
RUN sed -i 's/Listen 80/Listen 0.0.0.0:80/' /etc/apache2/ports.conf

# 10. Expose port 80
EXPOSE 80

# 11. The Startup Command
CMD ["/bin/sh", "-c", "/var/www/html/render-build.sh && apache2-foreground"]