# Invenza

<p align="center">
    <img src="https://via.placeholder.com/400x100/3b82f6/ffffff?text=Invenza" alt="Invenza Logo" width="400">
</p>

**Invenza** is a modern inventory management and e‑commerce platform built with Laravel. It offers tools for managing products, suppliers and customers while supporting full order processing with Stripe.

## Features

* Role‑based portals for **Admin**, **Staff** and **Customer**
* Comprehensive product and stock management
* Supplier and customer management
* Invoice and proposal generation with PDF export
* Real‑time dashboards and notifications
* Shopping cart and checkout powered by Stripe
* Optional store seeding with real product data

## Quick Start

1. Clone the repository and install PHP dependencies:
   ```bash
   composer install
   ```
2. Install Node dependencies and compile assets:
   ```bash
   npm install
   npm run build # or npm run dev
   ```
3. Copy `.env.example` to `.env` and adjust your database credentials.
4. Generate the application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
   To populate the store with sample data run:
   ```bash
   ./populate-store.sh
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

### Default Credentials

* **Admin**: `admin@invenza.com` / `password`
* **Staff**: `staff@invenza.com` / `password`
* **Customer**: `customer@invenza.com` / `password`

## Development

* `composer dev` – run the web server, queue worker, log tail and Vite
* `composer test` – run the test suite

Add your Stripe keys and webhook secret to `.env`. See `STRIPE_SETUP.md` and `WEBHOOK_SETUP.md` for details.

Further documentation:

* `DATABASE_INFO.md` – database schema and sample data
* `STORE_POPULATION_GUIDE.md` – using real product APIs
* `STRIPE_SETUP.md` – Stripe configuration
* `WEBHOOK_SETUP.md` – webhook event guide

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
