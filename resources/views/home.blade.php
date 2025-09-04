@extends('layouts.app')

@section('styles')
<style>
    .hero-section {
        background: var(--surface-color);
        padding: 6rem 0;
        text-align: center;
    }
    .hero-section h1 {
        font-size: 2.5rem; /* Smaller font size for mobile */
        font-weight: 700;
        color: var(--secondary-color);
    }

    @media (min-width: 768px) { /* Apply larger font size for md screens and up */
        .hero-section h1 {
            font-size: 3.5rem;
        }
    }
    .hero-section p {
        font-size: 1.25rem;
        color: #6c757d;
        margin-bottom: 2rem;
    }
    .hero-search {
        max-width: 600px;
        margin: auto;
        position: relative; /* Needed for absolute positioning of results */
    }
    .categories-section {
        padding: 4rem 0;
    }
    .category-card {
        text-align: center;
        padding: 1.5rem;
        border: 1px solid #eee;
        border-radius: 12px;
        background: var(--surface-color);
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.07);
    }
    .category-card img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-left: auto;
        margin-right: auto;
    }
    .product-card {
        background: var(--surface-color);
        border: 1px solid #eee;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .product-card-img-top {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .product-card-body {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-card-title {
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 1.1rem;
    }

    .product-card-text {
        color: #6c757d;
        flex-grow: 1;
    }
    
    .product-card-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .fs-sm {
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="hero-section" data-aos="fade-up">
    <div class="container">
        <h1>Lapar? Pesan aja di Snagih!</h1>
        <p>Temukan dan pesan makanan ringan favoritmu dari berbagai rasa.</p>
        <div class="hero-search">
            <form action="{{ route('products.index') }}" method="GET" id="hero-search-form">
                <div class="input-group">
                    <input type="text" name="search" id="hero-search-input" class="form-control form-control-lg search-input" placeholder="Cari rasa original, pedas, keju..." value="{{ request('search') }}" autocomplete="off">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
            <div id="hero-live-search-results" class="live-search-results-container d-none"></div>
        </div>
    </div>
</div>

<section class="categories-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-down">Jelajahi Kategori</h2>
        <div class="row">
            <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="category-card">
                    <img src="{{ asset('storage/products/mielidipedas.png') }}" alt="Rasa Pedas">
                    <h5 class="fw-bold mt-2">Rasa Pedas</h5>
                    <a href="{{ route('products.index', ['category' => 'pedas']) }}" class="btn btn-sm btn-outline-primary mt-auto">Jelajahi</a>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="category-card">
                    <img src="{{ asset('storage/products/cimoring.jpg') }}" alt="Rasa Asin">
                    <h5 class="fw-bold mt-2">Rasa Asin</h5>
                    <a href="{{ route('products.index', ['category' => 'asin']) }}" class="btn btn-sm btn-outline-primary mt-auto">Jelajahi</a>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="category-card">
                    <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?q=80&w=1089&auto=format&fit=crop" alt="Rasa Manis">
                    <h5 class="fw-bold mt-2">Rasa Manis</h5>
                    <a href="{{ route('products.index', ['category' => 'manis']) }}" class="btn btn-sm btn-outline-primary mt-auto">Jelajahi</a>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="category-card">
                    <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?q=80&w=1074&auto=format&fit=crop" alt="Semua Produk">
                    <h5 class="fw-bold mt-2">Semua Produk</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary mt-auto">Jelajahi</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Best Sellers Section -->
<section class="best-sellers-section py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-down">Produk Terlaris</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @if(isset($bestSellers) && $bestSellers->count() > 0)
                @foreach($bestSellers as $product)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                        <div class="product-card h-100">
                            <img src="{{ asset('storage/' . $product->image) }}" class="product-card-img-top" alt="{{ $product->name }}" loading="lazy" decoding="async">
                            <div class="product-card-body">
                                <h5 class="product-card-title text-dark">{{ $product->name }}</h5>
                                <p class="text-muted fw-bold">{{ $product->variant }}</p>
                                <div class="mt-auto">
                                    <p class="product-card-price mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <button class="btn btn-primary w-100 mt-2">Lihat Detail</button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-center text-muted">Belum ada produk terlaris.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Promotions Section -->
<section class="promotions-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-down">Promo Spesial</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @if(isset($promotionalProducts) && $promotionalProducts->count() > 0)
                @foreach($promotionalProducts as $product)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                        <div class="product-card h-100">
                            <span class="position-absolute top-0 start-0 bg-danger text-white py-1 px-2" style="border-bottom-right-radius: 0.5rem; z-index: 1;">SALE</span>
                            <img src="{{ asset('storage/' . $product->image) }}" class="product-card-img-top" alt="{{ $product->name }}" loading="lazy" decoding="async">
                            <div class="product-card-body">
                                <h5 class="product-card-title text-dark">{{ $product->name }}</h5>
                                <p class="text-muted fw-bold">{{ $product->variant }}</p>
                                <div class="mt-auto">
                                    <p class="product-card-price mb-0">
                                        <span class="text-danger">Rp {{ number_format($product->harga_diskon, 0, ',', '.') }}</span>
                                        <s class="text-muted fs-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</s>
                                    </p>
                                    <button class="btn btn-primary w-100 mt-2">Lihat Detail</button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-center text-muted">Saat ini tidak ada promo.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="about-us-shortcut py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0" data-aos="fade-right">
                <img src="{{ asset('images/ft2.JPG') }}" class="img-fluid rounded shadow" alt="Tim Snagih">
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <h2 class="fw-bold">Dari Kami, untuk Anda</h2>
                <p class="lead text-muted">Snagih adalah hasil kerja keras tim kecil yang bersemangat menghadirkan camilan terbaik. Kami percaya pada kualitas dan rasa yang otentik.</p>
                <a href="{{ route('about') }}" class="btn btn-primary mt-3">Kenali Cerita Kami &rarr;</a>
            </div>
        </div>
    </div>
</section>

@endsection


