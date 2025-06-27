@extends('layouts.app')

@section('title', 'Agregar Restaurante')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">Restaurantes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar Restaurante</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Agregar Nuevo Restaurante
                    </h4>
                    <p class="text-muted mb-0 mt-2">Comparte tu restaurante favorito con la comunidad</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('restaurants.store') }}" method="POST" enctype="multipart/form-data" id="restaurantForm">
                        @csrf
                        
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
                                       value="{{ old('name') }}"
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
                                    <option value="1" {{ old('price_range') == '1' ? 'selected' : '' }}>$ Económico (Menos de $15)</option>
                                    <option value="2" {{ old('price_range') == '2' ? 'selected' : '' }}>$$ Moderado ($15 - $30)</option>
                                    <option value="3" {{ old('price_range') == '3' ? 'selected' : '' }}>$$$ Caro ($30 - $60)</option>
                                    <option value="4" {{ old('price_range') == '4' ? 'selected' : '' }}>$$$$ Muy caro (Más de $60)</option>
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
                                          placeholder="Describe el ambiente, especialidades, lo que hace especial a este restaurante...">{{ old('description') }}</textarea>
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
                                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                                       value="{{ old('address') }}"
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
                                       value="{{ old('phone') }}"
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
                                       value="{{ old('email') }}"
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
                                       value="{{ old('website') }}"
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
                                                               {{ old('is_open.'.$day) ? 'checked' : '' }}
                                                               onchange="toggleHours('{{ $day }}')">
                                                        <label class="form-check-label" for="is_open_{{ $day }}">
                                                            Abierto
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" id="hours_{{ $day }}" style="display: {{ old('is_open.'.$day) ? 'block' : 'none' }};">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label for="open_time_{{ $day }}" class="form-label small">Hora de apertura</label>
                                                            <input type="time" 
                                                                   class="form-control @error('open_time.'.$day) is-invalid @enderror" 
                                                                   id="open_time_{{ $day }}" 
                                                                   name="open_time[{{ $day }}]" 
                                                                   value="{{ old('open_time.'.$day) }}">
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
                                                                   value="{{ old('close_time.'.$day) }}">
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
                                       value="{{ old('latitude') }}"
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
                                       value="{{ old('longitude') }}"
                                       step="any"
                                       min="-180"
                                       max="180"
                                       placeholder="Ej: -99.1332">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Photos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-images me-2"></i>
                                    Fotos (Opcional) - Máximo 8 fotos
                                </h5>
                            </div>
                            
                            <!-- Photo Upload Tabs -->
                            <div class="col-12 mb-3">
                                <ul class="nav nav-tabs" id="photoTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-panel" type="button" role="tab">
                                            <i class="bi bi-upload me-1"></i>Subir desde ordenador
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-panel" type="button" role="tab">
                                            <i class="bi bi-link-45deg me-1"></i>URLs de internet
                                        </button>
                                    </li>
                                </ul>
                                
                                <div class="tab-content border border-top-0 p-3" id="photoTabContent">
                                    <!-- Upload Panel -->
                                    <div class="tab-pane fade show active" id="upload-panel" role="tabpanel">
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
                                        </div>
                                    </div>
                                    
                                    <!-- URL Panel -->
                                    <div class="tab-pane fade" id="url-panel" role="tabpanel">
                                        <label class="form-label">URLs de Fotos</label>
                                        <div id="photoUrls">
                                            <div class="input-group mb-2">
                                                <input type="url" class="form-control" name="photo_urls[]" placeholder="https://ejemplo.com/imagen.jpg">
                                                <button type="button" class="btn btn-outline-success" onclick="addPhotoUrl()"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </div>
                                        @error('photo_urls')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        @error('photo_urls.*')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Máximo 8 URLs. Asegúrate de que las URLs sean válidas y apunten a imágenes.
                                        </div>
                                    </div>
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
                                    <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Crear Restaurante
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Help Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>
                        Consejos para una mejor publicación
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li>Usa un nombre descriptivo y fácil de recordar</li>
                        <li>Incluye una descripción detallada que destaque lo especial del lugar</li>
                        <li>Selecciona las categorías que mejor representen el tipo de comida</li>
                        <li>Proporciona información de contacto completa y actualizada</li>
                        <li>Sube fotos de buena calidad que muestren el ambiente y la comida</li>
                        <li>Los horarios ayudan a los visitantes a planificar su visita</li>
                        <li>Tu publicación será revisada antes de aparecer públicamente</li>
                    </ul>
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

// Photo URL management
let photoUrlCount = 1;
const maxPhotos = 8;

function addPhotoUrl() {
    if (photoUrlCount >= maxPhotos) {
        alert('Máximo 8 fotos permitidas.');
        return;
    }
    
    const container = document.getElementById('photoUrls');
    const newDiv = document.createElement('div');
    newDiv.className = 'input-group mb-2';
    newDiv.innerHTML = `
        <input type="url" class="form-control" name="photo_urls[]" placeholder="https://ejemplo.com/imagen.jpg">
        <button type="button" class="btn btn-outline-danger" onclick="removePhotoUrl(this)"><i class="bi bi-trash"></i></button>
    `;
    
    container.appendChild(newDiv);
    photoUrlCount++;
    
    updateAddButton();
}

function removePhotoUrl(button) {
    button.parentElement.remove();
    photoUrlCount--;
    updateAddButton();
}

function updateAddButton() {
    const addButtons = document.querySelectorAll('#photoUrls .btn-outline-success');
    addButtons.forEach(btn => {
        if (photoUrlCount >= maxPhotos) {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-plus"></i> Máximo alcanzado';
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-plus"></i>';
        }
    });
}

// Validate total photos (files + URLs)
document.getElementById('restaurantForm').addEventListener('submit', function(e) {
    const fileInputs = document.getElementById('photos').files.length;
    const urlInputs = document.querySelectorAll('input[name="photo_urls[]"]').length;
    const filledUrls = Array.from(document.querySelectorAll('input[name="photo_urls[]"]')).filter(input => input.value.trim() !== '').length;
    
    const totalPhotos = fileInputs + filledUrls;
    
    if (totalPhotos > maxPhotos) {
        e.preventDefault();
        alert(`Máximo ${maxPhotos} fotos permitidas. Tienes ${totalPhotos} fotos seleccionadas.`);
        return false;
    }
});

</script>
@endpush