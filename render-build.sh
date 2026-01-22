#!/usr/bin/env bash
# Exit on error
set -o errexit

# 1. Install dependencies
composer install --no-dev --optimize-autoloader

# 2. Clear and Cache configuration
php artisan config:cache
php artisan route:cache

# 3. Run migrations on the REAL MySQL database
# (Ensure DB_CONNECTION=mysql is set in Render Environment)
php artisan migrate --force