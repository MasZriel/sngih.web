<li class="nav-item dropdown">
    <a class="nav-link" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fs-5"></i>
        @if($notificationCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: .7em;">
                {{ $notificationCount }}
                <span class="visually-hidden">unread notifications</span>
            </span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
        @if($unreadNotifications->isEmpty())
            <li><a class="dropdown-item" href="#">Tidak ada notifikasi baru</a></li>
        @else
            @foreach($unreadNotifications as $notification)
                <li>
                    <a class="dropdown-item" href="{{ route('notifications.read', $notification->id) }}">
                        <div class="fw-bold">{{ $notification->data['message'] }}</div>
                        <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                    </a>
                </li>
            @endforeach
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-grid">
                    @csrf
                    <button type="submit" class="btn btn-link dropdown-item">Tandai semua dibaca</button>
                </form>
            </li>
            <li><a class="dropdown-item text-center" href="#">Lihat semua notifikasi</a></li>
        @endif
    </ul>
</li>
