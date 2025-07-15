@extends('layouts.app')

@section('title', $restaurant->name)

@section('content')
<div class="container my-4">
    <!-- Enhanced Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('restaurants.index') }}" class="text-decoration-none">
                        <i class="bi bi-house-door me-1"></i>Restaurantes
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($restaurant->name, 30) }}
                </li>
            </ol>
            
        </div>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Restaurant Header -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <!-- Title and Status Row -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <h1 class="h2 me-3 mb-0 text-dark" style="word-break: break-word; max-width: 100%; line-height: 1.2;">{{ $restaurant->name }}</h1>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="bi bi-currency-dollar me-1"></i>{{ $restaurant->priceRangeDisplay }}
                                    </span>
                                    @if($restaurant->status === 'approved')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>Verificado
                                        </span>
                                    @elseif($restaurant->status === 'pending')
                                        <span class="badge bg-warning px-3 py-2">
                                            <i class="bi bi-clock me-1"></i>Pendiente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                         @auth
                             <div class="d-flex gap-2">
                                 <button class="btn {{ $isFavorite ? 'btn-danger' : 'btn-outline-secondary' }} btn-lg" 
                                         onclick="toggleFavorite({{ $restaurant->id }})" 
                                         id="favorite-btn"
                                         data-restaurant-id="{{ $restaurant->id }}"
                                         title="{{ $isFavorite ? 'Quitar de favoritos' : 'Agregar a favoritos' }}">
                                     <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                 </button>
                                 @if($canEdit)
                                     <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-outline-primary btn-lg">
                                         <i class="bi bi-pencil"></i>
                                     </a>
                                 @endif
                             </div>
                         @endauth
                    </div>
                    
                    <!-- Rating and Reviews Row -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            @if($restaurant->averageRating)
                                <div class="rating me-3" style="color: #ffc107; font-size: 1.2rem;">
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
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold fs-5 me-2">{{ number_format($restaurant->averageRating, 1) }}</span>
                                    <span class="text-muted">
                                        ({{ $restaurant->reviewsCount }} {{ $restaurant->reviewsCount === 1 ? 'reseña' : 'reseñas' }})
                                    </span>
                                </div>
                            @else
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-star me-2" style="font-size: 1.2rem;"></i>
                                    <span>Sin reseñas aún</span>
                                </div>
                            @endif
                        </div>
                        
                        @auth
                            @if(!$restaurant->reviews->where('user_id', Auth::id())->count())
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                    <i class="bi bi-plus-circle me-1"></i>Escribir reseña
                                </button>
                            @endif
                        @endauth
                    </div>
                    
                    <!-- Categories Row -->
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($restaurant->categories as $category)
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="bi bi-tag me-1"></i>{{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Photos Gallery -->
            @if($restaurant->photos->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-images me-2"></i>Fotos</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($restaurant->photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $photo->full_url }}" 
                                             class="d-block w-100" 
                                             style="height: 400px; object-fit: cover;"
                                             alt="{{ $photo->alt_text }}">
                                    </div>
                                @endforeach
                            </div>
                            @if($restaurant->photos->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                                <div class="carousel-indicators">
                                    @foreach($restaurant->photos as $index => $photo)
                                        <button type="button" data-bs-target="#photoCarousel" data-bs-slide-to="{{ $index }}" 
                                                class="{{ $index === 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Description -->
            @if($restaurant->description)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $restaurant->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Reviews Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Reseñas ({{ $restaurant->reviewsCount }})</h5>
                    @auth
                        @if(!$restaurant->reviews->where('user_id', Auth::id())->count())
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="bi bi-plus-circle me-1"></i>Escribir reseña
                            </button>
                        @endif
                    @endauth
                </div>
                <div class="card-body">
                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $review->user->name }}</h6>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                        
                        <!-- Paginación de reseñas -->
                        @if($reviews->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-chat-dots display-4 text-muted mb-3"></i>
                            <h6 class="text-muted">Aún no hay reseñas</h6>
                            <p class="text-muted mb-0">Sé el primero en compartir tu experiencia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información de contacto</h5>
                </div>
                <div class="card-body">
                    <!-- Address -->
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt text-primary me-3 mt-1"></i>
                        <div>
                            <strong>Dirección</strong><br>
                            <span class="text-muted">{{ $restaurant->address }}</span>
                        </div>
                    </div>

                    <!-- Phone -->
                    @if($restaurant->phone)
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-telephone text-primary me-3"></i>
                            <div>
                                <strong>Teléfono</strong><br>
                                <a href="tel:{{ $restaurant->phone }}" class="text-decoration-none">{{ $restaurant->phone }}</a>
                            </div>
                        </div>
                    @endif

                    <!-- Email -->
                    @if($restaurant->email)
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope text-primary me-3"></i>
                            <div>
                                <strong>Email</strong><br>
                                <a href="mailto:{{ $restaurant->email }}" class="text-decoration-none">{{ $restaurant->email }}</a>
                            </div>
                        </div>
                    @endif

                    <!-- Website -->
                    @if($restaurant->website)
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-globe text-primary me-3"></i>
                            <div>
                                <strong>Sitio web</strong><br>
                                <a href="{{ $restaurant->website }}" target="_blank" class="text-decoration-none">
                                    {{ parse_url($restaurant->website, PHP_URL_HOST) }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Opening Hours -->
            @if($restaurant->opening_hours)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Horarios de atención</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $days = [
                                'monday' => 'Lunes',
                                'tuesday' => 'Martes',
                                'wednesday' => 'Miércoles',
                                'thursday' => 'Jueves',
                                'friday' => 'Viernes',
                                'saturday' => 'Sábado',
                                'sunday' => 'Domingo'
                            ];
                        @endphp
                        @foreach($days as $day => $dayName)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $dayName }}</span>
                                <span class="text-muted">
                                    {{ $restaurant->opening_hours[$day] ?? 'No especificado' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Map placeholder -->
            @if($restaurant->latitude && $restaurant->longitude)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-map me-2"></i>Ubicación</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 250px; background: #f8f9fa;" class="d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <i class="bi bi-geo-alt display-4 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Mapa disponible próximamente</p>
                                <small class="text-muted">Lat: {{ $restaurant->latitude }}, Lng: {{ $restaurant->longitude }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Owner Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Propietario</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $restaurant->user->name }}</h6>
                            <small class="text-muted">Miembro desde {{ $restaurant->user->created_at->format('M Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
@auth
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Escribir reseña para {{ $restaurant->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('restaurants.reviews.store', $restaurant) }}" method="POST">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Calificación *</label>
                            <div class="rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comentario (opcional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" 
                                      placeholder="Comparte tu experiencia..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Publicar reseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endauth
@endsection

@push('styles')
<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star-label {
    color: #ddd;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input .star-label:hover,
.rating-input .star-label:hover ~ .star-label {
    color: #f39c12;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/form-utils.js') }}"></script>
<script>
    // Configurar variable global para autenticación
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/favorites.js') }}"></script>
@endpush