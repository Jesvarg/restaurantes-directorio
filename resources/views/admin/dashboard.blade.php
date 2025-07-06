@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container my-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-shield-check me-2"></i>
                        Panel de Administración
                    </h2>
                    <p class="text-muted mb-0">Gestiona restaurantes y usuarios del sistema</p>
                </div>
                <div class="text-end">
                    <small class="text-muted">Administrador: {{ Auth::user()->name }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $pendingRestaurants }}</h4>
                            <small>Pendientes</small>
                        </div>
                        <i class="bi bi-clock-history fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $totalRestaurants }}</h4>
                            <small>Total Restaurantes</small>
                        </div>
                        <i class="bi bi-shop fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $totalUsers }}</h4>
                            <small>Total Usuarios</small>
                        </div>
                        <i class="bi bi-people fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ number_format((($totalRestaurants - $pendingRestaurants) / max($totalRestaurants, 1)) * 100, 1) }}%</h4>
                            <small>Aprobados</small>
                        </div>
                        <i class="bi bi-check-circle fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.restaurants.pending') }}" class="btn btn-warning w-100">
                                <i class="bi bi-clock-history me-2"></i>
                                Revisar Pendientes ({{ $pendingRestaurants }})
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.restaurants.all') }}" class="btn btn-primary w-100">
                                <i class="bi bi-list-ul me-2"></i>
                                Ver Todos los Restaurantes
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.users') }}" class="btn btn-info w-100">
                                <i class="bi bi-people me-2"></i>
                                Gestionar Usuarios ({{ $totalUsers }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pending Restaurants -->
    @if($recentRestaurants->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock me-2"></i>
                        Restaurantes Pendientes Recientes
                    </h5>
                    <a href="{{ route('admin.restaurants.pending') }}" class="btn btn-sm btn-outline-primary">
                        Ver todos
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Restaurante</th>
                                    <th>Propietario</th>
                                    <th>Categorías</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRestaurants as $restaurant)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($restaurant->primaryPhoto)
                                                <img src="{{ $restaurant->primaryPhoto->url }}" 
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;" 
                                                     alt="{{ $restaurant->name }}">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $restaurant->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($restaurant->address, 30) }}</small>
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
                                        @foreach($restaurant->categories->take(2) as $category)
                                            <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                        @endforeach
                                        @if($restaurant->categories->count() > 2)
                                            <span class="text-muted small">+{{ $restaurant->categories->count() - 2 }} más</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $restaurant->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('restaurants.show', $restaurant) }}" 
                                               class="btn btn-outline-info" 
                                               title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
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
    @else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                    <h5>¡Todo al día!</h5>
                    <p class="text-muted mb-0">No hay restaurantes pendientes de aprobación.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection