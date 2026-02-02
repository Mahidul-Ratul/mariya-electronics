FROM php:8.2-apache

# 1. Install GD (for Invoices) and system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip ca-certificates \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# 2. Enable Apache rewrite
RUN a2enmod rewrite

# 3. Setup Laravel Public Directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Install Composer & Project Files
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .

# --- FIX START ---
# Ensure the certs folder exists and has the right permissions 
# (This must come AFTER 'COPY . .')
RUN mkdir -p /var/www/html/certs && chmod -R 755 /var/www/html/certs
# --- FIX END ---

# 5. Install Dependencies (Using sqlite bypass to avoid DB check during build)
RUN DB_CONNECTION=sqlite DB_DATABASE=:memory: composer install --no-dev --optimize-autoloader

# 6. Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Render Port Binding
RUN sed -i 's/Listen 80/Listen 0.0.0.0:80/' /etc/apache2/ports.conf
EXPOSE 80

# We use migrate:fresh to delete the "broken" tables and create them correctly.
# ✅ TO THIS:
CMD php artisan config:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan db:seed --class=DefaultUserSeeder --force && \
    exec apache2-foreground