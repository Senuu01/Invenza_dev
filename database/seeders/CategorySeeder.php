<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'color' => '#3b82f6', // Blue
                'icon' => 'fa-microchip',
                'products_count' => 25
            ],
            [
                'name' => 'Furniture',
                'description' => 'Office and home furniture',
                'color' => '#10b981', // Green
                'icon' => 'fa-chair',
                'products_count' => 12
            ],
            [
                'name' => 'Accessories',
                'description' => 'Various accessories and peripherals',
                'color' => '#a855f7', // Purple
                'icon' => 'fa-keyboard',
                'products_count' => 18
            ],
            [
                'name' => 'Software',
                'description' => 'Software licenses and digital products',
                'color' => '#f97316', // Orange
                'icon' => 'fa-compact-disc',
                'products_count' => 8
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
