#!/usr/bin/env bash
set -o errexit

echo "--- Starting Render Build Script ---"

# 1. Install dependencies (Dummy DB connection to prevent DNS crash)
DB_CONNECTION=sqlite DB_DATABASE=:memory: composer install --no-dev --optimize-autoloader

# 2. Run discovery and caching (Dummy DB connection)
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan package:discover --ansi
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan config:cache
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan route:cache

echo "--- Build Phase Complete. Starting Migration Phase ---"

# 3. Run migrations on REAL MySQL
# This only runs if the DB_HOST is reachable (which it is at this stage of deployment)
php artisan migrate --force