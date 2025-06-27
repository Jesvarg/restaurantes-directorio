@extends('layouts.app')

@section('title', 'Restaurantes Pendientes')

@section('content')
<div class="container my-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-clock-history me-2"></i>
                        Restaurantes Pendientes
                    </h2>
                    <p class="text-muted mb-0">Revisa y aprueba los restaurantes enviados por los usuarios</p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $restaurants->total() }}</h3>
                    <small>Restaurantes Pendientes</small>
                </div>
            </div>
        </div>
    </div>

    @if($restaurants->count() > 0)
    <!-- Restaurants List -->
    <div class="row">
        @foreach($restaurants as $restaurant)
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $restaurant->name }}</h5>
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i>
                            {{ $restaurant->user->name }}
                        </small>
                    </div>
                    <span class="badge bg-warning">{{ ucfirst($restaurant->status) }}</span>
                </div>
                
                @if($restaurant->primaryPhoto)
                <div class="position-relative">
                    <img src="{{ $restaurant->primaryPhoto->url }}" 
                         class="card-img-top" 
                         style="height: 200px; object-fit: cover;" 
                         alt="{{ $restaurant->name }}">
                    @if($restaurant->photos->count() > 1)
                    <span class="position-absolute top-0 end-0 m-2 badge bg-dark">
                        <i class="bi bi-images me-1"></i>
                        {{ $restaurant->photos->count() }}
                    </span>
                    @endif
                </div>
                @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
                @endif
                
                <div class="card-body">
                    <!-- Description -->
                    <p class="card-text text-muted mb-3">
                        {{ Str::limit($restaurant->description, 120) }}
                    </p>
                    
                    <!-- Details -->
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <small class="text-muted">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ Str::limit($restaurant->address, 50) }}
                            </small>
                        </div>
                        @if($restaurant->phone)
                        <div class="col-12 mb-2">
                            <small class="text-muted">
                                <i class="bi bi-telephone me-1"></i>
                                {{ $restaurant->phone }}
                            </small>
                        </div>
                        @endif
                        @if($restaurant->email)
                        <div class="col-12 mb-2">
                            <small class="text-muted">
                                <i class="bi bi-envelope me-1"></i>
                                {{ $restaurant->email }}
                            </small>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Categories -->
                    <div class="mb-3">
                        @foreach($restaurant->categories as $category)
                            <span class="badge bg-secondary me-1 mb-1">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    
                    <!-- Price Range -->
                    @if($restaurant->price_range)
                    <div class="mb-3">
                        <small class="text-muted">Rango de precios: </small>
                        <span class="fw-bold">
                            @for($i = 1; $i <= $restaurant->price_range; $i++)
                                €
                            @endfor
                        </span>
                    </div>
                    @endif
                    
                    <!-- Submission Date -->
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            Enviado: {{ $restaurant->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <!-- View Details -->
                        <a href="{{ route('restaurants.show', $restaurant) }}" 
                           class="btn btn-outline-info flex-fill" 
                           target="_blank">
                            <i class="bi bi-eye me-1"></i>
                            Ver Detalles
                        </a>
                        
                        <!-- Approve -->
                        <form method="POST" action="{{ route('admin.restaurants.approve', $restaurant) }}" class="flex-fill">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-success w-100" 
                                    onclick="return confirm('¿Aprobar el restaurante \"{{ $restaurant->name }}\"?')">
                                <i class="bi bi-check-lg me-1"></i>
                                Aprobar
                            </button>
                        </form>
                        
                        <!-- Reject -->
                        <form method="POST" action="{{ route('admin.restaurants.reject', $restaurant) }}" class="flex-fill">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-danger w-100" 
                                    onclick="return confirm('¿Rechazar el restaurante \"{{ $restaurant->name }}\"? Esta acción no se puede deshacer.')">
                                <i class="bi bi-x-lg me-1"></i>
                                Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($restaurants->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $restaurants->links() }}
            </div>
        </div>
    </div>
    @endif
    
    @else
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 mb-2">¡Todo al día!</h4>
                    <p class="text-muted mb-4">No hay restaurantes pendientes de aprobación en este momento.</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div class="toast show" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Éxito</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div class="toast show" role="alert">
        <div class="toast-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Mejorar confirmaciones con SweetAlert2 (opcional)
document.addEventListener('DOMContentLoaded', function() {
    // Confirmación para aprobar
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const restaurantName = this.dataset.restaurant;
            
            if (confirm(`¿Está seguro de que desea APROBAR el restaurante "${restaurantName}"?\n\nEsta acción permitirá que el restaurante sea visible públicamente.`)) {
                form.submit();
            }
        });
    });
    
    // Confirmación para rechazar
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const restaurantName = this.dataset.restaurant;
            
            const reason = prompt(`¿Por qué desea RECHAZAR el restaurante "${restaurantName}"?\n\nProporcione una razón:`);
            if (reason && reason.trim()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'reason';
                input.value = reason.trim();
                form.appendChild(input);
                form.submit();
            }
        });
    });
});
</script>
@endpush

<!-- Auto-hide toasts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(function(toast) {
        setTimeout(function() {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });
});
</script>
@endpush