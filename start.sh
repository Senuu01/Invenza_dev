#!/bin/bash

echo "🚂 Starting Railway deployment..."

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo "✅ Storage link created"
fi

# Clear configuration cache first (important for new env vars)
php artisan config:clear

# Debug environment variables
echo "🔍 Debugging database environment variables:"
echo "DB_HOST: ${DB_HOST:-not set}"
echo "MYSQL_HOST: ${MYSQL_HOST:-not set}"
echo "DB_DATABASE: ${DB_DATABASE:-not set}"
echo "MYSQL_DATABASE: ${MYSQL_DATABASE:-not set}"
echo "DB_USERNAME: ${DB_USERNAME:-not set}"
echo "MYSQL_USER: ${MYSQL_USER:-not set}"
echo "DATABASE_URL: ${DATABASE_URL:-not set}"

# Wait for database to be ready and run migrations (with timeout)
echo "⏳ Waiting for database connection..."
TIMEOUT=120  # 2 minutes timeout
ELAPSED=0
until php artisan migrate:status > /dev/null 2>&1; do
    if [ $ELAPSED -ge $TIMEOUT ]; then
        echo "❌ Database connection timeout after ${TIMEOUT} seconds"
        echo "Attempting to continue with migrations anyway..."
        break
    fi
    echo "Database not ready yet, waiting 2 seconds... (${ELAPSED}/${TIMEOUT}s)"
    sleep 2
    ELAPSED=$((ELAPSED + 2))
done

if [ $ELAPSED -lt $TIMEOUT ]; then
    echo "✅ Database connection established"
else
    echo "⚠️ Proceeding without database connection confirmation"
fi

# Run migrations (with --force for production)
echo "🔄 Running database migrations..."
if ! php artisan migrate --force; then
    echo "❌ Migration failed, but continuing deployment..."
fi

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