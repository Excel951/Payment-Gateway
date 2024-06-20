<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'price' => '50000',
                'description' => 'This is product 1',
            ],
            [
                'name' => 'Product 2',
                'price' => '150000',
                'description' => 'This is product 2',
            ],
            [
                'name' => 'Product 3',
                'price' => '250000',
                'description' => 'This is product 3',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
