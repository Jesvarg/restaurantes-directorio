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
                                <p class="text-muted small mb-3">Selecciona los horarios de apertura y cierre para cada día</p>
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
                                $openingHours = $restaurant->opening_hours ?? [];
                                
                                // Parse existing opening hours to extract times
                                $parsedHours = [];
                                foreach ($days as $day => $dayName) {
                                    $hours = $openingHours[$day] ?? '';
                                    if ($hours && $hours !== 'Cerrado' && strpos($hours, ' - ') !== false) {
                                        $times = explode(' - ', $hours);
                                        $parsedHours[$day] = [
                                            'is_open' => true,
                                            'open_time' => $times[0] ?? '',
                                            'close_time' => $times[1] ?? ''
                                        ];
                                    } else {
                                        $parsedHours[$day] = [
                                            'is_open' => false,
                                            'open_time' => '',
                                            'close_time' => ''
                                        ];
                                    }
                                }
                            @endphp
                            
                            @foreach($days as $day => $dayName)
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-body py-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-2">
                                                    <label class="form-label mb-0 fw-bold">{{ $dayName }}</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="is_open_{{ $day }}" 
                                                               name="is_open[{{ $day }}]" 
                                                               value="1"
                                                               {{ old('is_open.'.$day, $parsedHours[$day]['is_open']) ? 'checked' : '' }}
                                                               onchange="toggleHours('{{ $day }}')">
                                                        <label class="form-check-label" for="is_open_{{ $day }}">
                                                            Abierto
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" id="hours_{{ $day }}" style="display: {{ old('is_open.'.$day, $parsedHours[$day]['is_open']) ? 'block' : 'none' }};">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label for="open_time_{{ $day }}" class="form-label small">Hora de apertura</label>
                                                            <input type="time" 
                                                                   class="form-control @error('open_time.'.$day) is-invalid @enderror" 
                                                                   id="open_time_{{ $day }}" 
                                                                   name="open_time[{{ $day }}]" 
                                                                   value="{{ old('open_time.'.$day, $parsedHours[$day]['open_time']) }}">
                                                            @error('open_time.'.$day)
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            <label class="form-label small">&nbsp;</label>
                                                            <div class="pt-2">hasta</div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label for="close_time_{{ $day }}" class="form-label small">Hora de cierre</label>
                                                            <input type="time" 
                                                                   class="form-control @error('close_time.'.$day) is-invalid @enderror" 
                                                                   id="close_time_{{ $day }}" 
                                                                   name="close_time[{{ $day }}]" 
                                                                   value="{{ old('close_time.'.$day, $parsedHours[$day]['close_time']) }}">
                                                            @error('close_time.'.$day)
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                            
                            <!-- Photo Upload Tabs -->
                            <div class="col-12">
                                <ul class="nav nav-tabs" id="photoTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-pane" type="button" role="tab">
                                            <i class="bi bi-upload me-2"></i>Subir desde Computadora
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-pane" type="button" role="tab">
                                            <i class="bi bi-link-45deg me-2"></i>URLs de Imágenes
                                        </button>
                                    </li>
                                </ul>
                                
                                <div class="tab-content border border-top-0 p-3" id="photoTabContent">
                                    <!-- File Upload Tab -->
                                    <div class="tab-pane fade show active" id="upload-pane" role="tabpanel">
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
                                            Máximo 8 fotos. Formatos permitidos: JPEG, PNG, JPG, GIF, WebP. Tamaño máximo: 2MB por imagen.
                                            Dimensiones recomendadas: entre 300x200 y 2000x2000 píxeles.
                                        </div>
                                        
                                        <!-- Photo Preview -->
                                        <div id="photoPreview" class="row g-2 mt-2" style="display: none;"></div>
                                    </div>
                                    
                                    <!-- URL Tab -->
                                    <div class="tab-pane fade" id="url-pane" role="tabpanel">
                                        <label class="form-label">URLs de Imágenes</label>
                                        <div id="photoUrlsContainer">
                                            <div class="input-group mb-2">
                                                <input type="url" 
                                                       class="form-control @error('photo_urls.0') is-invalid @enderror" 
                                                       name="photo_urls[]" 
                                                       placeholder="https://ejemplo.com/imagen.jpg">
                                                <button type="button" class="btn btn-outline-danger" onclick="removePhotoUrl(this)" style="display: none;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @error('photo_urls.0')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <button type="button" id="addPhotoUrlBtn" class="btn btn-outline-primary btn-sm mb-2" onclick="addPhotoUrl()">
                                            <i class="bi bi-plus-circle me-1"></i>Agregar otra URL
                                        </button>
                                        
                                        @error('photo_urls')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        @error('photo_urls.*')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        
                                        <div class="form-text">
                                            Máximo 8 URLs. Las imágenes deben ser accesibles públicamente y tener formato: JPEG, PNG, JPG, GIF o WebP.
                                        </div>
                                    </div>
                                </div>
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
// Toggle hours visibility
function toggleHours(day) {
    const checkbox = document.getElementById('is_open_' + day);
    const hoursDiv = document.getElementById('hours_' + day);
    
    if (checkbox.checked) {
        hoursDiv.style.display = 'block';
    } else {
        hoursDiv.style.display = 'none';
        // Clear the time inputs when closing
        document.getElementById('open_time_' + day).value = '';
        document.getElementById('close_time_' + day).value = '';
    }
}

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
    
    // Limitar a 10 dígitos máximo
    if (value.length > 10) {
        value = value.substring(0, 10);
    }
    
    if (value.length >= 10) {
        value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    } else if (value.length >= 6) {
        value = value.replace(/(\d{3})(\d{3})/, '($1) $2-');
    } else if (value.length >= 3) {
        value = value.replace(/(\d{3})/, '($1) ');
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

// Photo URL management functions
function addPhotoUrl() {
    const container = document.getElementById('photoUrlsContainer');
    const currentInputs = container.querySelectorAll('input[name="photo_urls[]"]');
    
    if (currentInputs.length >= 8) {
        alert('Máximo 8 URLs de fotos permitidas.');
        return;
    }
    
    const newInputGroup = document.createElement('div');
    newInputGroup.className = 'input-group mb-2';
    newInputGroup.innerHTML = `
        <input type="url" 
               class="form-control" 
               name="photo_urls[]" 
               placeholder="https://ejemplo.com/imagen.jpg">
        <button type="button" class="btn btn-outline-danger" onclick="removePhotoUrl(this)">
            <i class="bi bi-trash"></i>
        </button>
    `;
    
    container.appendChild(newInputGroup);
    updatePhotoUrlButtons();
}

function removePhotoUrl(button) {
    const inputGroup = button.closest('.input-group');
    inputGroup.remove();
    updatePhotoUrlButtons();
}

function updatePhotoUrlButtons() {
    const container = document.getElementById('photoUrlsContainer');
    const inputGroups = container.querySelectorAll('.input-group');
    const addButton = document.getElementById('addPhotoUrlBtn');
    
    // Show/hide remove buttons
    inputGroups.forEach((group, index) => {
        const removeButton = group.querySelector('.btn-outline-danger');
        if (inputGroups.length > 1) {
            removeButton.style.display = 'block';
        } else {
            removeButton.style.display = 'none';
        }
    });
    
    // Show/hide add button
    if (inputGroups.length >= 8) {
        addButton.style.display = 'none';
    } else {
        addButton.style.display = 'inline-block';
    }
}

// Initialize photo URL buttons on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePhotoUrlButtons();
    
    // Add validation for total photos (files + URLs) on form submit
    document.getElementById('restaurantForm').addEventListener('submit', function(e) {
        const fileInputs = document.getElementById('photos').files;
        const urlInputs = document.querySelectorAll('input[name="photo_urls[]"]');
        const filledUrls = Array.from(urlInputs).filter(input => input.value.trim() !== '');
        
        const totalPhotos = fileInputs.length + filledUrls.length;
        
        if (totalPhotos > 8) {
            e.preventDefault();
            alert('No puedes agregar más de 8 fotos en total (archivos + URLs).');
            return false;
        }
    });
});
</script>
@endpush