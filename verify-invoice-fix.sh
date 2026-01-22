#!/bin/bash
# Deployment Verification Script for Invoice Fix

echo "🔍 Verifying Invoice Template Deployment..."

# Check if latest invoice template exists
if [ -f "resources/views/sales/invoice-pdf-clean.blade.php" ]; then
    echo "✅ Latest invoice template (invoice-pdf-clean.blade.php) exists"
else
    echo "❌ Latest invoice template missing!"
    exit 1
fi

# Check controller references
if grep -q "invoice-pdf-clean" app/Http/Controllers/SaleController.php; then
    echo "✅ SaleController updated to use latest template"
else
    echo "❌ SaleController still using old template!"
    exit 1
fi

# Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Rebuild optimized caches
echo "⚡ Building optimized caches..."
php artisan config:cache
php artisan route:cache

echo "✨ Deployment verification complete!"
echo ""
echo "📋 Next steps for users/hosting:"
echo "1. Commit and push these changes to git"
echo "2. Re-deploy to Render or re-send zip file"
echo "3. Test invoice generation on the deployed site"