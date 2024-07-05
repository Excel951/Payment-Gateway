<?php

namespace Database\Seeders;

use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        function getrandomphoto()
        {
            $client = new Client();
            $accesskey = env('UNSPLASH_ACCESS_KEY');

            $response = $client->get('https://api.unsplash.com/photos/random', [
                'headers' => [
                    'Authorization' => 'Client-ID ' . $accesskey,
                ]
            ]);

            $json = json_decode($response->getBody()->getContents(), true);

            return str($json['urls']['regular']);
        }

        for ($i=0; $i < 10; $i++) { 
            $name = 'Product '.$i;
            $price = random_int(1, 15)*10000;
            $description = 'TEST PRODUCTION';
            $image_url = 

            Product::factory()->create([
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'quantity' => random_int(1, 10),
                'image_url' => getrandomphoto()
            ]);
        }
    }
}
