<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        $products = [
            // Mie Lidi (kering)
            [
                'name' => 'Mie Lidi Kering',
                'variant' => 'Pedas Asin',
                'category' => 'pedas',
                'description' => 'Mie lidi kering dengan kombinasi rasa pedas dan asin.',
                'price' => 12000,
                'discount' => null,
                'image' => 'products/mielidirasapedasasin.jpeg',
            ],
            [
                'name' => 'Mie Lidi Kering',
                'variant' => 'Keju',
                'category' => 'asin',
                'description' => 'Mie lidi kering dengan bumbu keju yang gurih.',
                'price' => 12000,
                'discount' => null,
                'image' => 'products/mielidirasakeju.jpeg',
            ],
            [
                'name' => 'Mie Lidi Kering',
                'variant' => 'Balado',
                'category' => 'pedas',
                'description' => 'Mie lidi kering dengan bumbu balado pedas manis.',
                'price' => 12000,
                'discount' => null,
                'image' => 'products/mielidipedas.png', // Placeholder
            ],
            // Mie Lidi (basah)
            [
                'name' => 'Mie Lidi Basah',
                'variant' => 'Greentea',
                'category' => 'manis',
                'description' => 'Mie lidi basah dengan saus greentea yang unik.',
                'price' => 15000,
                'discount' => null,
                'image' => 'products/mielidirasagreentea.jpeg',
            ],
            [
                'name' => 'Mie Lidi Basah',
                'variant' => 'Coklat',
                'category' => 'manis',
                'description' => 'Mie lidi basah dengan lelehan saus coklat.',
                'price' => 15000,
                'discount' => null,
                'image' => 'products/mielidirasacoklat.jpeg',
            ],
            [
                'name' => 'Mie Lidi Basah',
                'variant' => 'Strawberry',
                'category' => 'manis',
                'description' => 'Mie lidi basah dengan saus strawberry yang segar.',
                'price' => 15000,
                'discount' => null,
                'image' => 'products/mielidibasahstarberry.jpeg',
            ],
            // Cimoring
            [
                'name' => 'Cimoring',
                'variant' => 'Pedas',
                'category' => 'pedas',
                'description' => 'Cireng kering renyah dengan bumbu pedas mantap.',
                'price' => 18000,
                'discount' => null,
                'image' => 'products/cimoring.jpg',
            ],
            [
                'name' => 'Cimoring',
                'variant' => 'Asin',
                'category' => 'asin',
                'description' => 'Cireng kering renyah dengan bumbu asin gurih.',
                'price' => 18000,
                'discount' => null,
                'image' => 'products/cimoring.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name'], 'variant' => $product['variant']],
                $product
            );
        }
    }
}
