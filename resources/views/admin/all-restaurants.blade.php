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
                            @if($restaurant->photos->count() > 0)
                                <img src="{{ $restaurant->primary_photo_url }}" 
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
                                                                onclick="confirmApprove(event, '{{ $restaurant->name }}', '{{ route('admin.restaurants.approve', $restaurant) }}')">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                    <!-- Reject -->
                                                    <button type="button" 
                                                            class="btn btn-outline-danger" 
                                                            title="Rechazar"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal"
                                                            data-restaurant-id="{{ $restaurant->id }}"
                                                            data-restaurant-name="{{ $restaurant->name }}">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                    @break
                                                    
                                                @case('approved')
                                                    <!-- Suspend -->
                                                    <button type="button" 
                                                            class="btn btn-outline-warning" 
                                                            title="Suspender"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#suspendModal"
                                                            data-restaurant-id="{{ $restaurant->id }}"
                                                            data-restaurant-name="{{ $restaurant->name }}">
                                                        <i class="bi bi-pause"></i>
                                                    </button>
                                                    @break
                                                    
                                                @case('suspended')
                                                    <!-- Reactivate -->
                                                    <button type="button" 
                                                            class="btn btn-outline-success" 
                                                            title="Reactivar"
                                                            onclick="confirmReactivate(event, '{{ $restaurant->name }}', '{{ route('admin.restaurants.reactivate', $restaurant) }}')">
                                                        <i class="bi bi-play"></i>
                                                    </button>
                                                    @break
                                                    
                                                @case('rejected')
                                                    <!-- Approve (second chance) -->
                                                    <button type="button" 
                                                            class="btn btn-outline-success" 
                                                            title="Aprobar"
                                                            onclick="confirmApprove(event, '{{ $restaurant->name }}', '{{ route('admin.restaurants.approve', $restaurant) }}')">
                                                        <i class="bi bi-check"></i>
                                                    </button>
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
                {{ $restaurants->appends(request()->query())->links('pagination::bootstrap-4') }}
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



<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="bi bi-x-circle me-2"></i>
                    Rechazar Restaurante: <span id="rejectRestaurantName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Seleccione los motivos específicos del rechazo:</strong>
                        <br><small>El propietario recibirá una notificación detallada con los campos que debe corregir.</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Campos Obligatorios</h6>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[name_invalid]" id="nameInvalid" value="1">
                                <label class="form-check-label" for="nameInvalid">
                                    <i class="bi bi-shop me-1"></i> Nombre del restaurante
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[description_invalid]" id="descriptionInvalid" value="1">
                                <label class="form-check-label" for="descriptionInvalid">
                                    <i class="bi bi-file-text me-1"></i> Descripción
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[address_invalid]" id="addressInvalid" value="1">
                                <label class="form-check-label" for="addressInvalid">
                                    <i class="bi bi-geo-alt me-1"></i> Dirección
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[phone_invalid]" id="phoneInvalid" value="1">
                                <label class="form-check-label" for="phoneInvalid">
                                    <i class="bi bi-telephone me-1"></i> Teléfono
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[email_invalid]" id="emailInvalid" value="1">
                                <label class="form-check-label" for="emailInvalid">
                                    <i class="bi bi-envelope me-1"></i> Email
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Contenido y Multimedia</h6>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[categories_missing]" id="categoriesMissing" value="1">
                                <label class="form-check-label" for="categoriesMissing">
                                    <i class="bi bi-tags me-1"></i> Categorías faltantes
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[photos_missing]" id="photosMissing" value="1">
                                <label class="form-check-label" for="photosMissing">
                                    <i class="bi bi-images me-1"></i> Fotos faltantes
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[website_invalid]" id="websiteInvalid" value="1">
                                <label class="form-check-label" for="websiteInvalid">
                                    <i class="bi bi-globe me-1"></i> Sitio web inválido
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[hours_invalid]" id="hoursInvalid" value="1">
                                <label class="form-check-label" for="hoursInvalid">
                                    <i class="bi bi-clock me-1"></i> Horarios incorrectos
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label for="rejectionNotes" class="form-label">
                            <i class="bi bi-chat-text me-1"></i> Notas adicionales (opcional)
                        </label>
                        <textarea class="form-control" id="rejectionNotes" name="notes" rows="3" placeholder="Agregue comentarios adicionales o instrucciones específicas..."></textarea>
                        <div class="form-text">Estas notas aparecerán junto con los motivos específicos en la notificación al propietario.</div>
                    </div>
                    
                    <div id="rejectionError" class="alert alert-danger d-none">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Debe seleccionar al menos un motivo de rechazo.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="rejectSubmitBtn">
                        <i class="bi bi-x-circle me-1"></i> Rechazar Restaurante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suspendModalLabel">Suspender Restaurante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="suspendForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>¿Está seguro de que desea suspender el restaurante <strong id="suspendRestaurantName"></strong>?</p>
                    <div class="mb-3">
                        <label for="suspensionReason" class="form-label">Razón de la suspensión <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="suspensionReason" name="suspension_reason" rows="3" required placeholder="Explique por qué se suspende este restaurante..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Suspender Restaurante</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-actions.js') }}"></script>
<script>
// Las funciones confirmApprove y confirmReactivate están disponibles en admin-actions.js

document.addEventListener('DOMContentLoaded', function() {
    
    // Handle reject modal
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const restaurantId = button.getAttribute('data-restaurant-id');
            const restaurantName = button.getAttribute('data-restaurant-name');
            
            const form = document.getElementById('rejectForm');
            const nameElement = document.getElementById('rejectRestaurantName');
            
            form.action = `/admin/restaurants/${restaurantId}/reject`;
            nameElement.textContent = restaurantName;
            
            // Clear previous selections
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
            
            // Clear notes
            document.getElementById('rejectionNotes').value = '';
            
            // Hide error message
            document.getElementById('rejectionError').classList.add('d-none');
        });
        
        // Handle form submission with validation
        const rejectForm = document.getElementById('rejectForm');
        if (rejectForm) {
            rejectForm.addEventListener('submit', function(event) {
                const checkboxes = this.querySelectorAll('input[name^="rejection_checks"]:checked');
                const errorDiv = document.getElementById('rejectionError');
                
                if (checkboxes.length === 0) {
                    event.preventDefault();
                    errorDiv.classList.remove('d-none');
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                } else {
                    errorDiv.classList.add('d-none');
                }
            });
        }
    }
    
    // Handle suspend modal
    const suspendModal = document.getElementById('suspendModal');
    if (suspendModal) {
        suspendModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const restaurantId = button.getAttribute('data-restaurant-id');
            const restaurantName = button.getAttribute('data-restaurant-name');
            
            const form = document.getElementById('suspendForm');
            const nameElement = document.getElementById('suspendRestaurantName');
            
            form.action = `/admin/restaurants/${restaurantId}/suspend`;
            nameElement.textContent = restaurantName;
            
            // Clear previous reason
            document.getElementById('suspensionReason').value = '';
        });
    }
});
</script>
@endpush