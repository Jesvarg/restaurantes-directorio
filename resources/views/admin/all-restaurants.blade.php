@extends('layouts.app')

@section('title', 'Todos los Restaurantes')

@section('content')
<div class="container my-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-list-ul me-2"></i>
                        Todos los Restaurantes
                    </h2>
                    <p class="text-muted mb-0">Gestiona todos los restaurantes del sistema</p>
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

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>
                        Filtros
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.restaurants.all') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">Buscar</label>
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       class="form-control" 
                                       placeholder="Nombre o dirección..."
                                       value="{{ request('search') }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="sort" class="form-label">Ordenar por</label>
                                <select name="sort" id="sort" class="form-select">
                                    <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Más recientes</option>
                                    <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Más antiguos</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre Z-A</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="w-100">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search me-2"></i>
                                        Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if(request()->hasAny(['status', 'search', 'sort']))
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('admin.restaurants.all') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Limpiar filtros
                                </a>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $restaurants->total() }}</h4>
                    <small>Total Mostrados</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $statusCounts['approved'] ?? 0 }}</h4>
                    <small>Aprobados</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $statusCounts['pending'] ?? 0 }}</h4>
                    <small>Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ ($statusCounts['rejected'] ?? 0) + ($statusCounts['suspended'] ?? 0) }}</h4>
                    <small>Rechazados/Suspendidos</small>
                </div>
            </div>
        </div>
    </div>

    @if($restaurants->count() > 0)
    <!-- Restaurants Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Restaurante</th>
                                    <th>Propietario</th>
                                    <th>Estado</th>
                                    <th>Categorías</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurants as $restaurant)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($restaurant->primaryPhoto)
                                                <img src="{{ $restaurant->primaryPhoto->url }}" 
                                                     class="rounded me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;" 
                                                     alt="{{ $restaurant->name }}">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $restaurant->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($restaurant->address, 40) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $restaurant->user->name }}
                                            <br>
                                            <small class="text-muted">{{ $restaurant->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($restaurant->status)
                                            @case('approved')
                                                <span class="badge bg-success">Aprobado</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pendiente</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rechazado</span>
                                                @break
                                            @case('suspended')
                                                <span class="badge bg-secondary">Suspendido</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($restaurant->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @foreach($restaurant->categories->take(2) as $category)
                                            <span class="badge bg-secondary me-1 mb-1">{{ $category->name }}</span>
                                        @endforeach
                                        @if($restaurant->categories->count() > 2)
                                            <br><small class="text-muted">+{{ $restaurant->categories->count() - 2 }} más</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $restaurant->created_at->format('d/m/Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $restaurant->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <!-- View -->
                                            <a href="{{ route('restaurants.show', $restaurant) }}" 
                                               class="btn btn-outline-info" 
                                               title="Ver" 
                                               target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <!-- Status Actions -->
                                            @switch($restaurant->status)
                                                @case('pending')
                                                    <!-- Approve -->
                                                    <form method="POST" action="{{ route('admin.restaurants.approve', $restaurant) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-success" 
                                                                title="Aprobar"
                                                                onclick="return confirm('¿Aprobar este restaurante?')">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                    <!-- Reject -->
                                                    <form method="POST" action="{{ route('admin.restaurants.reject', $restaurant) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger" 
                                                                title="Rechazar"
                                                                onclick="return confirm('¿Rechazar este restaurante?')">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </form>
                                                    @break
                                                    
                                                @case('approved')
                                                    <!-- Suspend -->
                                                    <form method="POST" action="{{ route('admin.restaurants.suspend', $restaurant) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-warning" 
                                                                title="Suspender"
                                                                onclick="return confirm('¿Suspender este restaurante?')">
                                                            <i class="bi bi-pause"></i>
                                                        </button>
                                                    </form>
                                                    @break
                                                    
                                                @case('suspended')
                                                    <!-- Reactivate -->
                                                    <form method="POST" action="{{ route('admin.restaurants.reactivate', $restaurant) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-success" 
                                                                title="Reactivar"
                                                                onclick="return confirm('¿Reactivar este restaurante?')">
                                                            <i class="bi bi-play"></i>
                                                        </button>
                                                    </form>
                                                    @break
                                                    
                                                @case('rejected')
                                                    <!-- Approve (second chance) -->
                                                    <form method="POST" action="{{ route('admin.restaurants.approve', $restaurant) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-success" 
                                                                title="Aprobar"
                                                                onclick="return confirm('¿Aprobar este restaurante?')">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                    @break
                                            @endswitch
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($restaurants->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $restaurants->appends(request()->query())->links() }}
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
                    <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 mb-2">No se encontraron restaurantes</h4>
                    <p class="text-muted mb-4">Intenta ajustar los filtros de búsqueda.</p>
                    <a href="{{ route('admin.restaurants.all') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Mostrar todos
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
// Auto-hide toasts after 5 seconds
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