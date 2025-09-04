<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Snagih</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #F7F8FA;
            --content-bg: #FFFFFF;
            --text-primary: #1A202C;
            --text-secondary: #718096;
            --accent-blue: #4A5568;
            --accent-blue-hover: #2D3748;
            --card-bg: #FFFFFF;
            --border-color: #EDF2F7;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--content-bg);
            color: var(--text-primary);
        }
        .wrapper { display: flex; }
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            transition: all 0.3s ease-in-out;
            position: fixed;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
        }
        #sidebar.collapsed { margin-left: calc(-1 * var(--sidebar-width)); }
        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }
        .sidebar-header h5 {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.5rem;
        }
        #sidebar .nav-link {
            margin: 0.25rem 1rem;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover {
            color: var(--accent-blue-hover);
        }
        #sidebar .nav-link.active {
            color: white;
            background: var(--accent-blue);
        }
        #sidebar .nav-link.active:hover {
            color: white;
        }
        #sidebar .nav-link i { width: 20px; margin-right: 1rem; text-align: center; font-size: 1rem; }
        #content { width: 100%; padding-left: var(--sidebar-width); transition: padding-left 0.3s ease-in-out; }
        #content.full-width { padding-left: 0; }
        .top-navbar {
            padding: 1rem 2rem;
            background: var(--accent-blue-hover); /* New Header Color */
            color: #FFFFFF; /* White text for header */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #sidebar-toggle { font-size: 1.25rem; cursor: pointer; color: #E2E8F0; }
        .header-actions { display: flex; align-items: center; }
        .header-actions .nav-link { color: #E2E8F0; font-size: 1.25rem; }
        .top-navbar h4 {
            color: #FFFFFF;
        }
        .main-content { padding: 2rem; }
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: none;
        }

        @media (max-width: 992px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.collapsed {
                margin-left: 0;
            }
            #content {
                padding-left: 0;
            }
        }

        @media (max-width: 767.98px) {
            .stat-card-col {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1.5rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="antialiased">
    <div class="wrapper">
        <nav id="sidebar">
            <div>
                <div class="sidebar-header">
                    <h5>Snagih</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="fas fa-box-open"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="fas fa-receipt"></i> Orders</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> Customers</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}"><i class="fas fa-star"></i> Reviews</a></li>
                </ul>
            </div>
            <div class="mt-auto p-3">
                 <a class="nav-link" href="{{ url('admin/logout') }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="admin-logout-form" action="{{ url('admin/logout') }}" method="POST" class="d-none"> @csrf </form>
            </div>
        </nav>

        <div id="content">
            <header class="top-navbar">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bars me-3 d-lg-none" id="sidebar-toggle"></i>
                    <h4 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h4>
                </div>
                <div class="d-flex align-items-center">
                    
                    <div class="header-actions">
                        <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link" href="#" id="navbarDropdownNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    @if($adminUnreadNotifications->count() > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $adminUnreadNotifications->count() }}
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownNotifications" style="width: 350px;">
                                    <li class="dropdown-header px-3">{{ $adminUnreadNotifications->count() }} Unread Notifications</li>
                                    <li><hr class="dropdown-divider"></li>
                                    @forelse ($adminUnreadNotifications->take(5) as $notification)
                                        <li>
                                            <a class="dropdown-item d-flex align-items-start" href="{{ route('admin.notifications.read', ['id' => $notification->id]) }}" style="white-space: normal;">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }} mt-1 me-2"></i>
                                                <div>
                                                    <p class="mb-0 small">{{ $notification->data['message'] }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <li><p class="text-center text-muted small my-2">No unread notifications</p></li>
                                    @endforelse
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center small" href="{{ route('admin.reviews.index') }}">View all reviews</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?d=mp" class="rounded-circle me-2" height="32" alt="User">
                                    <div class="d-none d-sm-block">{{ Auth::user()->name }}</div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownAdmin">
                                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">View Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar Toggle
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
            }
        });
    </script>
    @stack('scripts')
</body>
</html>