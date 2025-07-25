<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PopulateStoreCommand extends Command
{
    protected $signature = 'store:populate {--source=fakestore : Data source (fakestore, dummyjson)} {--limit=20 : Number of products to import}';
    protected $description = 'Populate store with real product data from external APIs';

    public function handle()
    {
        $source = $this->option('source');
        $limit = $this->option('limit');

        $this->info("🚀 Starting store population from {$source}...");
        $this->info("📦 Importing up to {$limit} products...");

        try {
            switch ($source) {
                case 'fakestore':
                    $this->populateFromFakeStore($limit);
                    break;
                case 'dummyjson':
                    $this->populateFromDummyJSON($limit);
                    break;
                default:
                    $this->error("❌ Unknown source: {$source}");
                    return 1;
            }

            $this->info("✅ Store population completed successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }

    private function populateFromFakeStore($limit)
    {
        $this->info("📡 Fetching data from Fake Store API...");

        // Fetch products
        $response = Http::get('https://fakestoreapi.com/products');
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch data from Fake Store API');
        }

        $products = $response->json();
        $products = array_slice($products, 0, $limit);

        $this->info("📊 Found " . count($products) . " products to import");

        // Create default supplier
        $supplier = Supplier::firstOrCreate(
            ['name' => 'Fake Store Supplier'],
            [
                'email' => 'supplier@fakestore.com',
                'phone' => '+1-555-0123',
                'address' => '123 Fake Street, Fake City, FC 12345',
                'status' => 'active'
            ]
        );

        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $productData) {
            try {
                // Create or find category
                $category = Category::firstOrCreate(
                    ['name' => ucfirst($productData['category'])],
                    [
                        'description' => "Products in the {$productData['category']} category",
                        'status' => 'active'
                    ]
                );

                // Download and store image
                $imagePath = $this->downloadAndStoreImage($productData['image'], $productData['title']);

                // Create product
                Product::create([
                    'name' => $productData['title'],
                    'sku' => 'SKU' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'quantity' => rand(10, 100), // Random stock
                    'category_id' => $category->id,
                    'supplier_id' => $supplier->id,
                    'image' => $imagePath,
                    'status' => 'in_stock',
                    'low_stock_threshold' => 10,
                    'cost_price' => $productData['price'] * 0.7, // 30% markup
                    'weight' => rand(0.1, 5.0),
                    'dimensions' => json_encode([
                        'length' => rand(5, 50),
                        'width' => rand(5, 50),
                        'height' => rand(5, 50)
                    ])
                ]);

                $bar->advance();
            } catch (\Exception $e) {
                $this->warn("\n⚠️  Failed to import product: {$productData['title']} - {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
    }

    private function populateFromDummyJSON($limit)
    {
        $this->info("📡 Fetching data from DummyJSON API...");

        // Fetch products
        $response = Http::get("https://dummyjson.com/products?limit={$limit}");
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch data from DummyJSON API');
        }

        $data = $response->json();
        $products = $data['products'];

        $this->info("📊 Found " . count($products) . " products to import");

        // Create default supplier
        $supplier = Supplier::firstOrCreate(
            ['name' => 'DummyJSON Supplier'],
            [
                'email' => 'supplier@dummyjson.com',
                'phone' => '+1-555-0456',
                'address' => '456 Dummy Avenue, Dummy Town, DT 67890',
                'status' => 'active'
            ]
        );

        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $productData) {
            try {
                // Create or find category
                $category = Category::firstOrCreate(
                    ['name' => ucfirst($productData['category'])],
                    [
                        'description' => "Products in the {$productData['category']} category",
                        'status' => 'active'
                    ]
                );

                // Download and store image
                $imagePath = $this->downloadAndStoreImage($productData['images'][0], $productData['title']);

                // Create product
                Product::create([
                    'name' => $productData['title'],
                    'sku' => 'SKU' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'quantity' => $productData['stock'],
                    'category_id' => $category->id,
                    'supplier_id' => $supplier->id,
                    'image' => $imagePath,
                    'status' => $productData['stock'] > 0 ? 'in_stock' : 'out_of_stock',
                    'low_stock_threshold' => 10,
                    'cost_price' => $productData['price'] * 0.7, // 30% markup
                    'weight' => rand(0.1, 5.0),
                    'dimensions' => json_encode([
                        'length' => rand(5, 50),
                        'width' => rand(5, 50),
                        'height' => rand(5, 50)
                    ])
                ]);

                $bar->advance();
            } catch (\Exception $e) {
                $this->warn("\n⚠️  Failed to import product: {$productData['title']} - {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
    }

    private function downloadAndStoreImage($imageUrl, $productName)
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
            $this->warn("⚠️  Failed to download image for {$productName}: {$e->getMessage()}");
            return null;
        }
    }
} 