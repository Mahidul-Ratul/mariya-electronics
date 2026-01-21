#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Run database migrations
# The --force flag is required for production
php artisan migrate --force