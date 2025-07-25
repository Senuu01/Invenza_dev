# Product Inventory Management System

A secure Laravel 11 web application for managing product inventory with role-based access control.

## 🛠️ Technology Stack

- **Framework**: Laravel 11
- **Authentication**: Laravel Breeze
- **Database**: MySQL
- **Frontend**: Blade templates with Tailwind CSS
- **PHP Version**: 8.2+

## 🔐 Features

### Authentication & Security
- User registration and login (Laravel Breeze)
- Role-based access control (Admin/Customer)
- CSRF protection on all forms
- XSS-safe output using Blade templates
- Input validation and sanitization
- Secure middleware protection

### Admin Features
- Full CRUD operations for products
- Dashboard with inventory statistics
- Product management interface
- Low stock alerts
- Inventory value tracking

### Customer Features
- Read-only product catalog
- Product detail viewing
- Responsive product grid layout
- Search and pagination

## 📋 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd product-inventory
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **MySQL Database setup**
   ```bash
   # Create MySQL database
   mysql -u root -e "CREATE DATABASE product_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   
   # Configure your database in .env file:
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=product_inventory
   # DB_USERNAME=root
   # DB_PASSWORD=your_password
   
   # Run migrations and seed data
   php artisan migrate:fresh --seed
   ```

5. **Start the application**
   ```bash
   php artisan serve
   ```

## 👥 Default User Accounts

### Admin Account
- **Email**: admin@inventory.com
- **Password**: password
- **Role**: Admin (Full CRUD access)

### Customer Account
- **Email**: customer@inventory.com
- **Password**: password
- **Role**: Customer (Read-only access)

## 🗂️ Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminDashboardController.php
│   │   ├── CustomerDashboardController.php
│   │   └── ProductController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── CustomerMiddleware.php
│   └── Requests/
│       ├── StoreProductRequest.php
│       └── UpdateProductRequest.php
├── Models/
│   ├── Product.php
│   └── User.php
database/
├── migrations/
│   ├── create_products_table.php
│   └── add_role_to_users_table.php
└── seeders/
    ├── AdminUserSeeder.php
    └── ProductSeeder.php
resources/views/
├── admin/
│   └── dashboard.blade.php
├── customer/
│   ├── dashboard.blade.php
│   └── product-detail.blade.php
├── products/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
└── layouts/
    ├── app.blade.php
    └── navigation.blade.php
```

## 🔒 Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **XSS Prevention**: All user input is escaped using Blade's `{{ }}` syntax
- **Input Validation**: Form requests validate all user input
- **Role-based Access**: Middleware ensures proper authorization
- **Password Hashing**: Bcrypt hashing for all passwords
- **SQL Injection Prevention**: Eloquent ORM prevents SQL injection

## 🎨 UI Features

- **Responsive Design**: Mobile-friendly Tailwind CSS styling
- **Clean Interface**: Professional admin and customer dashboards
- **Flash Messages**: Success/error message notifications
- **Pagination**: Efficient product listing with pagination
- **Low Stock Alerts**: Visual indicators for low inventory

## 📊 Database Schema

### Users Table
- id, name, email, password, role, timestamps

### Products Table
- id, name, description, price, quantity, category, timestamps

## 🚀 Usage

1. **Admin Workflow**:
   - Login with admin credentials
   - Access admin dashboard
   - Manage products (Create, Read, Update, Delete)
   - Monitor inventory statistics

2. **Customer Workflow**:
   - Login with customer credentials
   - Browse product catalog
   - View product details
   - Check availability

## 🧪 Testing

The application includes comprehensive validation and error handling:

- Form validation with custom error messages
- Database constraints and relationships
- Middleware authentication and authorization
- XSS and CSRF protection testing

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please ensure all code follows Laravel best practices and includes proper security measures.