<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get supplier and category IDs
        $techCorp = Supplier::where('name', 'TechCorp Ltd.')->first();
        $comfortDesk = Supplier::where('name', 'ComfortDesk Inc.')->first();
        $workspacePro = Supplier::where('name', 'WorkSpace Pro')->first();
        $peripheralPro = Supplier::where('name', 'PeripheralPro')->first();
        
        $electronics = Category::where('name', 'Electronics')->first();
        $furniture = Category::where('name', 'Furniture')->first();
        $accessories = Category::where('name', 'Accessories')->first();

        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'sku' => 'WH-001',
                'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life.',
                'price' => 89.99,
                'quantity' => 20,
                'stock' => 45,
                'status' => 'in_stock',
                'supplier_id' => $techCorp->id ?? 1,
                'category_id' => $electronics->id ?? 1,
            ],
            [
                'name' => 'Office Ergonomic Chair',
                'sku' => 'OC-015',
                'description' => 'Comfortable ergonomic office chair with lumbar support and adjustable height.',
                'price' => 299.99,
                'quantity' => 10,
                'stock' => 8,
                'status' => 'low_stock',
                'supplier_id' => $comfortDesk->id ?? 2,
                'category_id' => $furniture->id ?? 2,
            ],
            [
                'name' => 'Laptop Stand Adjustable',
                'sku' => 'LS-008',
                'description' => 'Aluminum laptop stand with adjustable height and angle for better ergonomics.',
                'price' => 49.99,
                'quantity' => 15,
                'stock' => 0,
                'status' => 'out_of_stock',
                'supplier_id' => $workspacePro->id ?? 3,
                'category_id' => $accessories->id ?? 3,
            ],
            [
                'name' => 'USB-C Cable 6ft',
                'sku' => 'UC-025',
                'description' => 'High-speed USB-C cable with fast charging support and data transfer.',
                'price' => 19.99,
                'quantity' => 50,
                'stock' => 150,
                'status' => 'in_stock',
                'supplier_id' => $techCorp->id ?? 1,
                'category_id' => $electronics->id ?? 1,
            ],
            [
                'name' => 'Wireless Mouse',
                'sku' => 'WM-012',
                'description' => 'Ergonomic wireless mouse with precision tracking and long battery life.',
                'price' => 39.99,
                'quantity' => 30,
                'stock' => 25,
                'status' => 'low_stock',
                'supplier_id' => $peripheralPro->id ?? 4,
                'category_id' => $electronics->id ?? 1,
            ],
            [
                'name' => 'Standing Desk Converter',
                'sku' => 'SD-203',
                'description' => 'Adjustable standing desk converter for healthier work posture.',
                'price' => 199.99,
                'quantity' => 8,
                'stock' => 12,
                'status' => 'in_stock',
                'supplier_id' => $workspacePro->id ?? 3,
                'category_id' => $furniture->id ?? 2,
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'sku' => 'KB-045',
                'description' => 'Gaming mechanical keyboard with RGB backlighting and programmable keys.',
                'price' => 129.99,
                'quantity' => 25,
                'stock' => 35,
                'status' => 'in_stock',
                'supplier_id' => $techCorp->id ?? 1,
                'category_id' => $electronics->id ?? 1,
            ],
            [
                'name' => 'Monitor Stand Bamboo',
                'sku' => 'MS-017',
                'description' => 'Eco-friendly bamboo monitor stand with storage compartment.',
                'price' => 59.99,
                'quantity' => 20,
                'stock' => 28,
                'status' => 'in_stock',
                'supplier_id' => $comfortDesk->id ?? 2,
                'category_id' => $accessories->id ?? 3,
            ],
            [
                'name' => 'Webcam 4K HD',
                'sku' => 'WC-089',
                'description' => '4K HD webcam with auto-focus and built-in microphone for video calls.',
                'price' => 149.99,
                'quantity' => 15,
                'stock' => 18,
                'status' => 'in_stock',
                'supplier_id' => $techCorp->id ?? 1,
                'category_id' => $electronics->id ?? 1,
            ],
            [
                'name' => 'Desk Organizer Set',
                'sku' => 'DO-033',
                'description' => 'Complete desk organizer set with pen holder, document tray, and accessories.',
                'price' => 39.99,
                'quantity' => 30,
                'stock' => 42,
                'status' => 'in_stock',
                'supplier_id' => $workspacePro->id ?? 3,
                'category_id' => $accessories->id ?? 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}