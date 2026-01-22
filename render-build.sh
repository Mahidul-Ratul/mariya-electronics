#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "--- STEP 1: Setting up code (using SQLite bypass) ---"
# We use SQLite here JUST to get past Render's networking block
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan package:discover --ansi
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan config:cache
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan route:cache

echo "--- STEP 2: Breaking the SQLite lock ---"
# This deletes the "Use SQLite" instruction we just made
php artisan config:clear

echo "--- STEP 3: Running Migrations (using REAL Aiven MySQL) ---"
# Now Laravel will read your REAL Render Environment Variables
php artisan migrate --force