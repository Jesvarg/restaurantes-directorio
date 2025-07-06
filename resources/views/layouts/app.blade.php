<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Agregar en el head --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Directorio Restaurantes') }} u- @yield('title', 'Inicio')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('restaurants.index') }}">
                <i class="bi bi-geo-alt-fill me-2 fs-4"></i>
                {{ config('app.name', 'Directorio Restaurantes') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Navegación principal removida según solicitud del usuario -->
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard.favorites') }}">
                                    <i class="bi bi-heart me-2"></i>Mis Favoritos
                                </a></li>
                                @if(Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('dashboard.restaurants') }}">
                                    <i class="bi bi-shop me-2"></i>Mis Restaurantes
                                </a></li>
                                @endif
                                @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-check me-2"></i>Panel de Admin
                                </a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('dashboard.reviews') }}">
                                    <i class="bi bi-chat-left-text me-2"></i>Mis Reseñas
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

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-white mb-3">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        {{ config('app.name', 'Directorio de Restaurantes') }}
                    </h5>
                    <p class="text-light mb-0">
                        Descubre los mejores restaurantes de tu ciudad   y disfruta experiencias gastronómicas únicas.
                    </p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Contacto</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-telephone-fill me-2"></i>
                            <span class="text-light">+1 (555) 123-4567</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope-fill me-2"></i>
                            <a href="mailto:info@restaurantes.com" class="text-light text-decoration-none">info@restaurantes.com</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            <span class="text-light">123 Calle Principal, Ciudad</span>
                        </li>
                        <li>
                            <i class="bi bi-clock-fill me-2"></i>
                            <span class="text-light">Lun-Dom: 24/7</span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white mb-3">Síguenos</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light fs-4"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light fs-4"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-light">Hecho con <i class="bi bi-heart-fill text-danger"></i> para los amantes de la buena comida</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    @stack('scripts')
</body>
</html>

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






</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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
</body>
</html>

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