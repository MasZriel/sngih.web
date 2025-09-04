@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Pesanan #{{ $order->id }}</h4>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Kembali ke Daftar Pesanan</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5>Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="60" class="img-thumbnail me-3">
                                                <span>{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Keseluruhan</td>
                                    <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5>Detail Pelanggan</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Alamat Pengiriman:</strong><br>{{ $order->user->address }}</p>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Status Pesanan</h5>
                </div>
                <div class="card-body">
                    @if($order->status == 'cancelled')
                        <p>Status Saat Ini: <span class="badge bg-danger">{{ ucfirst($order->status) }}</span></p>
                        @if($order->cancellation_reason)
                        <div class="alert alert-warning mt-3">
                            <strong>Alasan Pembatalan:</strong><br>
                            {{ $order->cancellation_reason }}
                        </div>
                        @endif
                    @else
                                                <p class="card-text"><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($order->status) }}</span></p>
                        @if($order->shipping_deadline)
                        <p class="card-text"><strong>Batas Waktu Pengiriman:</strong> <span class="text-danger">{{ $order->shipping_deadline->format('d M Y, H:i') }}</span></p>
                        @endif
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">Ubah Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3" id="cancellation-reason-container" style="display: none;">
                                <label for="cancellation_reason" class="form-label">Alasan Pembatalan</label>
                                <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            const reasonContainer = document.getElementById('cancellation-reason-container');
            const reasonTextarea = document.getElementById('cancellation_reason');

            function toggleReasonField() {
                if (statusSelect.value === 'cancelled') {
                    reasonContainer.style.display = 'block';
                    reasonTextarea.required = true;
                } else {
                    reasonContainer.style.display = 'none';
                    reasonTextarea.required = false;
                }
            }

            // Initial check in case the page loads with 'cancelled' already selected
            toggleReasonField();

            // Add event listener for changes
            statusSelect.addEventListener('change', toggleReasonField);
        }
    });
</script>
@endpush