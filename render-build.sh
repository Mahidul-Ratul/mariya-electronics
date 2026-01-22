#!/usr/bin/env bash
set -o errexit

# 1. Run the discovery that we skipped in the Dockerfile
php artisan package:discover --ansi

# 2. Clear and Cache configuration
php artisan config:cache
php artisan route:cache

# 3. Run migrations on the REAL MySQL database
# This will now work because it's running in the "Live" environment
php artisan migrate --force