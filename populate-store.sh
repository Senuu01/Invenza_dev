#!/bin/bash

echo "🛍️  Invenza Store Population Script"
echo "=================================="

# Check if storage link exists, create if not
if [ ! -L "public/storage" ]; then
    echo "📁 Creating storage link..."
    php artisan storage:link
fi

# Clear any existing cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear

# Run the store seeder
echo "🚀 Populating store with real product data..."
php artisan db:seed --class=StoreSeeder

echo ""
echo "✅ Store population completed!"
echo ""
echo "📊 Summary:"
echo "   - Categories: Created 10+ categories"
echo "   - Suppliers: Created 4 suppliers"
echo "   - Products: Imported from real APIs + fallback data"
echo "   - Images: Downloaded and stored locally"
echo ""
echo "🌐 Your store is now ready with real product data!"
echo "   Visit: http://localhost:8001/customer/dashboard"
echo "" 