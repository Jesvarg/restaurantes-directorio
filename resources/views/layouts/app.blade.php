<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Directorio Restaurantes') }} - @yield('title', 'Inicio')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #34495e;
            --accent-color: #f39c12;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --admin-primary: #3498db;
            --admin-success: #27ae60;
            --admin-warning: #f39c12;
            --admin-danger: #e74c3c;
            --admin-dark: #2c3e50;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .restaurant-card {
            height: 100%;
        }

        .restaurant-image {
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .price-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .rating {
            color: var(--accent-color);
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .footer {
            background-color: var(--secondary-color);
            color: white;
            margin-top: auto;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c0392b 100%);
            color: white;
            padding: 4rem 0;
        }

        .search-form {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .category-filter {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }

        .page-link {
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .text-muted {
            color: #6c757d !important;
        }

        .min-vh-100 {
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        /* Scroll to top button */
        .btn-floating {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .btn-floating:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Pagination improvements */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 6px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
        }

        .pagination .page-link:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Admin specific styles */
        .admin-header {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-dark));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .admin-card {
            border-left: 4px solid var(--admin-primary);
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('restaurants.index') }}">
                <i class="bi bi-shop me-2"></i>
                {{ config('app.name', 'Directorio Restaurantes') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('restaurants.index') ? 'active' : '' }}" href="{{ route('restaurants.index') }}">
                            <i class="bi bi-house me-1"></i>
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('restaurants.index') ? 'active' : '' }}" href="{{ route('restaurants.index') }}">
                            <i class="bi bi-search me-1"></i>
                            Buscar Restaurantes
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>
                                Registrarse
                            </a>
                        </li>
                    @else
                        <!-- Dropdown de Notificaciones -->
                        @if(count($notifications) > 0)
                        <li class="nav-item dropdown me-2">
                            <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ array_sum(array_column($notifications, 'count')) }}
                                    <span class="visually-hidden">notificaciones pendientes</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                                <li><h6 class="dropdown-header">Notificaciones</h6></li>
                                @foreach($notifications as $notification)
                                <li>
                                    <a class="dropdown-item d-flex align-items-start py-2" href="{{ $notification['url'] }}">
                                        <i class="{{ $notification['icon'] }} text-{{ $notification['color'] }} me-2 mt-1"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $notification['title'] }}</div>
                                            <small class="text-muted">{{ $notification['message'] }}</small>
                                        </div>
                                        <span class="badge bg-{{ $notification['color'] }} ms-2">{{ $notification['count'] }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->name }}
                                @if(Auth::user()->role === 'admin')
                                    <span class="badge bg-primary ms-1">Admin</span>
                                @elseif(Auth::user()->role === 'owner')
                                    <span class="badge bg-success ms-1">Propietario</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Panel Admin
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                
                                @if(Auth::user()->role === 'owner')
                                    <li><a class="dropdown-item" href="{{ route('dashboard.restaurants') }}">
                        <i class="bi bi-shop me-2"></i>Mis Restaurantes
                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('restaurants.create') }}">
                                        <i class="bi bi-plus-circle me-2"></i>Agregar Restaurante
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                
                                <li><a class="dropdown-item" href="{{ route('dashboard.favorites') }}">
                    <i class="bi bi-heart me-2"></i>Mis Favoritos
                </a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.reviews') }}">
                    <i class="bi bi-star me-2"></i>Mis Reseñas
                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                     <i class="bi bi-gear me-2"></i>Configuración
                 </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ config('app.name', 'Directorio Restaurantes') }}</h5>
                    <p class="mb-0">Descubre los mejores restaurantes de tu ciudad.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button type="button" class="btn btn-primary btn-floating" id="scrollToTopBtn" style="display: none;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Custom Scripts -->
    <script>
        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'flex';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
        
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Show success/error messages
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                timer: 4000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Errores de validación',
                html: 'Por favor corrige los siguientes errores:<br><ol style="text-align: left; margin: 10px 0;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ol>',
                confirmButtonText: 'Entendido'
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>