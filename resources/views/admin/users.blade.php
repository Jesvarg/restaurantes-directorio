@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Panel de Administración')

@section('content')
<div class="container my-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-people me-2"></i>
                        Gestión de Usuarios
                    </h2>
                    <p class="text-muted mb-0">Administra los usuarios registrados en la plataforma</p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalUsers }}</h4>
                            <small>Total Usuarios</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $activeUsers }}</h4>
                            <small>Usuarios Activos</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-check fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $suspendedUsers }}</h4>
                            <small>Usuarios Suspendidos</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-x fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $adminUsers }}</h4>
                            <small>Administradores</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-shield-check fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nombre o email...">
                </div>
                <div class="col-md-2">
                    <label for="role" class="form-label">Rol</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Todos los roles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Usuarios -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Usuarios ({{ $users->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Restaurantes</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info ms-1">Tú</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Administrador</span>
                                @else
                                    <span class="badge bg-secondary">Usuario</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $user->restaurants_count }}</span>
                            </td>
                            <td>
                                @if($user->suspended_at)
                                    <span class="badge bg-warning">Suspendido</span>
                                    @if($user->suspension_reason)
                                        <i class="bi bi-info-circle ms-1" 
                                           title="{{ $user->suspension_reason }}" 
                                           data-bs-toggle="tooltip"></i>
                                    @endif
                                @else
                                    <span class="badge bg-success">Activo</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($user->role !== 'admin' && $user->id !== auth()->id())
                                        @if($user->suspended_at)
                                            <!-- Reactivar -->
                                            <button type="button" 
                                                    class="btn btn-outline-success btn-sm" 
                                                    title="Reactivar usuario"
                                                    onclick="confirmReactivateUser(event, '{{ $user->name }}', '{{ route('admin.users.reactivate', $user) }}')">
                                                <i class="bi bi-person-check"></i>
                                            </button>
                                        @else
                                            <!-- Suspender -->
                                            <button type="button" 
                                                    class="btn btn-outline-warning btn-sm" 
                                                    title="Suspender usuario"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#suspendUserModal"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}">
                                                <i class="bi bi-person-x"></i>
                                            </button>
                                        @endif
                                        
                                        <!-- Eliminar -->
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                title="Eliminar usuario"
                                                onclick="confirmDeleteUser(event, '{{ $user->name }}', '{{ route('admin.users.delete', $user) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <span class="text-muted small">Sin acciones</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            @if($users->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3">No se encontraron usuarios</h4>
                <p class="text-muted">No hay usuarios que coincidan con los filtros aplicados.</p>
            </div>
            @endif
        </div>
    </div>
</div>



<!-- Suspend User Modal -->
<div class="modal fade" id="suspendUserModal" tabindex="-1" aria-labelledby="suspendUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suspendUserModalLabel">Suspender Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="suspendUserForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>¿Está seguro de que desea suspender al usuario <strong id="suspendUserName"></strong>?</p>
                    <div class="mb-3">
                        <label for="userSuspensionReason" class="form-label">Razón de la suspensión <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="userSuspensionReason" name="suspension_reason" rows="3" required placeholder="Explique por qué se suspende este usuario..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Suspender Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #6c757d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/admin-actions.js') }}"></script>
<script>
// Las funciones confirmReactivateUser y confirmDeleteUser están disponibles en admin-actions.js

document.addEventListener('DOMContentLoaded', function() {
    
    // Handle suspend user modal
    const suspendUserModal = document.getElementById('suspendUserModal');
    if (suspendUserModal) {
        suspendUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            
            const form = document.getElementById('suspendUserForm');
            const nameElement = document.getElementById('suspendUserName');
            
            form.action = `/admin/users/${userId}/suspend`;
            nameElement.textContent = userName;
            
            // Clear previous reason
            document.getElementById('userSuspensionReason').value = '';
        });
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush