@extends('layouts.app')

@section('styles')
<style>
    .wishlist-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 1px solid #eee;
        border-radius: 0.5rem;
        background-color: #fff;
    }
    .wishlist-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-right: 1rem;
    }
    .wishlist-item-details {
        flex-grow: 1;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Wishlist Saya</h1>
        <p class="text-muted">Produk yang Anda simpan untuk nanti.</p>
    </div>

    @if($wishlist->isEmpty())
        <div class="text-center py-5">
            <h4 class="fw-bold">Wishlist Anda Kosong</h4>
            <p class="text-muted">Simpan produk yang Anda suka dengan menekan tombol hati di halaman produk.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Mulai Belanja</a>
        </div>
    @else
        <div class="row g-3">
            @foreach($wishlist as $item)
            <div class="col-12">
                <div class="wishlist-item">
                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="wishlist-item-img" loading="lazy" decoding="async">
                    <div class="wishlist-item-details">
                        <h5 class="mb-1 fw-bold">
                            <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none text-dark">{{ $item->product->name }}</a>
                        </h5>
                        <p class="mb-1 text-muted">{{ $item->product->variant }}</p>
                        <p class="mb-0 fw-bold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="ms-auto d-flex align-items-center">
                        <form action="{{ route('cart.add', $item->product->id) }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">+ Keranjang</button>
                        </form>
                        <form action="{{ route('wishlist.destroy', $item->product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus dari wishlist">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
