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
                    <a href="{{ route('restaurants.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>Nuevo Restaurante
                    </a>
                </div>
            </div>
            
            <!-- Notificaciones de rechazo -->
            @foreach($restaurants->where('status', 'rejected') as $rejectedRestaurant)
                @php
                    $latestRejection = $rejectedRestaurant->getLatestRejectionReason();
                @endphp
                @if($latestRejection)
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill me-3 mt-1"></i>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2">
                                <strong>Restaurante "{{ $rejectedRestaurant->name }}" rechazado</strong>
                            </h6>
                            <p class="mb-2">Su restaurante ha sido rechazado por las siguientes razones:</p>
                            <ul class="mb-2">
                                @foreach($latestRejection->getInvalidFields() as $field)
                                    <li>
                                        @switch($field)
                                            @case('name_invalid')
                                                El nombre del restaurante es inválido o inapropiado
                                                @break
                                            @case('description_invalid')
                                                La descripción necesita ser mejorada o es inadecuada
                                                @break
                                            @case('address_invalid')
                                                La dirección está incorrecta o incompleta
                                                @break
                                            @case('contact_invalid')
                                                La información de contacto (teléfono/email) es inválida
                                                @break
                                            @case('photos_missing')
                                                Faltan fotos o las existentes son inadecuadas
                                                @break
                                            @case('categories_invalid')
                                                Las categorías seleccionadas son incorrectas
                                                @break
                                            @case('duplicate_restaurant')
                                                Este restaurante ya existe en nuestro directorio
                                                @break
                                            @case('other_reason')
                                                Otros motivos (ver notas adicionales)
                                                @break
                                        @endswitch
                                    </li>
                                @endforeach
                            </ul>
                            @if($latestRejection->notes)
                                <div class="alert alert-secondary mb-2">
                                    <strong>Notas del administrador:</strong><br>
                                    {{ $latestRejection->notes }}
                                </div>
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('restaurants.edit', $rejectedRestaurant) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Corregir y Reenviar
                                </a>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            @endforeach

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
                                        <span class="badge bg-success">{{ $restaurant->priceRangeDisplay }}</span>
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
                    {{ $restaurants->links('pagination::bootstrap-4') }}
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