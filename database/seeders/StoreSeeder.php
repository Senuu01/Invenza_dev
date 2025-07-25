<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Starting comprehensive store population...');

        // Create categories first
        $this->createCategories();
        
        // Create suppliers
        $this->createSuppliers();
        
        // Populate with real data
        $this->populateWithRealData();
        
        // Add some custom products
        $this->addCustomProducts();

        $this->command->info('✅ Store population completed!');
    }

    private function createCategories()
    {
        $this->command->info('📂 Creating categories...');

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

        $this->command->info('✅ Categories created successfully!');
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

    private function createSuppliers()
    {
        $this->command->info('🏢 Creating suppliers...');

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
                $this->command->warn("⚠️  Supplier {$supplierData['name']} might already exist, skipping...");
            }
        }

        $this->command->info('✅ Suppliers created successfully!');
    }

    private function populateWithRealData()
    {
        $this->command->info('📡 Fetching real product data...');

        try {
            // Try Fake Store API first
            $this->populateFromFakeStore();
        } catch (\Exception $e) {
            $this->command->warn('⚠️  Fake Store API failed, trying DummyJSON...');
            try {
                $this->populateFromDummyJSON();
            } catch (\Exception $e2) {
                $this->command->warn('⚠️  Both APIs failed, using fallback data...');
                $this->populateWithFallbackData();
            }
        }
    }

    private function populateFromFakeStore()
    {
        $response = Http::timeout(30)->get('https://fakestoreapi.com/products');
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch from Fake Store API');
        }

        $products = $response->json();
        $supplier = Supplier::where('name', 'TechCorp Industries')->first();

        $this->command->info("📦 Importing " . count($products) . " products from Fake Store...");

        foreach ($products as $productData) {
            $this->createProductFromData($productData, $supplier);
        }
    }

    private function populateFromDummyJSON()
    {
        $response = Http::timeout(30)->get('https://dummyjson.com/products?limit=30');
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch from DummyJSON API');
        }

        $data = $response->json();
        $products = $data['products'];
        $supplier = Supplier::where('name', 'TechCorp Industries')->first();

        $this->command->info("📦 Importing " . count($products) . " products from DummyJSON...");

        foreach ($products as $productData) {
            $this->createProductFromData($productData, $supplier);
        }
    }

    private function createProductFromData($productData, $supplier)
    {
        try {
            // Find or create category
            $categoryName = ucfirst($productData['category'] ?? 'Uncategorized');
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'description' => "Products in the {$categoryName} category",
                    'color' => $this->getRandomColor(),
                    'icon' => $this->getCategoryIcon($categoryName)
                ]
            );

            // Download image
            $imageUrl = $productData['image'] ?? $productData['images'][0] ?? null;
            $imagePath = $imageUrl ? $this->downloadImage($imageUrl, $productData['title']) : null;

                            // Create product
                Product::firstOrCreate(
                    ['name' => $productData['title']],
                    [
                        'sku' => 'SKU' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                        'description' => $productData['description'] ?? 'Product description not available.',
                        'price' => $productData['price'] ?? rand(10, 500),
                        'quantity' => $productData['stock'] ?? rand(10, 100),
                        'category_id' => $category->id,
                        'supplier_id' => $supplier ? $supplier->id : null,
                        'image' => $imagePath,
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
        } catch (\Exception $e) {
            $this->command->warn("⚠️  Failed to create product: {$productData['title']} - {$e->getMessage()}");
        }
    }

    private function downloadImage($imageUrl, $productName)
    {
        try {
            $response = Http::timeout(30)->get($imageUrl);
            
            if (!$response->successful()) {
                return null;
            }

            $imageContent = $response->body();
            $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = Str::slug($productName) . '_' . time() . '.' . $extension;
            $path = 'products/' . $filename;

            Storage::disk('public')->put($path, $imageContent);

            return $path;
        } catch (\Exception $e) {
            $this->command->warn("⚠️  Failed to download image for {$productName}");
            return null;
        }
    }

    private function populateWithFallbackData()
    {
        $this->command->info('📦 Creating fallback products...');

        $fallbackProducts = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced camera system and A17 Pro chip',
                'price' => 999.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries'
            ],
            [
                'name' => 'MacBook Pro 14-inch',
                'description' => 'Powerful laptop for professionals with M3 chip',
                'price' => 2499.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries'
            ],
            [
                'name' => 'Samsung 55" QLED TV',
                'description' => '4K Smart TV with Quantum Dot technology',
                'price' => 1299.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries'
            ],
            [
                'name' => 'Nike Air Max 270',
                'description' => 'Comfortable running shoes with Air Max technology',
                'price' => 150.00,
                'category' => 'Sports & Outdoors',
                'supplier' => 'Sports Gear Pro'
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'description' => 'Premium running shoes with responsive cushioning',
                'price' => 180.00,
                'category' => 'Sports & Outdoors',
                'supplier' => 'Sports Gear Pro'
            ],
            [
                'name' => 'Levi\'s 501 Original Jeans',
                'description' => 'Classic straight-fit jeans in blue denim',
                'price' => 89.00,
                'category' => 'Clothing',
                'supplier' => 'Fashion Forward Ltd'
            ],
            [
                'name' => 'Ray-Ban Aviator Classic',
                'description' => 'Timeless aviator sunglasses with UV protection',
                'price' => 165.00,
                'category' => 'Clothing',
                'supplier' => 'Fashion Forward Ltd'
            ],
            [
                'name' => 'IKEA MALM Bed Frame',
                'description' => 'Modern queen-size bed frame in white',
                'price' => 299.00,
                'category' => 'Home & Garden',
                'supplier' => 'Home Essentials Co'
            ],
            [
                'name' => 'KitchenAid Stand Mixer',
                'description' => 'Professional 5-quart stand mixer in red',
                'price' => 399.00,
                'category' => 'Home & Garden',
                'supplier' => 'Home Essentials Co'
            ],
            [
                'name' => 'Diamond Tennis Bracelet',
                'description' => 'Elegant 14k white gold bracelet with diamonds',
                'price' => 2500.00,
                'category' => 'Jewelry',
                'supplier' => 'Fashion Forward Ltd'
            ]
        ];

        foreach ($fallbackProducts as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            $supplier = Supplier::where('name', $productData['supplier'])->first();

            if ($category && $supplier) {
                Product::firstOrCreate(
                    ['name' => $productData['name']],
                    [
                        'sku' => 'SKU' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'quantity' => rand(10, 100),
                        'category_id' => $category->id,
                        'supplier_id' => $supplier ? $supplier->id : null,
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
            }
        }
    }

    private function addCustomProducts()
    {
        $this->command->info('🎨 Adding custom products...');

        // Add some premium products
        $premiumProducts = [
            [
                'name' => 'Apple Vision Pro',
                'description' => 'Revolutionary spatial computing device with advanced AR/VR capabilities',
                'price' => 3499.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries'
            ],
            [
                'name' => 'Tesla Model 3',
                'description' => 'Electric vehicle with autopilot and long-range battery',
                'price' => 45000.00,
                'category' => 'Automotive',
                'supplier' => 'TechCorp Industries'
            ],
            [
                'name' => 'Rolex Submariner',
                'description' => 'Luxury diving watch with automatic movement',
                'price' => 8500.00,
                'category' => 'Jewelry',
                'supplier' => 'Fashion Forward Ltd'
            ]
        ];

        foreach ($premiumProducts as $productData) {
            $category = Category::firstOrCreate(
                ['name' => $productData['category']],
                [
                    'description' => "Products in the {$productData['category']} category",
                    'color' => $this->getRandomColor(),
                    'icon' => $this->getCategoryIcon($productData['category'])
                ]
            );
            
            $supplier = Supplier::where('name', $productData['supplier'])->first();

            if ($supplier) {
                Product::firstOrCreate(
                    ['name' => $productData['name']],
                    [
                        'sku' => 'SKU' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'quantity' => rand(1, 10), // Limited stock for premium items
                        'category_id' => $category->id,
                        'supplier_id' => $supplier ? $supplier->id : null,
                        'status' => 'in_stock',
                        'low_stock_threshold' => 5,
                        'weight' => rand(0.5, 10.0),
                        'dimensions' => json_encode([
                            'length' => rand(10, 100),
                            'width' => rand(10, 100),
                            'height' => rand(10, 100)
                        ])
                    ]
                );
            }
        }
    }
} 