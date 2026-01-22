#!/usr/bin/env bash
# Exit on error
set -o errexit

# 1. Install dependencies without trying to connect to Aiven
DB_CONNECTION=sqlite DB_DATABASE=:memory: composer install --no-dev --optimize-autoloader

# 2. Cache configuration using a dummy connection
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan config:cache
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan route:cache

# 3. Run migrations (This will use your REAL DB_HOST from Render Environment)
# Note: This will only work if your Render Environment variables are correct
php artisan migrate --force