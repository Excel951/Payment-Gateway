<?php

namespace Database\Seeders;

use App\Models\Product as ModelsProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 10; $i++) { 
            $name = 'Product '.$i;
            $price = random_int(1, 15);
            $description = 'Add a new product';

            ModelsProduct::factory()->create([
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'quantity' => random_int(1, 10),
            ]);
        }
    }
}
