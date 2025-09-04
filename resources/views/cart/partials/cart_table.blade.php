<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th scope="col">Produk</th>
                <th scope="col">Harga</th>
                <th scope="col" class="text-center">Jumlah</th>
                <th scope="col" class="text-end">Subtotal</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($cart as $id => $details)
                @php $total += $details['price'] * $details['quantity']; @endphp
                <tr class="cart-item-row" data-id="{{ $id }}">
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="cart-item-img me-3" loading="lazy" decoding="async">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" class="form-control quantity-input cart-quantity-input" min="1" data-id="{{ $id }}">
                    </td>
                    <td class="text-end item-subtotal">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger cart-remove-btn" data-id="{{ $id }}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Lanjut Belanja</a>
    </div>
    <div class="col-md-6 text-md-end">
        <h3 class="fw-bold">Total: <span id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</span></h3>
        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg mt-2">Lanjut ke Pembayaran</a>
    </div>
</div>
