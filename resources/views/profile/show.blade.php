@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Profile Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} fs-6">
                        {{ $user->role === 'admin' ? 'Administrador' : 'Usuario' }}
                    </span>
                </div>
            </div>

            <!-- Profile Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-shop text-primary mb-2" style="font-size: 2rem;"></i>
                            <h4 class="mb-1">{{ $stats['restaurants_count'] }}</h4>
                            <p class="text-muted mb-0">Restaurantes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-heart-fill text-danger mb-2" style="font-size: 2rem;"></i>
                            <h4 class="mb-1">{{ $stats['favorites_count'] }}</h4>
                            <p class="text-muted mb-0">Favoritos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-chat-left-text text-success mb-2" style="font-size: 2rem;"></i>
                            <h4 class="mb-1">{{ $stats['reviews_count'] }}</h4>
                            <p class="text-muted mb-0">Reseñas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Información del Perfil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Nombre:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->name }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->email }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Rol:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                {{ $user->role === 'admin' ? 'Administrador' : 'Usuario' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Estado:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                {{ $user->status === 'active' ? 'Activo' : 'Suspendido' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Miembro desde:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        @if($user->role === 'admin' || $user->role === 'owner')
                        <a href="{{ route('dashboard.restaurants') }}" class="btn btn-outline-primary">
                            <i class="bi bi-shop me-1"></i>Mis Restaurantes
                        </a>
                        @endif
                        <a href="{{ route('dashboard.favorites') }}" class="btn btn-outline-danger">
                            <i class="bi bi-heart me-1"></i>Mis Favoritos
                        </a>
                        <a href="{{ route('dashboard.reviews') }}" class="btn btn-outline-success">
                            <i class="bi bi-chat-left-text me-1"></i>Mis Reseñas
                        </a>
                        @if($user->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-warning">
                            <i class="bi bi-gear me-1"></i>Panel Admin
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection