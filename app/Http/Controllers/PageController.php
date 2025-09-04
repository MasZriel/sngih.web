<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    // Data untuk 3 produk unggulan yang akan tampil di halaman home
    private $featuredProducts = [
        [
            'id' => 1, 'name' => 'Mie Lidi Pedas Asin', 'price' => 'Rp 10.000',
            'description' => 'Rasa pedas asin klasik yang selalu jadi favorit.',
            'image' => 'https://via.placeholder.com/600x400.png/F5EFE6/78543E?text=Pedas+Asin',
        ],
        [
            'id' => 3, 'name' => 'Mie Lidi Balado', 'price' => 'Rp 10.000',
            'description' => 'Sensasi pedas manis bumbu balado yang khas.',
            'image' => 'https://via.placeholder.com/600x400.png/F5EFE6/78543E?text=Balado',
        ],
        [
            'id' => 5, 'name' => 'Mie Lidi Coklat', 'price' => 'Rp 12.000',
            'description' => 'Lumeran saus coklat premium yang manis dan legit.',
            'image' => 'https://via.placeholder.com/600x400.png/F5EFE6/78543E?text=Coklat',
        ],
    ];

    public function home()
    {
        return view('home', ['featuredProducts' => $this->featuredProducts]);
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
