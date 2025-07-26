#!/bin/bash

echo "🚂 Starting Railway deployment..."

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo "✅ Storage link created"
fi

# Clear configuration cache first (important for new env vars)
php artisan config:clear

# Set environment variables for Laravel from Railway's MySQL variables
export DB_CONNECTION=mysql

# Use Railway MySQL variables directly
export DB_HOST="$MYSQLHOST"
export DB_PORT="${MYSQLPORT:-3306}"
export DB_DATABASE="$MYSQLDATABASE"
export DB_USERNAME="$MYSQLUSER"
export DB_PASSWORD="$MYSQLPASSWORD"

echo "🔧 Setting DB variables from Railway MySQL service:"
echo "  DB_HOST=$DB_HOST"
echo "  DB_PORT=$DB_PORT"
echo "  DB_DATABASE=$DB_DATABASE"
echo "  DB_USERNAME=$DB_USERNAME"
echo "  DB_PASSWORD=${DB_PASSWORD:+[SET]}"

# Fallback: Parse MYSQL_URL if individual variables not available
if [ -z "$DB_HOST" ] && [ -n "$MYSQL_URL" ]; then
    echo "🔄 Parsing MYSQL_URL for database connection details..."
    echo "MYSQL_URL: $MYSQL_URL"
    
    # Extract components from mysql://user:password@host:port/database
    # More robust parsing with better regex
    DB_HOST_PARSED=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^:]*:[^@]*@\([^:]*\):.*|\1|p')
    DB_PORT_PARSED=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^:]*:[^@]*@[^:]*:\([^/]*\)/.*|\1|p')
    DB_DATABASE_PARSED=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^:]*:[^@]*@[^:]*:[^/]*/\([^?]*\).*|\1|p')
    DB_USERNAME_PARSED=$(echo "$MYSQL_URL" | sed -n 's|mysql://\([^:]*\):.*|\1|p')
    DB_PASSWORD_PARSED=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^:]*:\([^@]*\)@.*|\1|p')
    
    if [ -n "$DB_HOST_PARSED" ]; then
        export DB_HOST="$DB_HOST_PARSED"
        export DB_PORT="${DB_PORT_PARSED:-3306}"
        export DB_DATABASE="$DB_DATABASE_PARSED"
        export DB_USERNAME="$DB_USERNAME_PARSED"
        export DB_PASSWORD="$DB_PASSWORD_PARSED"
        echo "✅ Successfully parsed MYSQL_URL"
    else
        echo "❌ Failed to parse MYSQL_URL"
    fi
fi

# Final fallback: Use known Railway MySQL details if still no connection
if [ -z "$DB_HOST" ]; then
    echo "🔄 Using Railway MySQL fallback configuration..."
    export DB_HOST="mainline.proxy.rlwy.net"
    export DB_PORT="3306"
    export DB_DATABASE="railway"
    export DB_USERNAME="root"
    # Check for any password-related environment variables
    if [ -n "$MYSQL_ROOT_PASSWORD" ]; then
        export DB_PASSWORD="$MYSQL_ROOT_PASSWORD"
    elif [ -n "$PASSWORD" ]; then
        export DB_PASSWORD="$PASSWORD"
    elif [ -n "$ROOT_PASSWORD" ]; then
        export DB_PASSWORD="$ROOT_PASSWORD"
    else
        echo "⚠️ No password found in environment variables"
    fi
    echo "✅ Railway fallback configuration set"
fi

# Debug environment variables after setting
echo "🔍 Debugging database environment variables:"
echo "DB_HOST: ${DB_HOST:-not set}"
echo "DB_PORT: ${DB_PORT:-not set}"
echo "DB_DATABASE: ${DB_DATABASE:-not set}"
echo "DB_USERNAME: ${DB_USERNAME:-not set}"
echo "DB_PASSWORD: ${DB_PASSWORD:+[SET]}"
echo "MYSQLHOST: ${MYSQLHOST:-not set}"
echo "MYSQLDATABASE: ${MYSQLDATABASE:-not set}"
echo "MYSQLUSER: ${MYSQLUSER:-not set}"
echo "MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD:+[SET]}"
echo "MYSQL_URL: ${MYSQL_URL:-not set}"
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