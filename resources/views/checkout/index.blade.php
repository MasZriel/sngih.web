@extends('layouts.app')

@section('styles')
<style>
    .order-summary-card, .shipping-card {
        background-color: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Checkout</h1>
        <p class="text-muted">Selesaikan pesanan Anda hanya dalam beberapa langkah.</p>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">
            <div class="shipping-card">
                <h3 class="fw-bold mb-4">Alamat Pengiriman</h3>
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Penerima</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="shipping_address" rows="3" required>{{ auth()->user()->address }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="province" class="form-label">Provinsi</label>
                            <select class="form-select" id="province" name="province_id" required>
                                <option selected disabled>Pilih Provinsi</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">Kota/Kabupaten</label>
                            <select class="form-select" id="city" name="city_id" required disabled>
                                <option selected disabled>Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="courier" class="form-label">Kurir</label>
                            <select class="form-select" id="courier" name="courier" required>
                                <option value="jne">JNE</option>
                                <option value="jni">JNI</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 align-self-end">
                            <button type="button" class="btn btn-secondary w-100" id="check-ongkir">Cek Ongkir</button>
                        </div>
                    </div>
                    
                    <div id="shipping-options" class="mt-3"></div>

                    <div class="mb-3 mt-4">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="qris" value="qris">
                            <label class="form-check-label" for="qris">QRIS</label>
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="bni" value="bni">
                            <label class="form-check-label" for="bni">Bank BNI</label>
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="mandiri" value="mandiri">
                            <label class="form-check-label" for="mandiri">Bank MANDIRI</label>
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="dana" value="dana">
                            <label class="form-check-label" for="dana">DANA</label>
                        </div>
                    </div>

                    <div id="payment-details" class="mt-3">
                        <div id="payment-cod" class="payment-info card bg-light p-3" style="display: block;">
                            <p class="mb-0">Anda akan membayar secara tunai kepada kurir saat pesanan tiba.</p>
                        </div>
                        <div id="payment-qris" class="payment-info card bg-light p-3" style="display: none;">
                            <p>Silakan pindai kode QR di bawah ini menggunakan aplikasi pembayaran Anda:</p>
                            <img src="{{ asset('images/qris1.jpg') }}" alt="QRIS Code" class="img-fluid mx-auto d-block" style="max-width: 200px;">
                        </div>
                        <div id="payment-bni" class="payment-info card bg-light p-3" style="display: none;">
                            <h6 class="fw-bold">Transfer Bank BNI</h6>
                            <p class="mb-0">Nomor Rekening: <strong>1850134018</strong></p>
                            <p class="mb-0">Atas Nama: <strong>Rasya Adam Saputra</strong></p>
                        </div>
                        <div id="payment-mandiri" class="payment-info card bg-light p-3" style="display: none;">
                            <h6 class="fw-bold">Transfer Bank MANDIRI</h6>
                            <p class="mb-0">Nomor Rekening: <strong>1800011639335</strong></p>
                            <p class="mb-0">Atas Nama: <strong>Rasya Adam Saputra</strong></p>
                        </div>
                        <div id="payment-dana" class="payment-info card bg-light p-3" style="display: none;">
                            <h6 class="fw-bold">Pembayaran DANA</h6>
                            <p class="mb-0">Nomor Telepon: <strong>085877367991</strong></p>
                            <p class="mb-0">Atas Nama: <strong>SUSWATI</strong></p>
                        </div>
                    </div>
                    <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                    <input type="hidden" name="total_weight" id="total_weight" value="{{ $totalWeight }}">
                </form>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="order-summary-card">
                <h3 class="fw-bold mb-4">Ringkasan Pesanan</h3>
                <ul class="list-group list-group-flush">
                    @foreach($cart as $id => $details)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        {{ $details['name'] }} (x{{ $details['quantity'] }})
                        <span>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                    </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 fw-bold">
                        Subtotal
                        <span id="subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 fw-bold">
                        Biaya Pengiriman
                        <span id="shipping-cost-display">Rp 0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 h4 fw-bold">
                        Total
                        <span id="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </li>
                </ul>
                <div class="d-grid mt-4">
                    <button type="submit" form="checkout-form" class="btn btn-primary btn-lg">Buat Pesanan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle Payment Method Display
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const paymentInfos = document.querySelectorAll('.payment-info');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            paymentInfos.forEach(info => {
                info.style.display = 'none';
            });
            const selectedInfo = document.getElementById(`payment-${this.value}`);
            if (selectedInfo) {
                selectedInfo.style.display = 'block';
            }
        });
    });

    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const courierSelect = document.getElementById('courier');
    const checkOngkirBtn = document.getElementById('check-ongkir');
    const shippingOptionsDiv = document.getElementById('shipping-options');
    const subtotalElement = document.getElementById('subtotal');
    const shippingCostDisplay = document.getElementById('shipping-cost-display');
    const totalPriceElement = document.getElementById('total-price');
    const shippingCostInput = document.getElementById('shipping_cost');
    const totalWeightInput = document.getElementById('total_weight');

    const subtotal = {{ $total }};

    // Fetch Provinces
    fetch('{{ route('shipping.provinces') }}')
        .then(response => response.json())
        .then(data => {
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id; // Changed from province.province_id
                option.textContent = province.name; // Changed from province.province
                provinceSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching provinces:', error));

    // Fetch Cities on Province Change
    provinceSelect.addEventListener('change', function () {
        citySelect.innerHTML = '<option selected disabled>Pilih Kota/Kabupaten</option>';
        citySelect.disabled = true;
        shippingOptionsDiv.innerHTML = '';

        if (!this.value) return;

        fetch(`{{ route('shipping.cities') }}?province_id=${this.value}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id; // Changed from city.city_id
                    option.textContent = city.name; // Changed from `${city.type} ${city.city_name}`
                    citySelect.appendChild(option);
                });
                citySelect.disabled = false;
            })
            .catch(error => console.error('Error fetching cities:', error));
    });

    // Check Shipping Cost
    checkOngkirBtn.addEventListener('click', function () {
        const origin = 153; // Tangerang Selatan
        const destination = citySelect.value;
        const weight = totalWeightInput.value;
        const courier = courierSelect.value;

        if (!destination) {
            alert('Pilih kota tujuan terlebih dahulu.');
            return;
        }

        shippingOptionsDiv.innerHTML = '<p class="text-muted">Mencari ongkir...</p>';

        fetch('{{ route('shipping.cost') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                origin: origin,
                destination: destination,
                weight: weight,
                courier: courier
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            shippingOptionsDiv.innerHTML = '';
            if (data.error) {
                shippingOptionsDiv.innerHTML = `<p class="text-danger">${data.error}</p>`;
                return;
            }

            const costs = data[0].costs;
            if (costs.length === 0) {
                shippingOptionsDiv.innerHTML = '<p class="text-warning">Tidak ada layanan pengiriman yang tersedia untuk tujuan ini.</p>';
                return;
            }

            costs.forEach(cost => {
                const costValue = cost.cost[0].value;
                const formattedCost = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(costValue);

                const optionDiv = document.createElement('div');
                optionDiv.classList.add('form-check');
                optionDiv.innerHTML = `
                    <input class="form-check-input" type="radio" name="shipping_option" id="shipping-${cost.service}" value="${costValue}">
                    <label class="form-check-label" for="shipping-${cost.service}">
                        <strong>${cost.service} (${cost.description})</strong> - ${formattedCost} (${cost.cost[0].etd} hari)
                    </label>
                `;
                shippingOptionsDiv.appendChild(optionDiv);
            });
        })
        .catch(error => {
            console.error('Error fetching shipping cost:', error);
            shippingOptionsDiv.innerHTML = '<p class="text-danger">Gagal mengambil data ongkir. Periksa API Key Anda atau coba lagi nanti.</p>';
        });
    });

    // Update Total on Shipping Option Change
    shippingOptionsDiv.addEventListener('change', function (e) {
        if (e.target.name === 'shipping_option') {
            const shippingCost = parseInt(e.target.value);
            shippingCostInput.value = shippingCost;
            shippingCostDisplay.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(shippingCost);
            
            const newTotal = subtotal + shippingCost;
            totalPriceElement.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(newTotal);
        }
    });
});
</script>
@endpush