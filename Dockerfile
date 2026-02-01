FROM php:8.2-apache

# 1. Install GD and dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip ca-certificates \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# 2. DOWNLOAD AIVEN CA CERTIFICATE (This fixes your SSL error)
RUN mkdir -p /var/www/html/certs && \
    curl https://cacerts.digicert.com/DigiCertGlobalRootG2.crt.pem -o /var/www/html/certs/ca.pem

RUN a2enmod rewrite

# 3. Setup Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .

RUN DB_CONNECTION=sqlite DB_DATABASE=:memory: composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN sed -i 's/Listen 80/Listen 0.0.0.0:80/' /etc/apache2/ports.conf
EXPOSE 80

# 4. Safe Startup: Apache starts first, migrations follow in background
CMD php artisan config:clear && php artisan cache:clear && apache2-foreground & sleep 20 && php artisan migrate --force && wait