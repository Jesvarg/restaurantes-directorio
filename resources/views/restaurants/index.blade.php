@extends('layouts.app')

@section('title', 'Restaurantes')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="bi bi-geo-alt-fill me-3"></i>
                    Descubre Restaurantes Increíbles
                </h1>
                <p class="lead mb-5">
                    Encuentra los mejores lugares para comer cerca de ti.
                </p>
                
                <!-- Search Form -->
                <div class="search-form">
                    <form method="GET" action="{{ route('restaurants.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Buscar restaurantes...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-lg" name="category">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" 
                                            {{ request('category') === $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-search me-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Results -->
<div class="container my-5">
    <!-- Filter Bar -->
    <div class="category-filter">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">
                    <i class="bi bi-funnel me-2"></i>
                    Filtros
                </h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('restaurants.index') }}" class="row g-2">
                    <!-- Mantener parámetros de búsqueda existentes -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    
                    <div class="col-md-4">
                        <select class="form-select" name="price_range" onchange="this.form.submit()">
                            <option value="">Todos los precios</option>
                            <option value="1" {{ request('price_range') == '1' ? 'selected' : '' }}>$ Económico</option>
                            <option value="2" {{ request('price_range') == '2' ? 'selected' : '' }}>$$ Moderado</option>
                            <option value="3" {{ request('price_range') == '3' ? 'selected' : '' }}>$$$ Caro</option>
                            <option value="4" {{ request('price_range') == '4' ? 'selected' : '' }}>$$$$ Muy caro</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="sort" onchange="this.form.submit()">
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                            <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Mejor valorados</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más recientes</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Precio: mayor a menor</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        @if(request()->hasAny(['search', 'category', 'price_range', 'sort']))
                            <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle me-1"></i>Limpiar filtros
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Restaurantes encontrados</h4>
            <p class="text-muted mb-0">
                {{ $restaurants->total() }} {{ $restaurants->total() === 1 ? 'restaurante encontrado' : 'restaurantes encontrados' }}
                @if(request('search'))
                    para "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        </div>
        @auth
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
                <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Agregar Restaurante
                </a>
            @endif
        @endauth
    </div>

    <!-- Restaurant Grid -->
    @if($restaurants->count() > 0)
        <div class="row g-4">
            @foreach($restaurants as $restaurant)
                <div class="col-lg-4 col-md-6">
                    <div class="card restaurant-card h-100">
                        <!-- Restaurant Image -->
                        <div class="position-relative">
                            @if($restaurant->primaryPhoto)
                                <img src="{{ $restaurant->primary_photo_url }}" 
                                     class="card-img-top restaurant-image" 
                                     alt="{{ $restaurant->primaryPhoto->alt_text }}">
                            @else
                                <div class="card-img-top restaurant-image d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <!-- Price Badge -->
                            <span class="price-badge">
                                {{ $restaurant->priceRangeDisplay }}
                            </span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Restaurant Name -->
                            <h5 class="card-title mb-2">
                                <a href="{{ route('restaurants.show', $restaurant) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $restaurant->name }}
                                </a>
                            </h5>

                            <!-- Categories -->
                            <div class="mb-2">
                                @foreach($restaurant->categories->take(3) as $category)
                                    <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                                @endforeach
                                @if($restaurant->categories->count() > 3)
                                    <span class="badge bg-light text-dark">+{{ $restaurant->categories->count() - 3 }}</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($restaurant->description, 100) }}
                            </p>

                            <!-- Rating and Reviews -->
                            <div class="d-flex align-items-center mb-3">
                                @if($restaurant->averageRating)
                                    <div class="rating me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $restaurant->averageRating)
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $restaurant->averageRating)
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="fw-bold me-2">{{ number_format($restaurant->averageRating, 1) }}</span>
                                    <small class="text-muted">({{ $restaurant->reviewsCount }} {{ $restaurant->reviewsCount === 1 ? 'reseña' : 'reseñas' }})</small>
                                @else
                                    <small class="text-muted">Sin reseñas aún</small>
                                @endif
                            </div>

                            <!-- Address -->
                            <div class="d-flex align-items-start mb-3 mt-auto">
                                <i class="bi bi-geo-alt text-muted me-2 mt-1"></i>
                                <small class="text-muted">{{ Str::limit($restaurant->address, 60) }}</small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('restaurants.show', $restaurant) }}" 
                                   class="btn btn-primary flex-fill">
                                    <i class="bi bi-eye me-1"></i>Ver detalles
                                </a>
                                @auth
                                    @if(Auth::user()->favorites()->where('restaurant_id', $restaurant->id)->exists())
                                        <button class="btn btn-outline-danger" 
                                                onclick="toggleFavorite({{ $restaurant->id }})" 
                                                id="favorite-btn-{{ $restaurant->id }}"
                                                data-restaurant-id="{{ $restaurant->id }}">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-outline-secondary" 
                                                onclick="toggleFavorite({{ $restaurant->id }})" 
                                                id="favorite-btn-{{ $restaurant->id }}"
                                                data-restaurant-id="{{ $restaurant->id }}">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Navegación de páginas">
                {{ $restaurants->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-4"></i>
            <h3 class="text-muted mb-3">No se encontraron restaurantes</h3>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'category', 'price_range']))
                    Intenta ajustar tus filtros de búsqueda o 
                    <a href="{{ route('restaurants.index') }}" class="text-decoration-none">ver todos los restaurantes</a>.
                @else
                    Aún no hay restaurantes registrados en el directorio.
                @endif
            </p>
            @auth
                <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Agregar el primer restaurante
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>Regístrate para agregar restaurantes
                </a>
            @endauth
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/form-utils.js') }}"></script>
<script>
    // Configurar variable global para autenticación
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/favorites.js') }}"></script>
<script>

// Auto-submit search form on Enter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    }
});

// La función showFavoriteSuccess está disponible como showSuccessMessage en form-utils.js
</script>
@endpush