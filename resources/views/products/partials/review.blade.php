<div class="d-flex review-item-wrapper mb-4" data-aos="fade-up" style="margin-left: {{ $level * 40 }}px;">
    <div class="flex-shrink-0 me-3">
        <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($review->user->email))) }}?d=identicon" class="rounded-circle" alt="{{ $review->user->name }}" width="{{ $level > 0 ? 40 : 50 }}">
    </div>
    <div class="flex-grow-1">
        <h5 class="mt-0 mb-1 fw-bold">{{ $review->user->name }}</h5>
        
        @if($review->rating)
        <div class="rating-display mb-2">
            @for ($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
            @endfor
        </div>
        @endif

        <div class="review-comment" data-full-comment="{{ e($review->comment) }}">
            {{ e($review->comment) }}
        </div>
        <small class="text-muted d-block mt-2">{{ $review->created_at->diffForHumans() }}</small>

        @auth
        <button class="btn btn-sm btn-link ps-0 reply-btn">Balas</button>
        <div class="reply-form-container mt-3" style="display: none;">
            <form action="{{ route('reviews.reply', $review->id) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <textarea name="comment" class="form-control" rows="2" placeholder="Tulis balasan Anda..." required></textarea>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Kirim Balasan</button>
            </form>
        </div>
        @endauth

        <!-- Admin Reply -->
        @if ($review->reply)
        <div class="mt-3 p-3 bg-light rounded" style="border-left: 3px solid var(--primary-color);">
            <h6 class="fw-bold">Balasan dari Penjual:</h6>
            <p class="mb-0" style="white-space: pre-wrap;">{{ $review->reply }}</p>
            <small class="text-muted d-block mt-1">Dibalas pada: {{ $review->replied_at->format('d M Y, H:i') }}</small>
        </div>
        @endif

        <!-- Nested Replies -->
        @if ($review->replies->isNotEmpty())
            <div class="mt-4">
                @foreach ($review->replies as $reply)
                    @include('products.partials.review', ['review' => $reply, 'level' => $level + 1])
                @endforeach
            </div>
        @endif
    </div>
</div>
