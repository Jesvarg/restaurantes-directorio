@extends('layouts.app')

@section('title', 'Mis Restaurantes')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">
                    <i class="bi bi-shop text-primary me-2"></i>
                    Mis Restaurantes
                </h1>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-primary fs-6">{{ $restaurants->total() }} restaurantes</span>
                    <a href="{{ route('restaurants.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>Nuevo Restaurante
                    </a>
                </div>
            </div>

            @if($restaurants->count() > 0)
                <div class="row">
                    @foreach($restaurants as $restaurant)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                @if($restaurant->photos->count() > 0)
                                    <img src="{{ asset('storage/' . $restaurant->photos->first()->url) }}" 
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
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $restaurant->name }}</h5>
                                        <span class="badge bg-{{ $restaurant->status === 'active' ? 'success' : ($restaurant->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($restaurant->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="card-text text-muted small mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $restaurant->address }}
                                    </p>
                                    <p class="card-text flex-grow-1">{{ Str::limit($restaurant->description, 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="rating">
                                            @php
                                                $avgRating = $restaurant->reviews->avg('rating') ?? 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $avgRating ? '-fill text-warning' : ' text-muted' }}"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $restaurant->reviews->count() }})</small>
                                        </div>
                                        <span class="badge bg-success">${{ $restaurant->price_range }}</span>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </a>
                                        <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </a>
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
                    <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 text-muted">No tienes restaurantes registrados</h3>
                    <p class="text-muted">Comienza agregando tu primer restaurante</p>
                    <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Agregar Restaurante
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection