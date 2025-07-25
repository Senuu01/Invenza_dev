<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RealProductSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🛍️ Starting real product seeder with images...');

        // Create categories if they don't exist
        $this->createCategories();
        
        // Create suppliers if they don't exist
        $this->createSuppliers();
        
        // Fetch and create products from Fake Store API
        $this->fetchFromFakeStore();
        
        // Fetch and create products from DummyJSON
        $this->fetchFromDummyJSON();

        $this->command->info('✅ Real product seeder completed!');
    }

    private function createCategories()
    {
        $categories = [
            'Electronics' => 'Smartphones, laptops, tablets, and electronic devices',
            'Clothing' => 'Fashion apparel, shoes, and accessories',
            'Home & Garden' => 'Furniture, decor, and garden supplies',
            'Sports & Outdoors' => 'Sports equipment and outdoor gear',
            'Books' => 'Books, magazines, and educational materials',
            'Beauty & Health' => 'Cosmetics, skincare, and health products',
            'Toys & Games' => 'Children toys and entertainment',
            'Automotive' => 'Car parts, accessories, and maintenance',
            'Jewelry' => 'Precious metals, gemstones, and accessories',
            'Food & Beverages' => 'Grocery items and beverages'
        ];

        foreach ($categories as $name => $description) {
            Category::firstOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'color' => $this->getRandomColor(),
                    'icon' => $this->getCategoryIcon($name)
                ]
            );
        }
    }

    private function createSuppliers()
    {
        $suppliers = [
            [
                'name' => 'TechCorp Industries',
                'email' => 'contact@techcorp.com',
                'phone' => '+1-555-0101',
                'address' => '123 Tech Street, Silicon Valley, CA 94025',
                'status' => 'active'
            ],
            [
                'name' => 'Fashion Forward Ltd',
                'email' => 'info@fashionforward.com',
                'phone' => '+1-555-0202',
                'address' => '456 Fashion Avenue, New York, NY 10001',
                'status' => 'active'
            ],
            [
                'name' => 'Home Essentials Co',
                'email' => 'sales@homeessentials.com',
                'phone' => '+1-555-0303',
                'address' => '789 Home Lane, Chicago, IL 60601',
                'status' => 'active'
            ],
            [
                'name' => 'Sports Gear Pro',
                'email' => 'orders@sportsgearpro.com',
                'phone' => '+1-555-0404',
                'address' => '321 Sports Blvd, Miami, FL 33101',
                'status' => 'active'
            ]
        ];

        foreach ($suppliers as $supplierData) {
            try {
                Supplier::firstOrCreate(
                    ['name' => $supplierData['name']],
                    $supplierData
                );
            } catch (\Exception $e) {
                // Supplier might already exist, continue
            }
        }
    }

    private function fetchFromFakeStore()
    {
        $this->command->info('📡 Fetching products from Fake Store API...');
        
        try {
            $response = Http::timeout(30)->get('https://fakestoreapi.com/products');
            
            if ($response->successful()) {
                $products = $response->json();
                $this->command->info("📦 Found " . count($products) . " products from Fake Store API");
                
                foreach ($products as $productData) {
                    $this->createProductFromFakeStore($productData);
                }
            } else {
                $this->command->warn('⚠️ Failed to fetch from Fake Store API');
            }
        } catch (\Exception $e) {
            $this->command->warn('⚠️ Error fetching from Fake Store API: ' . $e->getMessage());
        }
    }

    private function fetchFromDummyJSON()
    {
        $this->command->info('📡 Fetching products from DummyJSON...');
        
        try {
            $response = Http::timeout(30)->get('https://dummyjson.com/products?limit=20');
            
            if ($response->successful()) {
                $data = $response->json();
                $products = $data['products'] ?? [];
                $this->command->info("📦 Found " . count($products) . " products from DummyJSON");
                
                foreach ($products as $productData) {
                    $this->createProductFromDummyJSON($productData);
                }
            } else {
                $this->command->warn('⚠️ Failed to fetch from DummyJSON');
            }
        } catch (\Exception $e) {
            $this->command->warn('⚠️ Error fetching from DummyJSON: ' . $e->getMessage());
        }
    }

    private function createProductFromFakeStore($productData)
    {
        try {
            // Map Fake Store categories to our categories
            $categoryMap = [
                "men's clothing" => 'Clothing',
                "women's clothing" => 'Clothing',
                "jewelery" => 'Jewelry',
                "electronics" => 'Electronics'
            ];
            
            $categoryName = $categoryMap[strtolower($productData['category'])] ?? 'Electronics';
            $category = Category::where('name', $categoryName)->first();
            $supplier = Supplier::inRandomOrder()->first();

            if ($category && $supplier) {
                Product::firstOrCreate(
                    ['name' => $productData['title']],
                    [
                        'sku' => 'FS' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'quantity' => rand(10, 100),
                        'category_id' => $category->id,
                        'supplier_id' => $supplier->id,
                        'image' => $productData['image'], // Direct web URL from Fake Store
                        'status' => 'in_stock',
                        'low_stock_threshold' => 10,
                        'weight' => rand(0.1, 5.0),
                        'dimensions' => json_encode([
                            'length' => rand(5, 50),
                            'width' => rand(5, 50),
                            'height' => rand(5, 50)
                        ])
                    ]
                );
                
                $this->command->info("✅ Created: {$productData['title']}");
            }
        } catch (\Exception $e) {
            $this->command->warn("⚠️ Failed to create product: {$productData['title']} - {$e->getMessage()}");
        }
    }

    private function createProductFromDummyJSON($productData)
    {
        try {
            // Map DummyJSON categories to our categories
            $categoryMap = [
                "smartphones" => 'Electronics',
                "laptops" => 'Electronics',
                "fragrances" => 'Beauty & Health',
                "skincare" => 'Beauty & Health',
                "groceries" => 'Food & Beverages',
                "home-decoration" => 'Home & Garden',
                "furniture" => 'Home & Garden',
                "tops" => 'Clothing',
                "womens-dresses" => 'Clothing',
                "womens-shoes" => 'Clothing',
                "mens-shirts" => 'Clothing',
                "mens-shoes" => 'Clothing',
                "mens-watches" => 'Jewelry',
                "womens-watches" => 'Jewelry',
                "womens-bags" => 'Clothing',
                "womens-jewellery" => 'Jewelry',
                "sunglasses" => 'Clothing',
                "automotive" => 'Automotive',
                "motorcycle" => 'Automotive',
                "lighting" => 'Home & Garden'
            ];
            
            $categoryName = $categoryMap[strtolower($productData['category'])] ?? 'Electronics';
            $category = Category::where('name', $categoryName)->first();
            $supplier = Supplier::inRandomOrder()->first();

            if ($category && $supplier) {
                Product::firstOrCreate(
                    ['name' => $productData['title']],
                    [
                        'sku' => 'DJ' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'quantity' => rand(10, 100),
                        'category_id' => $category->id,
                        'supplier_id' => $supplier->id,
                        'image' => $productData['images'][0] ?? null, // Use first image from array
                        'status' => 'in_stock',
                        'low_stock_threshold' => 10,
                        'weight' => rand(0.1, 5.0),
                        'dimensions' => json_encode([
                            'length' => rand(5, 50),
                            'width' => rand(5, 50),
                            'height' => rand(5, 50)
                        ])
                    ]
                );
                
                $this->command->info("✅ Created: {$productData['title']}");
            }
        } catch (\Exception $e) {
            $this->command->warn("⚠️ Failed to create product: {$productData['title']} - {$e->getMessage()}");
        }
    }

    private function getRandomColor()
    {
        $colors = [
            '#3b82f6', // Blue
            '#10b981', // Green
            '#f59e0b', // Yellow
            '#ef4444', // Red
            '#8b5cf6', // Purple
            '#06b6d4', // Cyan
            '#f97316', // Orange
            '#ec4899', // Pink
            '#84cc16', // Lime
            '#6366f1'  // Indigo
        ];
        
        return $colors[array_rand($colors)];
    }

    private function getCategoryIcon($categoryName)
    {
        $icons = [
            'Electronics' => 'fas fa-laptop',
            'Clothing' => 'fas fa-tshirt',
            'Home & Garden' => 'fas fa-home',
            'Sports & Outdoors' => 'fas fa-running',
            'Books' => 'fas fa-book',
            'Beauty & Health' => 'fas fa-spa',
            'Toys & Games' => 'fas fa-gamepad',
            'Automotive' => 'fas fa-car',
            'Jewelry' => 'fas fa-gem',
            'Food & Beverages' => 'fas fa-utensils'
        ];
        
        return $icons[$categoryName] ?? 'fas fa-box';
    }
} 