# MySQL Database Configuration

## 📊 Database Details

- **Database Name**: `product_inventory`
- **Character Set**: `utf8mb4`
- **Collation**: `utf8mb4_unicode_ci`
- **Connection**: MySQL (configured in `.env`)

## 🗄️ Database Structure

### Tables Created:
1. **users** - User accounts with roles
2. **products** - Product inventory data
3. **migrations** - Laravel migration tracking
4. **cache** / **cache_locks** - Application caching
5. **sessions** - User session storage
6. **jobs** / **job_batches** / **failed_jobs** - Queue system
7. **password_reset_tokens** - Password reset functionality

## 👥 Sample Data Inserted

### Users (2 records):
- **Admin User**: admin@inventory.com (role: admin)
- **Customer User**: customer@inventory.com (role: customer)

### Products (15 records):
Diverse product catalog including:
- Electronics (MacBook Pro, iPhone, Samsung TV, etc.)
- Audio equipment (Sony headphones, Bose earbuds)
- Gaming accessories
- Home appliances
- And more...

## 🔧 Configuration

Current `.env` settings:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_inventory
DB_USERNAME=root
DB_PASSWORD=
```

## ✅ Migration Status

All migrations have been successfully applied:
- User table with role field
- Products table with inventory fields
- Laravel system tables (cache, jobs, sessions)

## 🔍 Database Verification Commands

Check data integrity:
```bash
# View all tables
mysql -u root -e "USE product_inventory; SHOW TABLES;"

# Check user accounts
mysql -u root -e "USE product_inventory; SELECT name, email, role FROM users;"

# Check product count
mysql -u root -e "USE product_inventory; SELECT COUNT(*) as product_count FROM products;"

# View sample products
mysql -u root -e "USE product_inventory; SELECT name, category, price, quantity FROM products LIMIT 5;"
```

The database is fully configured and ready for the Laravel application!