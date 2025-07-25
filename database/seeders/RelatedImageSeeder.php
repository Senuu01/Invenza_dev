<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class RelatedImageSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🖼️ Starting related image seeder...');

        // Update existing products with related images
        $this->updateProductImages();

        $this->command->info('✅ Related image seeder completed!');
    }

    private function updateProductImages()
    {
        $this->command->info('📦 Updating product images to be product-specific...');

        // Electronics - Real product images
        $this->updateElectronicsImages();
        
        // Clothing - Real product images
        $this->updateClothingImages();
        
        // Jewelry - Real product images
        $this->updateJewelryImages();
        
        // Home & Garden - Real product images
        $this->updateHomeGardenImages();
        
        // Sports & Outdoors - Real product images
        $this->updateSportsImages();
        
        // Beauty & Health - Real product images
        $this->updateBeautyImages();
        
        // Food & Beverages - Real product images
        $this->updateFoodImages();
    }

    private function updateElectronicsImages()
    {
        $electronicsProducts = Product::whereHas('category', function($query) {
            $query->where('name', 'Electronics');
        })->get();

        $electronicsImages = [
            // Smartphones
            'iPhone' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=400&fit=crop',
            'Samsung' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=400&h=400&fit=crop',
            'Galaxy' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=400&h=400&fit=crop',
            
            // Laptops
            'MacBook' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop',
            'Dell' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400&h=400&fit=crop',
            'Laptop' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=400&fit=crop',
            
            // Tablets
            'iPad' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400&h=400&fit=crop',
            'Tablet' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400&h=400&fit=crop',
            
            // Monitors
            'Monitor' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400&h=400&fit=crop',
            'Acer' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400&h=400&fit=crop',
            
            // Headphones
            'Headphones' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop',
            'Sony' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop',
            
            // Storage
            'SSD' => 'https://images.unsplash.com/photo-1597872200969-74b51c24e8a5?w=400&h=400&fit=crop',
            'Hard Drive' => 'https://images.unsplash.com/photo-1597872200969-74b51c24e8a5?w=400&h=400&fit=crop',
            'WD' => 'https://images.unsplash.com/photo-1597872200969-74b51c24e8a5?w=400&h=400&fit=crop',
            'SanDisk' => 'https://images.unsplash.com/photo-1597872200969-74b51c24e8a5?w=400&h=400&fit=crop',
            
            // Default electronics
            'default' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&h=400&fit=crop'
        ];

        foreach ($electronicsProducts as $product) {
            $imageUrl = $this->findMatchingImage($product->name, $electronicsImages);
            $product->update(['image' => $imageUrl]);
            $this->command->info("✅ Updated: {$product->name}");
        }
    }

    private function updateClothingImages()
    {
        $clothingProducts = Product::whereHas('category', function($query) {
            $query->where('name', 'Clothing');
        })->get();

        $clothingImages = [
            // Shoes
            'Nike' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop',
            'Adidas' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=400&h=400&fit=crop',
            'Jordan' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=400&h=400&fit=crop',
            'Shoes' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop',
            
            // Jeans
            'Levi' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop',
            'Jeans' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop',
            
            // Jackets
            'Jacket' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop',
            'North Face' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop',
            
            // T-Shirts
            'T-Shirt' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop',
            'T Shirt' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop',
            
            // Sunglasses
            'Ray-Ban' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop',
            'Sunglasses' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop',
            
            // Women's clothing
            'Women' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=400&fit=crop',
            'Dress' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=400&fit=crop',
            
            // Default clothing
            'default' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=400&fit=crop'
        ];

        foreach ($clothingProducts as $product) {
            $imageUrl = $this->findMatchingImage($product->name, $clothingImages);
            $product->update(['image' => $imageUrl]);
            $this->command->info("✅ Updated: {$product->name}");
        }
    }

    private function updateJewelryImages()
    {
        $jewelryProducts = Product::whereHas('category', function($query) {
            $query->where('name', 'Jewelry');
        })->get();

        $jewelryImages = [
            // Bracelets
            'Bracelet' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=400&h=400&fit=crop',
            'John Hardy' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=400&h=400&fit=crop',
            
            // Rings
            'Ring' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=400&h=400&fit=crop',
            'Gold' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=400&h=400&fit=crop',
            'Princess' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=400&h=400&fit=crop',
            
            // Earrings
            'Earrings' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=400&h=400&fit=crop',
            'Owl' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=400&h=400&fit=crop',
            
            // Watches
            'Watch' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=400&h=400&fit=crop',
            'Rolex' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=400&h=400&fit=crop',
            
            // Necklaces
            'Necklace' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=400&h=400&fit=crop',
            
            // Default jewelry
            'default' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&h=400&fit=crop'
        ];

        foreach ($jewelryProducts as $product) {
            $imageUrl = $this->findMatchingImage($product->name, $jewelryImages);
            $product->update(['image' => $imageUrl]);
            $this->command->info("✅ Updated: {$product->name}");
        }
    }

    private function updateHomeGardenImages()
    {
        $homeProducts = Product::whereHas('category', function($query) {
            $query->where('name', 'Home & Garden');
        })->get();

        $homeImages = [
            // Furniture
            'Bed' => 'https://images.unsplash.com/photo-1505693314120-0d443867891c?w=400&h=400&fit=crop',
            'Sofa' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&h=400&fit=crop',
            'Chair' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=400&h=400&fit=crop',
            'Table' => 'https://images.unsplash.com/photo-1533090481720-856c6e3c1fdc?w=400&h=400&fit=crop',
            
            // Kitchen
            'KitchenAid' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
            'Mixer' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
            
            // Cleaning
            'Dyson' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop',
            'Vacuum' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop',
            
            // Lighting
            'Philips Hue' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&h=400&fit=crop',
            'Light' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&h=400&fit=crop',
            
            // Outdoor
            'Weber' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
            'Grill' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
            
            // Default home
            'default' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=400&fit=crop'
        ];

        foreach ($homeProducts as $product) {
            $imageUrl = $this->findMatchingImage($product->name, $homeImages);
            $product->update(['image' => $imageUrl]);
            $this->command->info("✅ Updated: {$product->name}");
        }
    }

    private function updateSportsImages()
    {
        $sportsProducts = Product::whereHas('category', function($query) {
            $query->where('name', 'Sports & Outdoors');
        })->get();

        $sportsImages = [
            // Bikes
            'Peloton' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop',
            'Bike' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop',
            'Trek' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop',
            
            // Coolers
            'Yeti' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
            'Cooler' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
            
            // Cameras
            'GoPro' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=400&h=400&fit=crop',
            'Camera' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=400&h=400&fit=crop',
            
            // Outdoor gear
            'Patagonia' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop',
            'Jacket' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop',
            
            // Default sports
            'default' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop'
        ];

        foreach ($sportsProducts as $product) {
            $imageUrl = $this->findMatchingImage($product->name, $sportsImages);
            $product->update(['image' => $imageUrl]);
            $this->command->info("✅ Updated: {$product->name}");
        }
    }

    private function updateBeautyImages()
    {
        $beautyProducts = Product::whereHas('category', function($query) {
            $query->where('name', 'Beauty & Health');
        })->get();

        $beautyImages = [
            // Makeup
            'Mascara' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=400&fit=crop',
            'Lipstick' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop',
            'Nail Polish' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=400&h=400&fit=crop',
            'Eyeshadow' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=400&fit=crop',
            
            // Fragrances
            'Calvin Klein' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400&h=400&fit=crop',
            'Chanel' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400&h=400&fit=crop',
            'Dior' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400&h=400&fit=crop',
            'Gucci' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400&h=400&fit=crop',
            
            // Skincare
            'Skincare' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=400&fit=crop',
            'Powder' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=400&fit=crop',
            
            // Default beauty
            'default' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=400&fit=crop'
        ];

        foreach ($beautyProducts as $product) {
            $imageUrl = $this->findMatchingImage($product->name, $beautyImages);
            $product->update(['image' => $imageUrl]);
            $this->command->info("✅ Updated: {$product->name}");
        }
    }

    private function updateFoodImages()
    {
        $foodProducts = Product::whereHas('category', function($query) {
            $query->where('name', 'Food & Beverages');
        })->get();

        $foodImages = [
            // Fruits
            'Apple' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=400&h=400&fit=crop',
            
            // Meat
            'Beef' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400&h=400&fit=crop',
            'Chicken' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?w=400&h=400&fit=crop',
            
            // Pet food
            'Cat Food' => 'https://images.unsplash.com/photo-1601758228041-3caa61d9c6e8?w=400&h=400&fit=crop',
            
            // Cooking
            'Cooking Oil' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
            
            // Default food
            'default' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=400&fit=crop'
        ];

        foreach ($foodProducts as $product) {
            $imageUrl = $this->findMatchingImage($product->name, $foodImages);
            $product->update(['image' => $imageUrl]);
            $this->command->info("✅ Updated: {$product->name}");
        }
    }

    private function findMatchingImage($productName, $imageMap)
    {
        $productName = strtolower($productName);
        
        foreach ($imageMap as $keyword => $imageUrl) {
            if ($keyword === 'default') continue;
            
            if (str_contains($productName, strtolower($keyword))) {
                return $imageUrl;
            }
        }
        
        return $imageMap['default'] ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=400&fit=crop';
    }
} 