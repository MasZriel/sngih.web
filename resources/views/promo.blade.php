@extends('layouts.app')

@section('styles')
<style>
    .page-header {
        padding: 2rem 0;
        text-align: center;
        background-color: var(--surface-color);
        margin-bottom: 2rem;
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
<div class="container py-4">
    <div class="page-header" data-aos="fade-down">
        <h1 class="fw-bold">Produk Promo</h1>
        <p class="text-muted">Nikmati penawaran spesial untuk produk-produk pilihan kami!</p>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5" data-aos="fade-up">
            <h4 class="fw-bold">Belum Ada Promo</h4>
            <p class="text-muted">Saat ini belum ada produk yang sedang promo. Silakan cek kembali nanti!</p>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">Lihat Semua Produk</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($products as $product)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                    <div class="product-card h-100">
                        <span class="position-absolute top-0 start-0 bg-danger text-white py-1 px-2" style="border-bottom-right-radius: 0.5rem; z-index: 1;">SALE</span>
                        <img src="{{ asset('storage/' . $product->image) }}" class="product-card-img-top" alt="{{ $product->name }}">
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
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
