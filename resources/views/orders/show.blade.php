@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Pesanan #{{ $order->id }}</h4>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Kembali ke Profil</a>
    </div>

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
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="60" class="img-thumbnail me-3">
                                                @endif
                                                <span>{{ $item->product->name ?? 'Produk tidak tersedia' }}</span>
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
                    <h5>Alamat Pengiriman</h5>
                </div>
                <div class="card-body">
                    <p>{{ $order->shipping_address }}</p>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Status Pesanan</h5>
                </div>
                <div class="card-body">
                                        <p><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($order->status) }}</span></p>
                    @if($order->shipping_deadline)
                        <p><strong>Batas Waktu Pengiriman:</strong> <span class="text-danger">{{ $order->shipping_deadline->format('d M Y, H:i') }}</span></p>
                    @endif

                    @if($order->status == 'pending')
                        <hr>
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Batalkan Pesanan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
