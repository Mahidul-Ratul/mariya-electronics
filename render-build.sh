#!/usr/bin/env bash
set -o errexit

echo "--- 1. Optimizing Build (using SQLite bypass) ---"

# These commands are "blindfolded" so they don't look for Aiven during build
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan package:discover --ansi
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan config:cache
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan route:cache

echo "--- 2. Running Migrations (using REAL MySQL) ---"

# We REMOVE the SQLite prefix here. 
# This command will use the DB_CONNECTION=mysql from your Render Environment.
php artisan migrate --force