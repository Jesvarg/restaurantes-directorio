@extends('layouts.app')

@section('title', 'Editar Restaurante')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">Restaurantes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $restaurant) }}">{{ $restaurant->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Restaurante
                    </h4>
                    <p class="text-muted mb-0 mt-2">Actualiza la información de {{ $restaurant->name }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('restaurants.update', $restaurant) }}" method="POST" enctype="multipart/form-data" id="restaurantForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Información Básica
                                </h5>
                            </div>
                            
                            <!-- Restaurant Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre del Restaurante *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $restaurant->name) }}"
                                       placeholder="Ej: La Parrilla del Chef"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Price Range -->
                            <div class="col-md-6 mb-3">
                                <label for="price_range" class="form-label">Rango de Precio *</label>
                                <select class="form-select @error('price_range') is-invalid @enderror" 
                                        id="price_range" 
                                        name="price_range" 
                                        required>
                                    <option value="">Selecciona un rango</option>
                                    <option value="1" {{ old('price_range', $restaurant->price_range) == '1' ? 'selected' : '' }}>$ Económico (Menos de $15)</option>
                                    <option value="2" {{ old('price_range', $restaurant->price_range) == '2' ? 'selected' : '' }}>$$ Moderado ($15 - $30)</option>
                                    <option value="3" {{ old('price_range', $restaurant->price_range) == '3' ? 'selected' : '' }}>$$$ Caro ($30 - $60)</option>
                                    <option value="4" {{ old('price_range', $restaurant->price_range) == '4' ? 'selected' : '' }}>$$$$ Muy caro (Más de $60)</option>
                                </select>
                                @error('price_range')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Describe el ambiente, especialidades, lo que hace especial a este restaurante...">{{ old('description', $restaurant->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Mínimo 10 caracteres, máximo 1000</div>
                            </div>
                            
                            <!-- Categories -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Categorías *</label>
                                <div class="row">
                                    @foreach($categories as $category)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input @error('categories') is-invalid @enderror" 
                                                       type="checkbox" 
                                                       name="categories[]" 
                                                       value="{{ $category->id }}" 
                                                       id="category{{ $category->id }}"
                                                       {{ in_array($category->id, old('categories', $restaurant->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('categories')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Selecciona entre 1 y 5 categorías que mejor describan el restaurante</div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-telephone me-2"></i>
                                    Información de Contacto
                                </h5>
                            </div>
                            
                            <!-- Address -->
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Dirección *</label>
                                <input type="text" 
                                       class="form-control @error('address') is-invalid @enderror" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address', $restaurant->address) }}"
                                       placeholder="Ej: Av. Principal 123, Centro, Ciudad"
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $restaurant->phone) }}"
                                       placeholder="Ej: +1 234 567 8900">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $restaurant->email) }}"
                                       placeholder="Ej: contacto@restaurante.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Website -->
                            <div class="col-12 mb-3">
                                <label for="website" class="form-label">Sitio Web</label>
                                <input type="url" 
                                       class="form-control @error('website') is-invalid @enderror" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website', $restaurant->website) }}"
                                       placeholder="Ej: https://www.restaurante.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Opening Hours -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-clock me-2"></i>
                                    Horarios de Atención
                                </h5>
                                <p class="text-muted small mb-3">Formato: HH:MM - HH:MM (ej: 09:00 - 22:00) o "Cerrado"</p>
                            </div>
                            
                            @php
                                $days = [
                                    'monday' => 'Lunes',
                                    'tuesday' => 'Martes', 
                                    'wednesday' => 'Miércoles',
                                    'thursday' => 'Jueves',
                                    'friday' => 'Viernes',
                                    'saturday' => 'Sábado',
                                    'sunday' => 'Domingo'
                                ];
                                $openingHours = old('opening_hours', $restaurant->opening_hours ?? []);
                            @endphp
                            
                            @foreach($days as $day => $dayName)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="opening_hours_{{ $day }}" class="form-label">{{ $dayName }}</label>
                                    <input type="text" 
                                           class="form-control @error('opening_hours.'.$day) is-invalid @enderror" 
                                           id="opening_hours_{{ $day }}" 
                                           name="opening_hours[{{ $day }}]" 
                                           value="{{ $openingHours[$day] ?? '' }}"
                                           placeholder="09:00 - 22:00">
                                    @error('opening_hours.'.$day)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Location -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    Ubicación (Opcional)
                                </h5>
                                <p class="text-muted small mb-3">Proporciona las coordenadas para mostrar el restaurante en el mapa</p>
                            </div>
                            
                            <!-- Latitude -->
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitud</label>
                                <input type="number" 
                                       class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude', $restaurant->latitude) }}"
                                       step="any"
                                       min="-90"
                                       max="90"
                                       placeholder="Ej: 19.4326">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Longitude -->
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitud</label>
                                <input type="number" 
                                       class="form-control @error('longitude') is-invalid @enderror" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude', $restaurant->longitude) }}"
                                       step="any"
                                       min="-180"
                                       max="180"
                                       placeholder="Ej: -99.1332">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Current Photos -->
                        @if($restaurant->photos->count() > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-images me-2"></i>
                                    Fotos Actuales
                                </h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="row g-2">
                                    @foreach($restaurant->photos as $photo)
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <div class="card position-relative">
                                                <img src="{{ $photo->url }}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="{{ $photo->alt_text }}">
                                                @if($photo->is_primary)
                                                    <span class="position-absolute top-0 start-0 badge bg-primary m-1">
                                                        <i class="bi bi-star-fill"></i> Principal
                                                    </span>
                                                @endif
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">Foto {{ $loop->iteration }}</small>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" id="delete_photo_{{ $photo->id }}">
                                                            <label class="form-check-label text-danger small" for="delete_photo_{{ $photo->id }}">
                                                                Eliminar
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Marca las fotos que deseas eliminar. Esta acción no se puede deshacer.
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- New Photos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Agregar Nuevas Fotos (Opcional)
                                </h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="photos" class="form-label">Subir Fotos</label>
                                <input type="file" 
                                       class="form-control @error('photos') @error('photos.*') is-invalid @enderror @enderror" 
                                       id="photos" 
                                       name="photos[]" 
                                       multiple
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                @error('photos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('photos.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Máximo 10 fotos. Formatos permitidos: JPEG, PNG, JPG, GIF, WebP. Tamaño máximo: 2MB por imagen.
                                    Dimensiones recomendadas: entre 300x200 y 2000x2000 píxeles.
                                </div>
                            </div>
                            
                            <!-- Photo Preview -->
                            <div class="col-12">
                                <div id="photoPreview" class="row g-2" style="display: none;"></div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Actualizar Restaurante
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Status Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Estado del Restaurante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="mb-2">
                                <strong>Estado actual:</strong> 
                                @if($restaurant->status === 'active')
                                    <span class="badge bg-success">Activo</span>
                                @elseif($restaurant->status === 'pending')
                                    <span class="badge bg-warning">Pendiente de Aprobación</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </p>
                            <p class="mb-0 small text-muted">
                                @if($restaurant->status === 'pending')
                                    Tu restaurante está siendo revisado por nuestro equipo. Te notificaremos cuando sea aprobado.
                                @elseif($restaurant->status === 'active')
                                    Tu restaurante está visible públicamente en el directorio.
                                @else
                                    Tu restaurante no está visible públicamente. Contacta al administrador para más información.
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <small class="text-muted">
                                Última actualización:<br>
                                {{ $restaurant->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Photo preview functionality
document.getElementById('photos').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('photoPreview');
    
    // Clear previous previews
    preview.innerHTML = '';
    
    if (files.length > 0) {
        preview.style.display = 'block';
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6';
                    
                    col.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Preview ${index + 1}">
                            <div class="card-body p-2">
                                <small class="text-muted">${file.name}</small>
                            </div>
                        </div>
                    `;
                    
                    preview.appendChild(col);
                };
                
                reader.readAsDataURL(file);
            }
        });
    } else {
        preview.style.display = 'none';
    }
});

// Form validation
document.getElementById('restaurantForm').addEventListener('submit', function(e) {
    const categories = document.querySelectorAll('input[name="categories[]"]:checked');
    
    if (categories.length === 0) {
        e.preventDefault();
        alert('Por favor selecciona al menos una categoría.');
        return false;
    }
    
    if (categories.length > 5) {
        e.preventDefault();
        alert('No puedes seleccionar más de 5 categorías.');
        return false;
    }
});

// Auto-format phone number
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value.length <= 3) {
            value = value;
        } else if (value.length <= 6) {
            value = value.slice(0, 3) + ' ' + value.slice(3);
        } else {
            value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
        }
    }
    e.target.value = value;
});

// Validate coordinates
function validateCoordinates() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    
    if ((lat && !lng) || (!lat && lng)) {
        alert('Si proporcionas latitud, también debes proporcionar longitud y viceversa.');
        return false;
    }
    
    return true;
}

document.getElementById('latitude').addEventListener('blur', validateCoordinates);
document.getElementById('longitude').addEventListener('blur', validateCoordinates);

// Confirm photo deletion
document.addEventListener('change', function(e) {
    if (e.target.name === 'delete_photos[]') {
        if (e.target.checked) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta foto? Esta acción no se puede deshacer.')) {
                e.target.checked = false;
            }
        }
    }
});
</script>
@endpush