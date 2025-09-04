<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Snagih - Pesan Makanan Favoritmu</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --primary-color: #00AA13; /* GoFood Green */
            --secondary-color: #333333;
            --background-color: #F8F9FA;
            --surface-color: #FFFFFF;
            --on-surface-color: #000000;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--secondary-color);
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }

        .navbar-brand img {
            height: 45px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 50px;
        }

        .btn-primary:hover {
            background-color: #008810;
            border-color: #008810;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 50px;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--surface-color);
        }

        .search-input {
            border-radius: 50px;
            border: 1px solid #e5e5e5;
            padding: 10px 20px 10px 45px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 15px center;
            background-size: 16px;
        }
        
        .footer {
            background-color: var(--secondary-color);
            color: var(--surface-color);
        }
        .footer a {
            color: var(--surface-color);
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }

        /* Live Search Results */
        .live-search-results-container {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 0.5rem 0.5rem;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1050; /* Ensure it's above other content */
        }
        .search-result-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        .search-result-item:last-child {
            border-bottom: none;
        }
        .search-result-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
            margin-right: 1rem;
        }
        .search-result-details {
            display: flex;
            flex-direction: column;
        }
        .search-result-name {
            font-weight: 600;
        }
        .search-result-price {
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        /* Auth Modal Styles */
        .auth-modal.modal .modal-dialog {
            max-width: 800px;
        }

        .auth-modal.modal .modal-content {
            border-radius: 1rem;
            overflow: hidden;
            border: none;
        }

        .auth-modal-img-col {
            background: linear-gradient(to top, var(--primary-color), #00c716);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .auth-modal-img-col img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .auth-modal-form-col {
            padding: 2rem;
        }

        .form-floating-label {
            position: relative;
        }

        .form-floating-label .form-control {
            height: 50px;
            padding-top: 1.375rem;
            padding-bottom: 0.375rem;
            border-radius: 0.5rem;
        }

        .form-floating-label textarea.form-control {
            height: auto;
        }

        .form-floating-label label {
            position: absolute;
            top: 0.8125rem;
            left: 0.75rem;
            font-size: 1rem;
            color: #6c757d;
            transition: all 0.2s ease-in-out;
            pointer-events: none;
        }

        .form-floating-label .form-control:focus~label,
        .form-floating-label .form-control:not(:placeholder-shown)~label {
            font-size: 0.75rem;
            transform: translateY(-100%) translateX(-0.1rem);
            background-color: var(--surface-color);
            padding: 0 0.25rem;
            color: var(--primary-color);
        }
        
        .form-floating-label .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 170, 19, 0.2);
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            color: #333;
            font-weight: 500;
        }

        .btn-google:hover {
            background-color: #f8f9fa;
        }

        .btn-google img {
            height: 20px;
        }

        .auth-modal .text-primary {
            color: var(--primary-color) !important;
        }

        .navbar-nav.flex-row {
            flex-wrap: nowrap;
        }

        .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
        }

        .invalid-feedback.d-block {
            font-size: .875em;
        }

        @media (max-width: 767.98px) {
            h1 {
                font-size: 2rem;
            }
            h2 {
                font-size: 1.75rem;
            }
            h3 {
                font-size: 1.5rem;
            }
        }

    </style>
    @yield('styles')
</head>
<body>
    

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" style="height:100px" alt="Snagih Logo">
            </a>
            <div class="d-none d-lg-block w-50 position-relative">
                 <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" id="navbar-search-input" class="form-control search-input" placeholder="Mau cari makan apa hari ini?" value="{{ request('search') }}" autocomplete="off">
                </form>
                <div id="navbar-live-search-results" class="live-search-results-container d-none"></div>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center flex-row">
                    <li class="nav-item d-lg-none my-2 position-relative">
                        <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" id="mobile-search-input" class="form-control search-input" placeholder="Cari produk..." value="{{ request('search') }}" autocomplete="off">
                        </form>
                        <div id="mobile-live-search-results" class="live-search-results-container d-none"></div>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active fw-bold' : '' }}" href="{{ route('products.index') }}" style="white-space: nowrap;">Galeri Produk</a>
                    </li>

                    @guest
                    <li class="nav-item ms-lg-3">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</button>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">Daftar</button>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart fs-5"></i>
                            <span class="cart-count-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ (!isset($cartItemCount) || $cartItemCount == 0) ? 'd-none' : '' }}" style="font-size: .7em;">{{ $cartItemCount ?? '' }}</span>
                        </a>
                    </li>
                    @else
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">Wishlist Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>

                    @if(!Auth::user()->isAdmin())
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart fs-5"></i>
                            <span class="cart-count-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ (!isset($cartItemCount) || $cartItemCount == 0) ? 'd-none' : '' }}" style="font-size: .7em;">{{ $cartItemCount ?? '' }}</span>
                        </a>
                    </li>
                    @endif

                    @include('layouts.partials.notifications')
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session('status'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Snagih</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: var(--primary-color); height: 2px"/>
                    <p>Pesan makanan ringan favoritmu dengan mudah dan cepat. Kualitas terjamin, rasa bikin nagih!</p>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Layanan</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: var(--primary-color); height: 2px"/>
                    <p><a href="{{ route('pesan-antar') }}" class="text-reset">Pesan Antar</a></p>
                    <p><a href="{{ route('promo') }}" class="text-reset">Promo</a></p>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Link Cepat</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: var(--primary-color); height: 2px"/>
                    <p><a href="{{ route('home') }}" class="text-reset">Beranda</a></p>
                    <p><a href="{{ route('products.index') }}" class="text-reset" style="white-space: nowrap;">Galeri Produk</a></p>
                    <p><a href="{{ route('about') }}" class="text-reset">Tentang Kami</a></p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold">Kontak</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: var(--primary-color); height: 2px"/>
                    <p><i class="fas fa-home me-3"></i> Banjarnegara</p>
                    <p><a href="https://mail.google.com/mail/?view=cm&fs=1&to=gosnagih@gmail.com" target="_blank" rel="noopener noreferrer" class="text-reset"><i class="fas fa-envelope me-3"></i> gosnagih@gmail.com</a></p>
                    <p><a href="https://wa.me/6289696036257" target="_blank" class="text-reset"><i class="fas fa-phone me-3"></i> +62 896-9603-6257</a></p>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Media Sosial</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: var(--primary-color); height: 2px"/>
                    <p><a href="https://www.instagram.com/snagih_id" target="_blank" class="text-reset"><i class="fab fa-instagram"></i> Instagram</a></p>
                    <p><a href="https://www.tiktok.com/@snagih._id" target="_blank" class="text-reset"><i class="fab fa-tiktok"></i> TikTok</a></p>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
        </div>
    </footer>

    
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: false,
        });
    </script>

    <script>
    // Global Live Search Script
    document.addEventListener('DOMContentLoaded', function () {
        const setupLiveSearch = (inputId, resultsId) => {
            const searchInput = document.getElementById(inputId);
            const resultsContainer = document.getElementById(resultsId);
            if (!searchInput || !resultsContainer) return;

            let debounceTimer;
            const debounce = (func, delay) => {
                return function(...args) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(this, args), delay);
                };
            };

            const performSearch = () => {
                const query = searchInput.value.trim();
                if (query.length < 2) {
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.add('d-none');
                    return;
                }

                fetch(`{{ url('/api/products/search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(products => {
                        resultsContainer.innerHTML = '';
                        if (products.length > 0) {
                            products.forEach(product => {
                                const item = document.createElement('a');
                                item.href = '{{ url("/products") }}/' + product.id;
                                item.className = 'search-result-item';
                                const price = product.harga_diskon > 0 ? product.harga_diskon : product.price;
                                item.innerHTML = `
                                    <img src="{{ asset('storage') }}/${product.image}" alt="${product.name}">
                                    <div class="search-result-details">
                                        <span class="search-result-name">${product.name}</span>
                                        <span class="search-result-price">Rp ${Number(price).toLocaleString('id-ID')}</span>
                                    </div>
                                `;
                                resultsContainer.appendChild(item);
                            });
                            resultsContainer.classList.remove('d-none');
                        } else {
                            resultsContainer.innerHTML = '<div class="p-3 text-center text-muted">Tidak ada produk ditemukan.</div>';
                            resultsContainer.classList.remove('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('Live search error:', error);
                        resultsContainer.classList.add('d-none');
                    });
            };

            searchInput.addEventListener('keyup', debounce(performSearch, 300));
            document.addEventListener('click', function (e) {
                if (!searchInput.parentElement.contains(e.target)) {
                    resultsContainer.classList.add('d-none');
                }
            });
        };

        // Setup for navbar, mobile, and hero search bars
        setupLiveSearch('navbar-search-input', 'navbar-live-search-results');
        setupLiveSearch('mobile-search-input', 'mobile-live-search-results');
        setupLiveSearch('hero-search-input', 'hero-live-search-results');
    });
    </script>

    @stack('scripts')
</body>

<!-- Login Modal -->
<div class="modal fade auth-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-flex auth-modal-img-col">
                        <img src="https://media.giphy.com/media/3o7qD4v5dppvQy0eGI/giphy.gif" alt="Food Animation">
                    </div>
                    <div class="col-lg-6 auth-modal-form-col">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Masuk</h3>
                            <p class="text-muted">Selamat datang kembali! Silakan masukkan detail Anda.</p>
                        </div>
                        <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate autocomplete="off">
                            @csrf
                            <div class="form-floating-label mb-3">
                                <input type="email" class="form-control" id="login_email_modal" name="email" required autocomplete="off">
                                <label for="login_email_modal">Email</label>
                            </div>
                            <div class="form-floating-label mb-3">
                                <input type="password" class="form-control" id="login_password_modal" name="password" required autocomplete="new-password">
                                <label for="login_password_modal">Password</label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">
                                        Ingat Saya
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">Lupa Password?</a>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                            </div>
                            <div class="d-grid">
                                <a href="{{ route('google.redirect') }}" class="btn btn-google btn-lg">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google logo">
                                    <span>Masuk dengan Google</span>
                                </a>
                            </div>
                            <p class="text-center mt-4">Belum punya akun? <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Daftar Gratis</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Register Modal -->
<div class="modal fade auth-modal" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-flex auth-modal-img-col">
                        <img src="https://media.giphy.com/media/3o7qD4v5dppvQy0eGI/giphy.gif" alt="Food Animation">
                    </div>
                    <div class="col-lg-6 auth-modal-form-col">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Buat Akun</h3>
                            <p class="text-muted">Mari mulai perjalananmu bersama kami!</p>
                        </div>
                        <form id="registerForm" method="POST" action="{{ route('register') }}" novalidate autocomplete="off">
                            @csrf
                            <div class="form-floating-label mb-3">
                                <input type="text" class="form-control" id="register_name_modal" name="name" required autofocus autocomplete="off">
                                <label for="register_name_modal">Nama Lengkap</label>
                            </div>
                            <div class="form-floating-label mb-3">
                                <input type="email" class="form-control" id="register_email_modal" name="email" required autocomplete="off">
                                <label for="register_email_modal">Email</label>
                            </div>
                            <div class="form-floating-label mb-3">
                                <textarea class="form-control" id="register_address_modal" name="address" rows="2" style="height: auto;" required></textarea>
                                <label for="register_address_modal">Alamat</label>
                            </div>
                            <div class="form-floating-label mb-3">
                                <input type="password" class="form-control" id="register_password_modal" name="password" required autocomplete="new-password">
                                <label for="register_password_modal">Password</label>
                            </div>
                            <div class="form-floating-label mb-4">
                                <input type="password" class="form-control" id="register_password_confirmation_modal" name="password_confirmation"required autocomplete="new-password">
                                <label for="register_password_confirmation_modal">Konfirmasi Password</label>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">Daftar</button>
                            </div>
                             <div class="d-grid">
                                <a href="{{ route('google.redirect') }}" class="btn btn-google btn-lg">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google logo">
                                    <span>Daftar dengan Google</span>
                                </a>
                            </div>
                            <p class="text-center mt-4">Sudah punya akun? <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Masuk di sini</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleAuthFormSubmit);
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleAuthFormSubmit);
    }
});

async function handleAuthFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;

    clearErrors(form);

    submitButton.disabled = true;
    submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: new FormData(form),
        });

        if (response.ok) {
            if (form.id === 'registerForm') {
                // Redirect to home to show the success message
                window.location.href = '/';
            } else {
                window.location.reload();
            }
        } else if (response.status === 422) {
            const data = await response.json();
            displayErrors(form, data.errors);
            // Clear password fields on error
            const passwordInput = form.querySelector('input[name="password"]');
            if (passwordInput) {
                passwordInput.value = '';
            }
            const passwordConfirmationInput = form.querySelector('input[name="password_confirmation"]');
            if (passwordConfirmationInput) {
                passwordConfirmationInput.value = '';
            }
        } else {
            displayErrors(form, { 'general': ['Terjadi kesalahan. Silakan coba lagi.'] });
        }
    } catch (error) {
        displayErrors(form, { 'general': ['Terjadi kesalahan jaringan. Periksa koneksi Anda.'] });
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    }
}

function clearErrors(form) {
    const errorMessages = form.querySelectorAll('.invalid-feedback, .alert-danger');
    errorMessages.forEach(el => el.remove());
    const invalidFields = form.querySelectorAll('.is-invalid');
    invalidFields.forEach(el => el.classList.remove('is-invalid'));
}

function displayErrors(form, errors) {
    for (const field in errors) {
        const input = form.querySelector(`[name="${field}"]`);
        const errorMessages = errors[field];
        
        if (input) {
            input.classList.add('is-invalid');
            let container = input.parentElement;
            errorMessages.forEach(message => {
                const errorEl = document.createElement('div');
                errorEl.className = 'invalid-feedback d-block';
                errorEl.innerText = message;
                container.appendChild(errorEl);
            });
        } else {
            const generalErrorContainer = document.createElement('div');
            generalErrorContainer.className = 'alert alert-danger mt-3';
            generalErrorContainer.innerHTML = errorMessages.join('<br>');
            form.prepend(generalErrorContainer);
        }
    }
}
</script>
</html>