@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Manajemen Pesanan</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID Pesanan</th>
                                    <th scope="col">Pelanggan</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tanggal Pesan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td><span class="badge bg-primary">{{ ucfirst($order->status) }}</span></td>
                                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                        <td class="d-flex flex-wrap">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info me-1 mb-1">Lihat Detail</a>
                                            @if($order->status == 'pending' || $order->status == 'processing')
                                                <form action="{{ route('admin.orders.update', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-danger mb-1">Batal</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
