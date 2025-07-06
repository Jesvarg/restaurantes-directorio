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
                
                @if($restaurant->primary_photo_url)
                <div class="position-relative">
                    <img src="{{ $restaurant->primary_photo_url }}" 
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
                        <button type="button" 
                                class="btn btn-outline-info flex-fill"
                                data-bs-toggle="modal"
                                data-bs-target="#reviewModal"
                                data-restaurant-id="{{ $restaurant->id }}"
                                data-restaurant-name="{{ $restaurant->name }}"
                                data-restaurant-description="{{ $restaurant->description }}"
                                data-restaurant-address="{{ $restaurant->address }}"
                                data-restaurant-phone="{{ $restaurant->phone }}"
                                data-restaurant-email="{{ $restaurant->email }}"
                                data-restaurant-website="{{ $restaurant->website }}"
                                data-restaurant-price-range="{{ $restaurant->price_range }}"
                                data-restaurant-categories="{{ $restaurant->categories->pluck('name')->implode(', ') }}"
                                data-restaurant-images="{{ implode(',', $restaurant->images ?? []) }}"
                                data-restaurant-owner="{{ $restaurant->user->name }}"
                                data-restaurant-owner-email="{{ $restaurant->user->email }}">
                            <i class="bi bi-eye me-1"></i>
                            Ver Detalles
                        </button>
                        
                        <!-- Approve -->
                        <form method="POST" action="{{ route('admin.restaurants.approve', $restaurant) }}" class="flex-fill approve-form">
                            @csrf
                            @method('PATCH')
                            <button type="button" 
                                    class="btn btn-success w-100 approve-btn" 
                                    data-restaurant="{{ $restaurant->name }}">
                                <i class="bi bi-check-lg me-1"></i>
                                Aprobar
                            </button>
                        </form>
                        
                        <!-- Reject -->
                        <form method="POST" action="{{ route('admin.restaurants.reject', $restaurant) }}" class="flex-fill reject-form">
                            @csrf
                            @method('PATCH')
                            <button type="button" 
                                    class="btn btn-danger w-100 reject-btn" 
                                    data-restaurant="{{ $restaurant->name }}">
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
                {{ $restaurants->links('pagination::bootstrap-4') }}
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


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmación para aprobar con SweetAlert2
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const restaurantName = this.dataset.restaurant;
            
            Swal.fire({
                title: '¿Aprobar restaurante?',
                text: `¿Está seguro de que desea aprobar "${restaurantName}"? El restaurante será visible públicamente.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, aprobar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    
    // Modal de rechazo con checks
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const restaurantName = this.dataset.restaurant;
            
            // Configurar el modal de rechazo
            document.getElementById('rejectModalTitle').textContent = `Rechazar: ${restaurantName}`;
            document.getElementById('rejectForm').action = form.action;
            
            // Limpiar selecciones previas
            document.querySelectorAll('#rejectModal input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('rejection_notes').value = '';
            
            // Ocultar mensajes de error previos
            const errorDiv = document.getElementById('rejection-error');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            
            // Mostrar el modal
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            rejectModal.show();
        });
    });
    
    // Validación del formulario de rechazo
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('#rejectModal input[type="checkbox"]:checked');
        
        if (checkboxes.length === 0) {
            e.preventDefault();
            const errorDiv = document.getElementById('rejection-error');
            errorDiv.textContent = 'Debe seleccionar al menos una razón de rechazo.';
            errorDiv.style.display = 'block';
            return false;
        }
        
        // Ocultar mensaje de error si la validación pasa
        const errorDiv = document.getElementById('rejection-error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    });
});
</script>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalTitle">
                    <i class="bi bi-x-circle me-2"></i>
                    Rechazar Restaurante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-danger" id="rejection-error" style="display: none;"></div>
                    
                    <p class="mb-3">Seleccione las razones por las cuales está rechazando este restaurante:</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[name_invalid]" id="name_invalid">
                                <label class="form-check-label" for="name_invalid">
                                    Nombre inválido o inapropiado
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[description_invalid]" id="description_invalid">
                                <label class="form-check-label" for="description_invalid">
                                    Descripción inadecuada
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[address_invalid]" id="address_invalid">
                                <label class="form-check-label" for="address_invalid">
                                    Dirección incorrecta o incompleta
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[contact_invalid]" id="contact_invalid">
                                <label class="form-check-label" for="contact_invalid">
                                    Información de contacto inválida
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[photos_missing]" id="photos_missing">
                                <label class="form-check-label" for="photos_missing">
                                    Faltan fotos o son inadecuadas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[categories_invalid]" id="categories_invalid">
                                <label class="form-check-label" for="categories_invalid">
                                    Categorías incorrectas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[duplicate_restaurant]" id="duplicate_restaurant">
                                <label class="form-check-label" for="duplicate_restaurant">
                                    Restaurante duplicado
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="rejection_checks[other_reason]" id="other_reason">
                                <label class="form-check-label" for="other_reason">
                                    Otra razón
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="rejection_notes" class="form-label">Notas adicionales (opcional):</label>
                        <textarea class="form-control" id="rejection_notes" name="notes" rows="3" placeholder="Proporcione detalles adicionales sobre el rechazo..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-1"></i>
                        Rechazar Restaurante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">
                    <i class="bi bi-eye me-2"></i>
                    Revisar Restaurante: <span id="modalRestaurantName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Información del Propietario -->
                    <div class="col-12 mb-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-person me-2"></i>
                                    Información del Propietario
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nombre:</strong>
                                        <p id="modalOwnerName" class="mb-2"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Email:</strong>
                                        <p id="modalOwnerEmail" class="mb-2"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del Restaurante -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-shop me-2"></i>
                                    Detalles del Restaurante
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Descripción:</strong>
                                        <p id="modalDescription" class="text-muted"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Dirección:</strong>
                                        <p id="modalAddress"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Teléfono:</strong>
                                        <p id="modalPhone"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Email:</strong>
                                        <p id="modalEmail"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Sitio Web:</strong>
                                        <p id="modalWebsite"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Rango de Precios:</strong>
                                        <p id="modalPriceRange"></p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <strong>Categorías:</strong>
                                        <p id="modalCategories"></p>
                                    </div>
                                    <div class="col-12">
                                        <strong>Imágenes:</strong>
                                        <div id="modalImages" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    Cerrar
                </button>
                <a id="modalViewPublic" href="#" target="_blank" class="btn btn-info">
                    <i class="bi bi-eye me-1"></i>
                    Ver Página Pública
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Handle review modal
const reviewModal = document.getElementById('reviewModal');
if (reviewModal) {
    reviewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        
        // Obtener datos del botón
        const restaurantId = button.getAttribute('data-restaurant-id');
        const restaurantName = button.getAttribute('data-restaurant-name');
        const description = button.getAttribute('data-restaurant-description');
        const address = button.getAttribute('data-restaurant-address');
        const phone = button.getAttribute('data-restaurant-phone');
        const email = button.getAttribute('data-restaurant-email');
        const website = button.getAttribute('data-restaurant-website');
        const priceRange = button.getAttribute('data-restaurant-price-range');
        const categories = button.getAttribute('data-restaurant-categories');
        const images = button.getAttribute('data-restaurant-images');
        const ownerName = button.getAttribute('data-restaurant-owner');
        const ownerEmail = button.getAttribute('data-restaurant-owner-email');
        
        // Actualizar contenido del modal
        document.getElementById('modalRestaurantName').textContent = restaurantName;
        document.getElementById('modalOwnerName').textContent = ownerName;
        document.getElementById('modalOwnerEmail').textContent = ownerEmail;
        document.getElementById('modalDescription').textContent = description || 'No especificada';
        document.getElementById('modalAddress').textContent = address || 'No especificada';
        document.getElementById('modalPhone').textContent = phone || 'No especificado';
        document.getElementById('modalEmail').textContent = email || 'No especificado';
        document.getElementById('modalWebsite').textContent = website || 'No especificado';
        
        // Formatear rango de precios
        let priceRangeText = 'No especificado';
        if (priceRange) {
            switch(priceRange) {
                case '1': priceRangeText = '$ - Económico'; break;
                case '2': priceRangeText = '$$ - Moderado'; break;
                case '3': priceRangeText = '$$$ - Caro'; break;
                case '4': priceRangeText = '$$$$ - Muy Caro'; break;
            }
        }
        document.getElementById('modalPriceRange').textContent = priceRangeText;
        
        document.getElementById('modalCategories').textContent = categories || 'No especificadas';
        
        // Manejar imágenes
        const imagesContainer = document.getElementById('modalImages');
        if (images && images.trim() !== '') {
            const imageUrls = images.split(',').filter(url => url.trim() !== '');
            if (imageUrls.length > 0) {
                imagesContainer.innerHTML = imageUrls.map(url => 
                    `<img src="${url.trim()}" class="img-thumbnail me-2 mb-2" style="width: 100px; height: 100px; object-fit: cover;" alt="Imagen del restaurante">`
                ).join('');
            } else {
                imagesContainer.innerHTML = '<p class="text-muted">No hay imágenes</p>';
            }
        } else {
            imagesContainer.innerHTML = '<p class="text-muted">No hay imágenes</p>';
        }
        
        // Actualizar enlace a página pública
        const publicLink = document.getElementById('modalViewPublic');
        publicLink.href = `/restaurants/${restaurantId}`;
    });
}
</script>

@endpush