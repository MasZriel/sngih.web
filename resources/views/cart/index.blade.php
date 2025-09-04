@extends('layouts.app')

@section('styles')
<style>
    .cart-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .quantity-input {
        width: 80px;
        text-align: center;
    }
    .cart-item-row .form-control, .cart-item-row .btn {
        transition: all 0.3s ease;
    }
    .cart-item-row.updating {
        opacity: 0.5;
        pointer-events: none;
    }
    .toast-container {
        z-index: 1056; /* Higher than modal */
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div id="cart-view">
        <h1 class="fw-bold mb-4">Keranjang Belanja Anda</h1>

        <div id="cart-content">
            @if(empty($cart))
                @include('cart.partials.empty_cart')
            @else
                @include('cart.partials.cart_table', ['cart' => $cart])
            @endif
        </div>
    </div>

    @auth
    <hr class="my-5">
    <div class="order-history mt-5">
        <h2 class="fw-bold mb-4">Riwayat Pesanan Anda</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($order->status) }}</span></td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Anda belum memiliki riwayat pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endauth
</div>

<!-- Toast for notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="cart-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">Notifikasi Keranjang</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const cartContent = document.getElementById('cart-content');
    const toastElement = document.getElementById('cart-toast');
    const cartToast = new bootstrap.Toast(toastElement);

    const showToast = (message, isSuccess = true) => {
        const toastBody = toastElement.querySelector('.toast-body');
        toastBody.textContent = message;
        toastElement.classList.remove(isSuccess ? 'bg-danger' : 'bg-success');
        toastElement.classList.add(isSuccess ? 'bg-success' : 'bg-danger');
        toastElement.querySelector('.toast-header').classList.toggle('text-white', true);
        toastBody.classList.toggle('text-white', true);
        cartToast.show();
    };

    const handleCartUpdate = (event) => {
        if (event.target.matches('.cart-quantity-input')) {
            const input = event.target;
            const productId = input.dataset.id;
            const quantity = input.value;
            const row = input.closest('.cart-item-row');
            row.classList.add('updating');

            updateCart(productId, quantity, row);
        }
    };

    const handleCartRemove = (event) => {
        const removeBtn = event.target.closest('.cart-remove-btn');
        if (removeBtn) {
            event.preventDefault();
            const productId = removeBtn.dataset.id;
            const row = removeBtn.closest('.cart-item-row');
            row.classList.add('updating');

            removeCartItem(productId, row);
        }
    };

    const debounce = (func, delay) => {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const updateCart = debounce((productId, quantity, row) => {
        fetch(`/cart/update/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cart-total').textContent = data.new_total_formatted;
                row.querySelector('.item-subtotal').textContent = data.item_subtotal_formatted;
                showToast(data.message);
            } else {
                showToast(data.message, false);
                // Revert quantity if stock is insufficient
                // This requires fetching the old quantity or storing it, for simplicity we'll just alert.
            }
        })
        .catch(console.error)
        .finally(() => {
            row.classList.remove('updating');
        });
    }, 500);

    const removeCartItem = (productId, row) => {
        fetch(`/cart/remove/${productId}`, {
            method: 'POST', // HTML forms in Laravel often use POST for DELETE
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                document.getElementById('cart-total').textContent = data.new_total_formatted;
                showToast(data.message);
                if (data.cart_is_empty) {
                    fetch(window.location.href) // Fetch the current page to get the empty cart partial
                        .then(response => response.text())
                        .then(html => {
                            const newDoc = new DOMParser().parseFromString(html, 'text/html');
                            const newCartContent = newDoc.getElementById('cart-content').innerHTML;
                            cartContent.innerHTML = newCartContent;
                        });
                }
            }
        })
        .catch(console.error)
        .finally(() => {
            // No need to remove updating class as the row is gone
        });
    };

    cartContent.addEventListener('change', handleCartUpdate);
    cartContent.addEventListener('click', handleCartRemove);
});
</script>
@endpush