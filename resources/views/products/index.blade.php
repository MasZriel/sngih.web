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
    
    .product-card-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .fs-sm {
        font-size: 0.875rem;
    }

    #modal-product-description-details {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="page-header" data-aos="fade-down">
        <h1 class="fw-bold">
            @if(isset($variant) && $variant)
                Produk Varian: {{ ucfirst($variant) }}
            @elseif(isset($search) && $search)
                Hasil Pencarian untuk: "{{ $search }}"
            @else
                Semua Produk Snagih
            @endif
        </h1>
        <p class="text-muted">Pilih dan nikmati semua varian rasa yang kami tawarkan!</p>
    </div>

    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3" data-aos="fade-right">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Filter & Urutkan</h5>
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Sort by -->
                        <div class="mb-3">
                            <label for="sort_by" class="form-label fw-semibold">Urutkan</label>
                            <select name="sort_by" id="sort_by" class="form-select">
                                <option value="newest" {{ ($sortBy ?? 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ ($sortBy ?? '') == 'popular' ? 'selected' : '' }}>Terlaris</option>
                                <option value="price_low_high" {{ ($sortBy ?? '') == 'price_low_high' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_high_low" {{ ($sortBy ?? '') == 'price_high_low' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </div>

                        <!-- Filter by Variant -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Varian Rasa</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="variant" id="variant-all" value="" {{ !$variant ? 'checked' : '' }}>
                                <label class="form-check-label" for="variant-all">Semua</label>
                            </div>
                            @if(isset($variants))
                                @foreach($variants as $var)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="variant" id="variant-{{ $loop->index }}" value="{{ $var }}" {{ $variant == $var ? 'checked' : '' }}>
                                    <label class="form-check-label" for="variant-{{ $loop->index }}">{{ ucfirst($var) }}</label>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Filter by Price -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rentang Harga</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ $minPrice ?? '' }}">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ $maxPrice ?? '' }}">
                            </div>
                        </div>
                        
                        <!-- Search -->
                        <div class="mb-3">
                             <label for="search" class="form-label fw-semibold">Pencarian</label>
                             <input type="text" name="search" id="search" class="form-control" placeholder="Nama produk..." value="{{ $search ?? '' }}">
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">Terapkan Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9" data-aos="fade-up">
            @if($products->isEmpty())
                <div class="text-center py-5">
                    <h4 class="fw-bold">Produk Tidak Ditemukan</h4>
                    <p class="text-muted">Coba gunakan filter lain atau lihat semua produk kami.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">Hapus Filter & Lihat Semua</a>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($products as $product)
                    <div class="col" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                        <div class="product-card h-100">
                            @if($product->harga_diskon && $product->harga_diskon > 0)
                                <span class="position-absolute top-0 start-0 bg-danger text-white py-1 px-2" style="border-bottom-right-radius: 0.5rem; z-index: 1;">SALE</span>
                            @endif
                            <img src="{{ asset('storage/' . $product->image) }}" class="product-card-img-top" alt="{{ $product->name }}" loading="lazy" decoding="async">
                            <div class="product-card-body">
                                <h5 class="product-card-title text-dark">{{ $product->name }}</h5>
                                <p class="text-muted fw-bold">{{ $product->variant }}</p>
                                <div class="mt-auto">
                                    @if($product->harga_diskon && $product->harga_diskon > 0)
                                        <p class="product-card-price mb-0">
                                            <span class="text-danger">Rp {{ number_format($product->harga_diskon, 0, ',', '.') }}</span>
                                            <s class="text-muted fs-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</s>
                                        </p>
                                    @else
                                        <p class="product-card-price mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    @endif
                                    <button class="btn btn-primary w-100 mt-2 quick-view-btn" data-product-id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#quickViewModal">Lihat</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4 pt-0">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <img src="" id="modal-product-image" class="img-fluid rounded shadow-sm" alt="Product Image" style="width: 100%; height: 350px; object-fit: cover;">
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <h3 class="fw-bold" id="modal-product-name"></h3>
                        <div class="mb-2">
                            <span id="modal-product-price" class="fs-4 fw-bold text-primary"></span>
                            <span id="modal-product-original-price" class="text-muted text-decoration-line-through ms-2"></span>
                        </div>
                        <div class="d-flex gap-3 fs-sm text-muted mb-3">
                            <span><strong>Kategori:</strong> <span id="modal-product-category"></span></span>
                            <span><strong>Varian:</strong> <span id="modal-product-variant"></span></span>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <label for="modal-quantity" class="form-label fw-semibold mb-0">Jumlah:</label>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" id="modal-quantity-minus">-</button>
                                <input type="text" id="modal-quantity" class="form-control text-center" value="1" min="1" readonly>
                                <button class="btn btn-outline-secondary" type="button" id="modal-quantity-plus">+</button>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-sm-flex">
                            <button id="modal-add-to-cart-btn" class="btn btn-primary flex-grow-1" type="button">+ Keranjang</button>
                            <button id="modal-wishlist-btn" class="btn btn-outline-danger" type="button"><i class="fas fa-heart"></i></button>
                        </div>
                        <div id="modal-cart-feedback" class="text-success mt-2" style="display: none;"></div>

                        <hr class="my-3">

                        <div id="modal-product-description-container">
                            <p class="fs-sm text-muted" id="modal-product-description"></p>
                            <a href="#" id="modal-go-to-details-btn" class="btn btn-outline-primary btn-sm mt-2">Lihat Detail</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const quickViewModal = document.getElementById('quickViewModal');
    const modal = new bootstrap.Modal(quickViewModal);

    // --- Modal Element References ---
    const modalProductName = document.getElementById('modal-product-name');
    const modalProductImage = document.getElementById('modal-product-image');
    const modalProductPrice = document.getElementById('modal-product-price');
    const modalProductOriginalPrice = document.getElementById('modal-product-original-price');
    const modalProductDescription = document.getElementById('modal-product-description');
    const modalProductCategory = document.getElementById('modal-product-category');
    const modalProductVariant = document.getElementById('modal-product-variant');
    const modalQuantityInput = document.getElementById('modal-quantity');
    const modalAddToCartBtn = document.getElementById('modal-add-to-cart-btn');
    const modalWishlistBtn = document.getElementById('modal-wishlist-btn');
    const modalCartFeedback = document.getElementById('modal-cart-feedback');

    let currentProductId = null;
    let currentProductStock = 0;

    // --- Event Listener for all Quick View Buttons ---
    document.querySelectorAll('.quick-view-btn').forEach(button => {
        button.addEventListener('click', function () {
            currentProductId = this.dataset.productId;
            // Reset modal state
            resetModal();
            // Fetch and populate
            populateModal(currentProductId);
        });
    });

    function resetModal() {
        modalProductName.textContent = 'Memuat...';
        modalProductImage.src = 'https://via.placeholder.com/400';
        modalProductPrice.textContent = '';
        modalProductOriginalPrice.textContent = '';
        modalProductDescription.textContent = '...';
        modalProductCategory.textContent = '...';
        modalProductVariant.textContent = '...';
        modalQuantityInput.value = 1;
        modalCartFeedback.style.display = 'none';
        modalAddToCartBtn.disabled = true;
        modalWishlistBtn.disabled = true;
    }

    function populateModal(productId) {
        fetch(`/api/products/${productId}`)
            .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok'))
            .then(data => {
                currentProductStock = data.stock;
                modalProductName.textContent = data.name;
                modalProductImage.src = `{{ asset('storage') }}/${data.image}`;
                const fullDescription = data.description || 'Tidak ada deskripsi.';
                const shortDescription = fullDescription.length > 100 ? fullDescription.substring(0, 100) + '...' : fullDescription;
                modalProductDescription.textContent = shortDescription;

                // Set the href for the details button
                const detailButton = document.getElementById('modal-go-to-details-btn');
                detailButton.href = `{{ url('/products') }}/${data.id}`;
                modalProductCategory.textContent = data.category;
                modalProductVariant.textContent = data.variant;

                // Handle price
                if (data.harga_diskon && data.harga_diskon > 0) {
                    modalProductPrice.textContent = `Rp ${Number(data.harga_diskon).toLocaleString('id-ID')}`;
                    modalProductOriginalPrice.textContent = `Rp ${Number(data.price).toLocaleString('id-ID')}`;
                    modalProductOriginalPrice.style.display = 'inline';
                } else {
                    modalProductPrice.textContent = `Rp ${Number(data.price).toLocaleString('id-ID')}`;
                    modalProductOriginalPrice.style.display = 'none';
                }

                // Update Wishlist Button State
                updateWishlistButton(data.is_in_wishlist);

                // Enable buttons
                modalAddToCartBtn.disabled = false;
                modalWishlistBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching product data:', error);
                modalProductName.textContent = 'Gagal memuat produk';
            });
    }

    // --- Quantity Controls ---
    document.getElementById('modal-quantity-plus').addEventListener('click', () => {
        let qty = parseInt(modalQuantityInput.value);
        if (qty < currentProductStock) modalQuantityInput.value = qty + 1;
    });
    document.getElementById('modal-quantity-minus').addEventListener('click', () => {
        let qty = parseInt(modalQuantityInput.value);
        if (qty > 1) modalQuantityInput.value = qty - 1;
    });

    // --- Add to Cart AJAX ---
    modalAddToCartBtn.addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;

        fetch(`{{ url('/cart/add') }}/${currentProductId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity: modalQuantityInput.value })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                modalCartFeedback.textContent = 'Berhasil ditambahkan ke keranjang!';
                modalCartFeedback.style.display = 'block';
                // Optionally, update a global cart counter here
            } else {
                throw new Error(data.message || 'Gagal menambahkan ke keranjang');
            }
        })
        .catch(error => {
            console.error('Add to cart error:', error);
            modalCartFeedback.textContent = 'Gagal menambahkan. Coba lagi.';
            modalCartFeedback.className = 'text-danger mt-2';
            modalCartFeedback.style.display = 'block';
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '+ Keranjang';
            setTimeout(() => { modalCartFeedback.style.display = 'none'; }, 3000);
        });
    });

    // --- Wishlist AJAX ---
    const IS_LOGGED_IN = @auth true @else false @endauth;

    modalWishlistBtn.addEventListener('click', function() {
        if (!IS_LOGGED_IN) {
            // Assumes a login modal with ID 'loginModal' exists in your main layout
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            // Hide the current modal before showing the login one
            modal.hide();
            loginModal.show();
            return;
        }

        const isInWishlist = this.classList.contains('btn-danger');
        const url = `{{ url('/wishlist') }}` + (isInWishlist ? `/${currentProductId}` : '');
        const method = isInWishlist ? 'DELETE' : 'POST';

        this.disabled = true;

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: currentProductId })
        })
        .then(response => {
            if (!response.ok) {
                // Handle non-JSON responses or errors if the user is not authenticated
                // This can happen if the session expires mid-use
                if (response.status === 401 || response.status === 419) {
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    modal.hide();
                    loginModal.show();
                }
                return Promise.reject('Authentication error or invalid response.');
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                // The controller now returns the correct state
                updateWishlistButton(data.is_in_wishlist);
            }
        })
        .catch(error => console.error('Wishlist error:', error))
        .finally(() => { this.disabled = false; });
    });

    function updateWishlistButton(isWishlisted) {
        if (isWishlisted) {
            modalWishlistBtn.classList.remove('btn-outline-danger');
            modalWishlistBtn.classList.add('btn-danger');
            modalWishlistBtn.title = 'Hapus dari Wishlist';
        } else {
            modalWishlistBtn.classList.remove('btn-danger');
            modalWishlistBtn.classList.add('btn-outline-danger');
            modalWishlistBtn.title = 'Tambah ke Wishlist';
        }
    }
});
</script>
@endpush
