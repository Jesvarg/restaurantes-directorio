@extends('layouts.app')

@section('title', 'Mis Reseñas')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">
                    <i class="bi bi-chat-left-text text-info me-2"></i>
                    Mis Reseñas
                </h1>
                <span class="badge bg-primary fs-6">{{ $reviews->total() }} reseñas</span>
            </div>

            @if($reviews->count() > 0)
                <div class="row">
                    @foreach($reviews as $review)
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if($review->restaurant->photos->count() > 0)
                                                <img src="{{ asset('storage/' . $review->restaurant->photos->first()->url) }}" 
                                                     class="img-fluid rounded" 
                                                     alt="{{ $review->restaurant->name }}"
                                                     style="height: 120px; width: 100%; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 120px;">
                                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0">
                                                    <a href="{{ route('restaurants.show', $review->restaurant) }}" class="text-decoration-none">
                                                        {{ $review->restaurant->name }}
                                                    </a>
                                                </h5>
                                                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            
                                            <div class="rating mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                                                @endfor
                                                <span class="ms-2 text-muted">{{ $review->rating }}/5 estrellas</span>
                                            </div>
                                            
                                            @if($review->comment)
                                                <p class="card-text mb-2">{{ $review->comment }}</p>
                                            @else
                                                <p class="card-text text-muted fst-italic mb-2">Sin comentario</p>
                                            @endif
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('restaurants.show', $review->restaurant) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Ver Restaurante
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-chat-left-text text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 text-muted">No has escrito reseñas</h3>
                    <p class="text-muted">Visita restaurantes y comparte tu experiencia</p>
                    <a href="{{ route('restaurants.index') }}" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Explorar Restaurantes
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection