#!/bin/bash

echo "🚂 Starting Railway deployment..."

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo "✅ Storage link created"
fi

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations (with --force for production)
php artisan migrate --force

# Cache everything for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🎉 Railway setup completed!"

# Start the server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}