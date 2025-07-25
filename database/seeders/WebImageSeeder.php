<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

class WebImageSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🌐 Starting web image seeder...');

        // Create categories if they don't exist
        $this->createCategories();
        
        // Create suppliers if they don't exist
        $this->createSuppliers();
        
        // Add products with web images
        $this->addProductsWithWebImages();

        $this->command->info('✅ Web image seeder completed!');
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

    private function addProductsWithWebImages()
    {
        $this->command->info('📦 Adding products with web images...');

        $products = [
            // Electronics
            [
                'name' => 'MacBook Pro 16-inch',
                'description' => 'Latest MacBook Pro with M3 Pro chip, 16GB RAM, 512GB SSD',
                'price' => 2499.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries',
                'image' => 'https://picsum.photos/400/400?random=1'
            ],
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'Latest iPhone with A17 Pro chip, 48MP camera, titanium design',
                'price' => 1199.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries',
                'image' => 'https://picsum.photos/400/400?random=2'
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android smartphone with S Pen, 200MP camera',
                'price' => 1299.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries',
                'image' => 'https://picsum.photos/400/400?random=3'
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-canceling wireless headphones',
                'price' => 399.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries',
                'image' => 'https://picsum.photos/400/400?random=4'
            ],
            [
                'name' => 'iPad Pro 12.9-inch',
                'description' => 'Professional tablet with M2 chip and Liquid Retina XDR display',
                'price' => 1099.00,
                'category' => 'Electronics',
                'supplier' => 'TechCorp Industries',
                'image' => 'https://picsum.photos/400/400?random=5'
            ],

            // Clothing
            [
                'name' => 'Nike Air Jordan 1 Retro',
                'description' => 'Classic basketball sneakers in Chicago colorway',
                'price' => 170.00,
                'category' => 'Clothing',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=6'
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'description' => 'Premium running shoes with responsive cushioning',
                'price' => 180.00,
                'category' => 'Clothing',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=7'
            ],
            [
                'name' => 'Levi\'s 501 Original Jeans',
                'description' => 'Classic straight-fit jeans in blue denim',
                'price' => 89.00,
                'category' => 'Clothing',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=8'
            ],
            [
                'name' => 'Ray-Ban Aviator Classic',
                'description' => 'Timeless aviator sunglasses with UV protection',
                'price' => 165.00,
                'category' => 'Clothing',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=9'
            ],
            [
                'name' => 'North Face Denali Jacket',
                'description' => 'Warm fleece jacket perfect for outdoor activities',
                'price' => 199.00,
                'category' => 'Clothing',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=10'
            ],

            // Home & Garden
            [
                'name' => 'IKEA MALM Bed Frame',
                'description' => 'Modern queen-size bed frame in white',
                'price' => 299.00,
                'category' => 'Home & Garden',
                'supplier' => 'Home Essentials Co',
                'image' => 'https://picsum.photos/400/400?random=11'
            ],
            [
                'name' => 'KitchenAid Stand Mixer',
                'description' => 'Professional 5-quart stand mixer in red',
                'price' => 399.00,
                'category' => 'Home & Garden',
                'supplier' => 'Home Essentials Co',
                'image' => 'https://picsum.photos/400/400?random=12'
            ],
            [
                'name' => 'Dyson V15 Detect',
                'description' => 'Cordless vacuum with laser dust detection',
                'price' => 699.00,
                'category' => 'Home & Garden',
                'supplier' => 'Home Essentials Co',
                'image' => 'https://picsum.photos/400/400?random=13'
            ],
            [
                'name' => 'Philips Hue Starter Kit',
                'description' => 'Smart lighting system with 3 bulbs and bridge',
                'price' => 199.00,
                'category' => 'Home & Garden',
                'supplier' => 'Home Essentials Co',
                'image' => 'https://picsum.photos/400/400?random=14'
            ],
            [
                'name' => 'Weber Spirit II E-310',
                'description' => '3-burner gas grill with electronic ignition',
                'price' => 449.00,
                'category' => 'Home & Garden',
                'supplier' => 'Home Essentials Co',
                'image' => 'https://picsum.photos/400/400?random=15'
            ],

            // Sports & Outdoors
            [
                'name' => 'Peloton Bike+',
                'description' => 'Premium indoor cycling bike with rotating HD touchscreen',
                'price' => 2495.00,
                'category' => 'Sports & Outdoors',
                'supplier' => 'Sports Gear Pro',
                'image' => 'https://picsum.photos/400/400?random=16'
            ],
            [
                'name' => 'Yeti Tundra 65 Cooler',
                'description' => 'Premium hard cooler with superior ice retention',
                'price' => 399.00,
                'category' => 'Sports & Outdoors',
                'supplier' => 'Sports Gear Pro',
                'image' => 'https://picsum.photos/400/400?random=17'
            ],
            [
                'name' => 'GoPro HERO11 Black',
                'description' => 'Action camera with 5.3K video and 27MP photos',
                'price' => 499.00,
                'category' => 'Sports & Outdoors',
                'supplier' => 'Sports Gear Pro',
                'image' => 'https://picsum.photos/400/400?random=18'
            ],
            [
                'name' => 'Patagonia Down Sweater',
                'description' => 'Lightweight down jacket perfect for outdoor adventures',
                'price' => 229.00,
                'category' => 'Sports & Outdoors',
                'supplier' => 'Sports Gear Pro',
                'image' => 'https://picsum.photos/400/400?random=19'
            ],
            [
                'name' => 'Trek Domane SL 6',
                'description' => 'Carbon road bike with electronic shifting',
                'price' => 3499.00,
                'category' => 'Sports & Outdoors',
                'supplier' => 'Sports Gear Pro',
                'image' => 'https://picsum.photos/400/400?random=20'
            ],

            // Jewelry
            [
                'name' => 'Cartier Love Bracelet',
                'description' => 'Iconic 18k yellow gold bracelet with screw motif',
                'price' => 6500.00,
                'category' => 'Jewelry',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=21'
            ],
            [
                'name' => 'Tiffany & Co. Solitaire Ring',
                'description' => 'Classic 1-carat diamond engagement ring in platinum',
                'price' => 12000.00,
                'category' => 'Jewelry',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=22'
            ],
            [
                'name' => 'Rolex Submariner',
                'description' => 'Luxury diving watch with automatic movement',
                'price' => 8500.00,
                'category' => 'Jewelry',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=23'
            ],
            [
                'name' => 'Pandora Charm Bracelet',
                'description' => 'Sterling silver bracelet with customizable charms',
                'price' => 75.00,
                'category' => 'Jewelry',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=24'
            ],
            [
                'name' => 'David Yurman Cable Bracelet',
                'description' => 'Sterling silver bracelet with 18k gold accents',
                'price' => 395.00,
                'category' => 'Jewelry',
                'supplier' => 'Fashion Forward Ltd',
                'image' => 'https://picsum.photos/400/400?random=25'
            ]
        ];

        foreach ($products as $productData) {
            try {
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
                            'supplier_id' => $supplier->id,
                            'image' => $productData['image'], // Direct web URL
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
            } catch (\Exception $e) {
                $this->command->warn("⚠️  Failed to create product: {$productData['name']} - {$e->getMessage()}");
            }
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