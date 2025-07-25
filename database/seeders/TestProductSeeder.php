<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class TestProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a test category
        $category = Category::firstOrCreate(
            ['name' => 'Test Products'],
            [
                'description' => 'Products for testing purposes',
                'color' => '#ff6b6b',
                'icon' => 'fas fa-flask'
            ]
        );

        // Get or create a test supplier
        $supplier = Supplier::firstOrCreate(
            ['email' => 'test@stripe.com'],
            [
                'name' => 'Stripe Test Supplier',
                'phone' => '+1-555-0123',
                'address' => '123 Test Street',
                'city' => 'Test City',
                'state' => 'TC',
                'country' => 'Test Country',
                'status' => 'active'
            ]
        );

        // Create test product with $0 price
        $testProduct = Product::firstOrCreate(
            ['sku' => 'STRIPE-TEST-001'],
            [
                'name' => 'Stripe Test Product - Free',
                'description' => 'This is a test product with $0 price for testing Stripe payment integration. Perfect for testing checkout flows without any cost.',
                'price' => 0.00,
                'quantity' => 999,
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'status' => 'in_stock',
                'low_stock_threshold' => 10,
                'weight' => 0.1,
                'dimensions' => ['length' => 5, 'width' => 5, 'height' => 1],
                'image' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=400&fit=crop&crop=center'
            ]
        );

        // Create another test product with $0.01 price (minimum Stripe amount)
        $testProduct2 = Product::firstOrCreate(
            ['sku' => 'STRIPE-TEST-002'],
            [
                'name' => 'Stripe Test Product - $0.01',
                'description' => 'This is a test product with $0.01 price for testing Stripe payment integration. Minimum amount for Stripe processing.',
                'price' => 0.01,
                'quantity' => 999,
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'status' => 'in_stock',
                'low_stock_threshold' => 10,
                'weight' => 0.1,
                'dimensions' => ['length' => 5, 'width' => 5, 'height' => 1],
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=400&h=400&fit=crop&crop=center'
            ]
        );

        // Create a test product with $1.00 price
        $testProduct3 = Product::firstOrCreate(
            ['sku' => 'STRIPE-TEST-003'],
            [
                'name' => 'Stripe Test Product - $1.00',
                'description' => 'This is a test product with $1.00 price for testing Stripe payment integration. Standard test amount.',
                'price' => 1.00,
                'quantity' => 999,
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'status' => 'in_stock',
                'low_stock_threshold' => 10,
                'weight' => 0.1,
                'dimensions' => ['length' => 5, 'width' => 5, 'height' => 1],
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=400&h=400&fit=crop&crop=center'
            ]
        );

        $this->command->info('✅ Test products created successfully!');
        $this->command->info('📦 Product 1: Stripe Test Product - Free ($0.00)');
        $this->command->info('📦 Product 2: Stripe Test Product - $0.01');
        $this->command->info('📦 Product 3: Stripe Test Product - $1.00');
        $this->command->info('');
        $this->command->info('💡 Use these products to test your Stripe integration:');
        $this->command->info('   - $0.00: Test free checkout flow');
        $this->command->info('   - $0.01: Test minimum Stripe amount');
        $this->command->info('   - $1.00: Test standard payment flow');
    }
} 