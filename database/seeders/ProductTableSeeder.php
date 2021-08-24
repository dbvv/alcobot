<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'image' => 'images/uploads/f04bd0fc5d11d9c62047ec8a91ef4268.jpeg',
                'category_id' => 5,
                'name' => 'Коньяк',
                'description' => 'Описание',
                'price' => 1200,
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
