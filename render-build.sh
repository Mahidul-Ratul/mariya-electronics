#!/usr/bin/env bash
set -o errexit

echo "--- BUILDING APPLICATION ---"

# Build using SQLite bypass to stay invisible to Render's DNS block
DB_CONNECTION=sqlite DB_DATABASE=:memory: composer install --no-dev --optimize-autoloader
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan package:discover --ansi
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan config:cache
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan route:cache

echo "--- BUILD COMPLETE ---"