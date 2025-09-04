@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Manajemen Ulasan</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse ($reviews as $review)
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title mb-1">{{ $review->user->name ?? 'Pengguna Anonim' }}</h6>
                                        <p class="card-subtitle mb-2 text-muted small">
                                            {{ $review->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                    <span class="badge bg-warning text-dark">{{ $review->rating }} <i class="fas fa-star"></i></span>
                                </div>

                                <p class="mt-2">
                                    <strong>Produk:</strong>
                                    <a href="{{ route('products.show', $review->product->id) }}" target="_blank" class="text-decoration-none">
                                        {{ $review->product->name ?? 'N/A' }}
                                    </a>
                                </p>

                                <p class="card-text">{{ $review->comment }}</p>

                                <hr>

                                <!-- Admin Reply Section -->
                                @if ($review->reply)
                                    <div class="ps-3 mt-3">
                                        <h6 class="text-success">Balasan Anda:</h6>
                                        <p style="white-space: pre-wrap;">{{ $review->reply }}</p>
                                        <p class="text-muted small">Dibalas pada: {{ $review->replied_at->format('d M Y, H:i') }}</p>
                                    </div>
                                @else
                                    <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="reply-{{ $review->id }}" class="form-label fw-bold">Beri Balasan</label>
                                            <textarea name="reply" id="reply-{{ $review->id }}" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Kirim Balasan</button>
                                    </form>
                                @endif
                                <!-- End Admin Reply Section -->

                            </div>
                            <div class="card-footer bg-light text-end">
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus ulasan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus Ulasan</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">
                            <p>Tidak ada ulasan untuk ditampilkan.</p>
                        </div>
                    @endforelse

                    <div class="d-flex justify-content-center mt-4">
                        {{ $reviews->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection