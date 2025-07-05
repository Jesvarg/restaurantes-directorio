@extends('layouts.app')

@section('title', 'Mis Favoritos')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">
                    <i class="bi bi-heart-fill text-danger me-2"></i>
                    Mis Favoritos
                </h1>
                <span class="badge bg-primary fs-6">{{ $restaurants->total() }} restaurantes</span>
            </div>

            @if($restaurants->count() > 0)
                <div class="row">
                    @foreach($restaurants as $restaurant)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                @if($restaurant->photos->count() > 0)
                                    <img src="{{ $restaurant->primary_photo_url }}" 
                                         class="card-img-top" 
                                         alt="{{ $restaurant->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $restaurant->address }}
                                    </p>
                                    <p class="card-text flex-grow-1">{{ Str::limit($restaurant->description, 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="rating">
                                            @php
                                                $avgRating = $restaurant->reviews->avg('rating') ?? 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $avgRating ? '-fill text-warning' : ' text-muted' }}"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $restaurant->reviews->count() }})</small>
                                        </div>
                                        <span class="badge bg-success">{{ $restaurant->priceRangeDisplay }}</span>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="bi bi-eye me-1"></i>Ver Detalles
                                        </a>
                                        <form action="{{ route('restaurants.favorite', $restaurant) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Quitar de favoritos">
                                                <i class="bi bi-heart-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $restaurants->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 text-muted">No tienes restaurantes favoritos</h3>
                    <p class="text-muted">Explora nuestros restaurantes y marca tus favoritos</p>
                    <a href="{{ route('restaurants.index') }}" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Explorar Restaurantes
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection