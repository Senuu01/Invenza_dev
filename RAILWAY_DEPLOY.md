# 🚂 Railway Deployment Guide

## Required Environment Variables

Set these in your Railway service dashboard:

### Essential Variables
```
APP_NAME=Invenza
APP_ENV=production  
APP_DEBUG=false
APP_KEY=base64:B1uVDQJ4JjcyFcMddFv5kFM5ho/HEh4VYqKUbL8t7Go=
APP_URL=https://your-app.up.railway.app
```

### Database (Railway auto-injects these)
```
MYSQL_HOST=(auto-injected)
MYSQL_PORT=(auto-injected)
MYSQL_DATABASE=(auto-injected)
MYSQL_USER=(auto-injected)
MYSQL_PASSWORD=(auto-injected)
DB_CONNECTION=mysql
```

### Logging & Performance
```
LOG_CHANNEL=single
LOG_LEVEL=error
PAPERTRAIL_URL=logs.papertrailapp.com
PAPERTRAIL_PORT=12345
```

### Session & Cache
```
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_SECURE_COOKIE=true
```

### Stripe Keys (Add your actual keys)
```
STRIPE_SECRET=sk_live_your_actual_secret_key
STRIPE_KEY=pk_live_your_actual_publishable_key
STRIPE_WEBHOOK_SECRET=whsec_your_actual_webhook_secret
```

## Deployment Steps

1. **Add MySQL Service**: In Railway dashboard, add a MySQL database service
2. **Set Environment Variables**: Copy the variables above to your Laravel service
3. **Deploy**: Push your code or deploy from GitHub
4. **Check Logs**: Monitor deployment logs for any issues

## Troubleshooting

- **500 Error**: Check environment variables are set correctly
- **Database Error**: Ensure MySQL service is running and connected
- **Migration Error**: Check if tables were created properly
- **Asset Error**: Make sure `npm run build` completed successfully

## Manual Commands (if needed)

Connect to Railway service terminal and run:
```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
```