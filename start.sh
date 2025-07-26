#!/bin/bash

echo "🚂 Starting Railway deployment..."

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo "✅ Storage link created"
fi

# Clear configuration cache first (important for new env vars)
php artisan config:clear

# Wait for database to be ready and run migrations
echo "⏳ Waiting for database connection..."
until php artisan migrate:status > /dev/null 2>&1; do
    echo "Database not ready yet, waiting 2 seconds..."
    sleep 2
done

echo "✅ Database connection established"

# Run migrations (with --force for production)
php artisan migrate --force

# Clear other caches after database is ready
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache everything for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🎉 Railway setup completed!"

# Start the server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}