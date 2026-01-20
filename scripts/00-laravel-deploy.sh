#!/bin/bash

echo "Starting Laravel deployment script..."

# Install/update composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Clear and optimize Laravel caches
echo "Optimizing Laravel..."
php artisan optimize

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

echo "Laravel deployment script completed successfully!"