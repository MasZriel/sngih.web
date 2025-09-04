@extends('layouts.app')

@section('styles')
<style>
    .product-gallery img {
        border-radius: 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .product-details h1 {
        font-weight: 700;
    }
    .product-price {
        font-size: 2rem;
        font-weight: 600;
        color: var(--primary-color);
    }
    .product-description {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    .star-rating-input {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .star-rating-input > input {
        display: none;
    }
    .star-rating-input > label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    .star-rating-input > input:checked ~ label,
    .star-rating-input > label:hover,
    .star-rating-input > label:hover ~ label {
        color: #ffc107;
    }
    .review-comment.truncated {
        max-height: 4.5em; /* Roughly 3 lines */
        overflow: hidden;
        position: relative;
    }
    .review-comment {
        overflow-wrap: break-word;
        word-wrap: break-word; /* Fallback for older browsers */
        white-space: normal !important;
        word-break: break-all;
    }
    .review-comment.truncated::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 70%;
        height: 1.5em;
        background: linear-gradient(to right, transparent, white);
    }
    .read-more-btn {
        background: none;
        border: none;
        color: var(--primary-color);
        cursor: pointer;
        padding: 0;
        font-weight: bold;
    }

    /* Responsive Review Layout */
    @media (max-width: 575.98px) {
        .review-item-wrapper {
            display: block !important;
        }
        .review-item-wrapper .flex-shrink-0 {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-lg-6 product-gallery" data-aos="fade-right">
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
        </div>
        <div class="col-lg-6 product-details" data-aos="fade-left">
            <h1 class="mb-3">{{ $product->name }}</h1>

            @auth
                @if(Auth::user()->isAdmin())
                    <div class="mb-3">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-info"><i class="fas fa-edit me-2"></i> Edit Produk Ini</a>
                    </div>
                @endif
            @endauth

                        @if($product->harga_diskon && $product->harga_diskon > 0)
                <div class="mb-4">
                    <span class="product-price text-danger" id="product-total-price">Rp {{ number_format($product->harga_diskon, 0, ',', '.') }}</span>
                    <s class="text-muted fs-4 ms-2">Rp {{ number_format($product->price, 0, ',', '.') }}</s>
                </div>
            @else
                <p class="product-price mb-4" id="product-total-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            @endif
            <div class="product-description mb-4">
                {!! nl2br(e($product->description)) !!}
            </div>

            <div class="mt-4">
                <h5 class="fw-bold">Detail Produk</h5>
                <p>Produk ini dibuat dari bahan-bahan pilihan berkualitas tinggi. Diolah dengan resep rahasia kami untuk menghasilkan cita rasa yang unik dan tak terlupakan. Setiap gigitan akan memberikan sensasi renyah dan bumbu yang meresap sempurna.</p>
                
                <h6 class="fw-bold mt-3">Bahan Utama:</h6>
                <ul>
                    <li>Tepung Pilihan</li>
                    <li>Rempah-Rempah Asli Indonesia</li>
                    <li>Minyak Nabati</li>
                    <li>Bumbu Rahasia "Snagih"</li>
                </ul>
            </div>
            
            @if(!Auth::check() || !Auth::user()->isAdmin())
                @if($product->stock > 0)
                    <div class="d-flex align-items-start">
                        <!-- Add to Cart Form -->
                        <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="me-2">
                            @csrf
                            <div class="mb-3">
                                <label for="quantity" class="form-label fw-bold">Jumlah:</label>
                                <div class="input-group quantity-controls" style="width: 150px;">
                                    <button type="button" id="quantity-minus" class="btn btn-outline-secondary"><i class="fas fa-minus"></i></button>
                                    <input type="text" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" readonly>
                                    <button type="button" id="quantity-plus" class="btn btn-outline-secondary"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang</button>
                        </form>

                        <!-- Wishlist Form -->
                        <form action="{{ $isInWishlist ? route('wishlist.destroy', $product->id) : route('wishlist.store') }}" method="POST" class="ms-2">
                            @csrf
                            @if($isInWishlist)
                                @method('DELETE')
                            @endif
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <label class="form-label fw-bold" style="visibility: hidden;">Aksi</label> <!-- Dummy label for alignment -->
                            <div>
                                <button type="submit" class="btn btn-outline-danger btn-lg" title="{{ $isInWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        <strong>Stok Habis!</strong> Produk ini tidak tersedia saat ini.
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Dapatkan Notifikasi Stok</h5>
                            <p class="card-text">Masukkan email Anda untuk mendapatkan notifikasi saat produk ini tersedia kembali.</p>
                            <form action="{{ route('stock.notification.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                @guest
                                <div class="mb-3">
                                    <label for="email" class="form-label">Alamat Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                @endguest

                                <button type="submit" class="btn btn-secondary w-100">Beri tahu saya jika stok tersedia</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif

            <div class="mt-4">
                <p class="mb-1"><strong>Kategori:</strong> <a href="{{ route('products.index', ['category' => $product->category]) }}" class="text-decoration-none">{{ ucfirst($product->category) }}</a></p>
                <p><strong>Varian:</strong> {{ ucfirst($product->variant) }}</p>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <h2 class="mb-4 fw-bold">Ulasan Pelanggan ({{ $product->reviews->count() }})</h2>

            @auth
            <div class="card mb-5" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title">Bagikan Pendapatmu</h5>
                    <form action="{{ route('reviews.store') }}" method="POST" id="review-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="star-rating-input @error('rating') is-invalid @enderror">
                                <input type="radio" id="star5" name="rating" value="5" {{ old('rating') == 5 ? 'checked' : '' }}><label for="star5" title="5 stars">★</label>
                                <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == 4 ? 'checked' : '' }}><label for="star4" title="4 stars">★</label>
                                <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == 3 ? 'checked' : '' }}><label for="star3" title="3 stars">★</label>
                                <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == 2 ? 'checked' : '' }}><label for="star2" title="2 stars">★</label>
                                <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == 1 ? 'checked' : '' }}><label for="star1" title="1 star">★</label>
                            </div>
                            @error('rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Ulasan Anda</label>
                            <textarea name="comment" id="comment" rows="4" class="form-control" required>{{ old('comment') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-info" data-aos="fade-up">
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</a> untuk memberikan ulasan.
            </div>
            @endauth

            @if ($reviews->isEmpty())
                <p>Belum ada ulasan untuk produk ini.</p>
            @else
                @foreach ($reviews as $review)
                    @include('products.partials.review', ['review' => $review, 'level' => 0])
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Add to Cart AJAX Form Submission ---
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const action = form.action;
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonHtml = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menambahkan...`;

            fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Berhasil ditambahkan!', 'success');

                    // Update cart count in navbar
                    document.querySelectorAll('.cart-count-badge').forEach(badge => {
                        badge.innerText = data.cart_count;
                        if (data.cart_count > 0) {
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }
                    });
                } else {
                    showToast(data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                showToast(error.message || 'Gagal menambahkan. Silakan coba lagi.', 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonHtml;
            });
        });
    }

    // --- Toast Notification Function ---
    function showToast(message, type = 'success') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '80px';
            toastContainer.style.right = '20px';
            toastContainer.style.zIndex = '1056';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show m-0 mb-2`;
        toast.role = 'alert';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        toastContainer.appendChild(toast);

        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                bootstrap.Alert.getOrCreateInstance(toastElement).close();
            }
        }, 5000);
    }
    
    // --- Quantity Controls Logic ---
    const quantityInput = document.getElementById('quantity');
    const priceElement = document.getElementById('product-total-price');
    const plusBtn = document.getElementById('quantity-plus');
    const minusBtn = document.getElementById('quantity-minus');

    if (quantityInput) {
        const basePrice = {{ $product->harga_diskon > 0 ? $product->harga_diskon : $product->price }};
        const stock = {{ $product->stock }};

        const updatePrice = () => {
            const quantity = parseInt(quantityInput.value);
            if (priceElement) {
                priceElement.textContent = `Rp ${(basePrice * quantity).toLocaleString('id-ID')}`;
            }
        };

        plusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < stock) {
                quantityInput.value = currentValue + 1;
                updatePrice();
            }
        });

        minusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updatePrice();
            }
        });
    }

    // --- Star Rating Input Logic ---
    // No extra JS needed for the pure CSS solution to work for hover and select.
    // The state is handled by :checked pseudo-selector.

    // --- Read More for Reviews ---
    document.querySelectorAll('.review-comment').forEach(commentDiv => {
        const fullText = commentDiv.dataset.fullComment.trim();
        // Roughly 200 characters for truncation
        if (fullText.length > 200) {
            const truncatedText = fullText.substring(0, 200) + '...';
            commentDiv.innerHTML = truncatedText;
            commentDiv.classList.add('truncated');

            const readMoreBtn = document.createElement('button');
            readMoreBtn.innerText = 'Baca selengkapnya';
            readMoreBtn.className = 'read-more-btn';
            
            commentDiv.parentNode.insertBefore(readMoreBtn, commentDiv.nextSibling);

            readMoreBtn.addEventListener('click', function() {
                if (commentDiv.classList.contains('truncated')) {
                    commentDiv.innerHTML = fullText;
                    commentDiv.classList.remove('truncated');
                    readMoreBtn.innerText = 'Tampilkan lebih sedikit';
                } else {
                    commentDiv.innerHTML = truncatedText;
                    commentDiv.classList.add('truncated');
                    readMoreBtn.innerText = 'Baca selengkapnya';
                }
            });
        }
    });

    // --- Reply Form Toggle Logic ---
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const formContainer = this.nextElementSibling;
            if (formContainer && formContainer.classList.contains('reply-form-container')) {
                formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
});
</script>
@endpush