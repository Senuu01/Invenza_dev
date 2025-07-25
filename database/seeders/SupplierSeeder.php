<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'TechCorp Ltd.',
                'email' => 'contact@techcorp.com',
                'phone' => '+1 555-0123',
                'address' => '123 Tech Street',
                'city' => 'Silicon Valley',
                'state' => 'CA',
                'country' => 'USA',
                'products_count' => 15,
                'status' => 'active'
            ],
            [
                'name' => 'ComfortDesk Inc.',
                'email' => 'sales@comfortdesk.com',
                'phone' => '+1 555-0456',
                'address' => '456 Furniture Ave',
                'city' => 'Design City',
                'state' => 'NY',
                'country' => 'USA',
                'products_count' => 8,
                'status' => 'active'
            ],
            [
                'name' => 'WorkSpace Pro',
                'email' => 'orders@workspacepro.com',
                'phone' => '+1 555-0789',
                'address' => '789 Office Blvd',
                'city' => 'Business Park',
                'state' => 'TX',
                'country' => 'USA',
                'products_count' => 12,
                'status' => 'active'
            ],
            [
                'name' => 'PeripheralPro',
                'email' => 'support@peripheralpro.com',
                'phone' => '+1 555-0321',
                'address' => '321 Device Street',
                'city' => 'Tech Hub',
                'state' => 'WA',
                'country' => 'USA',
                'products_count' => 6,
                'status' => 'inactive'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
