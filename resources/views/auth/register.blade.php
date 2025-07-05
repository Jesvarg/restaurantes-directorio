@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header text-center bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>
                        Crear Cuenta
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">Únete a nuestra comunidad gastronómica</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person me-1"></i>
                                Nombre Completo
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="Tu nombre completo"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mínimo 2 caracteres, máximo 255</div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                Correo Electrónico
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="tu@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Debe ser un correo electrónico válido y único</div>
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>
                                Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Mínimo 8 caracteres"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Mínimo 8 caracteres. Se recomienda incluir mayúsculas, minúsculas, números y símbolos.
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="passwordStrengthText" class="text-muted"></small>
                            </div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>
                                Confirmar Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Repite tu contraseña"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye" id="toggleIconConfirm"></i>
                                </button>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="passwordMatch" class="form-text"></div>
                        </div>
                        
                        <!-- User Role -->
                        <div class="mb-3">
                            <label for="role" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>
                                Tipo de Usuario
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="">Selecciona tu tipo de usuario</option>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Usuario - Explorar y reseñar restaurantes</option>
                                <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Propietario - Gestionar mis restaurantes</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <strong>Usuario:</strong> Puedes explorar, reseñar y marcar restaurantes como favoritos.<br>
                                <strong>Propietario:</strong> Puedes agregar y gestionar tus propios restaurantes.
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" 
                                       name="terms" 
                                       id="terms" 
                                       required
                                       {{ old('terms') ? 'checked' : '' }}>
                                <label class="form-check-label" for="terms">
                                    Acepto los 
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">
                                        Términos y Condiciones
                                    </a> 
                                    y la 
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#privacyModal">
                                        Política de Privacidad
                                    </a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Newsletter Subscription -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="newsletter" 
                                       id="newsletter"
                                       {{ old('newsletter') ? 'checked' : '' }}>
                                <label class="form-check-label" for="newsletter">
                                    Quiero recibir noticias y ofertas especiales por correo electrónico
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="bi bi-person-check me-2"></i>
                                Crear Cuenta
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <p class="mb-0 text-muted">
                        ¿Ya tienes una cuenta? 
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Welcome Message -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-heart me-2"></i>
                        ¡Bienvenido a nuestra comunidad!
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Al unirte a nosotros podrás:</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="small">Descubrir nuevos restaurantes en tu área</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="small">Compartir tus experiencias gastronómicas</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="small">Conectar con otros amantes de la comida</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="small">Recibir recomendaciones personalizadas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">
                    <i class="bi bi-file-text me-2"></i>
                    Términos y Condiciones
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Aceptación de los Términos</h6>
                <p>Al registrarte y usar nuestro directorio de restaurantes, aceptas cumplir con estos términos y condiciones.</p>
                
                <h6>2. Uso del Servicio</h6>
                <p>Nuestro servicio está destinado a ayudar a los usuarios a descubrir y compartir información sobre restaurantes. Te comprometes a usar el servicio de manera responsable y legal.</p>
                
                <h6>3. Contenido del Usuario</h6>
                <p>Eres responsable del contenido que publiques, incluyendo reseñas, fotos y información de restaurantes. El contenido debe ser veraz, apropiado y no infringir derechos de terceros.</p>
                
                <h6>4. Privacidad</h6>
                <p>Respetamos tu privacidad. Consulta nuestra Política de Privacidad para entender cómo recopilamos y usamos tu información.</p>
                
                <h6>5. Modificaciones</h6>
                <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Te notificaremos sobre cambios importantes.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">
                    <i class="bi bi-shield-check me-2"></i>
                    Política de Privacidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Información que Recopilamos</h6>
                <p>Recopilamos información que nos proporcionas directamente, como tu nombre, correo electrónico, y contenido que publicas en nuestro sitio.</p>
                
                <h6>Cómo Usamos tu Información</h6>
                <p>Usamos tu información para proporcionar y mejorar nuestros servicios, comunicarnos contigo, y personalizar tu experiencia.</p>
                
                <h6>Compartir Información</h6>
                <p>No vendemos ni alquilamos tu información personal a terceros. Podemos compartir información agregada y anónima para fines estadísticos.</p>
                
                <h6>Seguridad</h6>
                <p>Implementamos medidas de seguridad para proteger tu información personal contra acceso no autorizado, alteración, divulgación o destrucción.</p>
                
                <h6>Tus Derechos</h6>
                <p>Tienes derecho a acceder, actualizar o eliminar tu información personal. Puedes contactarnos para ejercer estos derechos.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
});

document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
    const passwordField = document.getElementById('password_confirmation');
    const toggleIcon = document.getElementById('toggleIconConfirm');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
});

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');
    
    let strength = 0;
    let feedback = [];
    
    // Length check
    if (password.length >= 8) strength += 20;
    else feedback.push('mínimo 8 caracteres');
    
    // Lowercase check
    if (/[a-z]/.test(password)) strength += 20;
    else feedback.push('minúsculas');
    
    // Uppercase check
    if (/[A-Z]/.test(password)) strength += 20;
    else feedback.push('mayúsculas');
    
    // Number check
    if (/\d/.test(password)) strength += 20;
    else feedback.push('números');
    
    // Special character check
    if (/[^\w\s]/.test(password)) strength += 20;
    else feedback.push('símbolos');
    
    // Update progress bar
    strengthBar.style.width = strength + '%';
    
    if (strength < 40) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Débil - Falta: ' + feedback.join(', ');
        strengthText.className = 'text-danger small';
    } else if (strength < 80) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Media - Falta: ' + feedback.join(', ');
        strengthText.className = 'text-warning small';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Fuerte - ¡Excelente!';
        strengthText.className = 'text-success small';
    }
});

// Password confirmation checker
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    const matchText = document.getElementById('passwordMatch');
    
    if (confirmation === '') {
        matchText.textContent = '';
        matchText.className = 'form-text';
    } else if (password === confirmation) {
        matchText.textContent = '✓ Las contraseñas coinciden';
        matchText.className = 'form-text text-success';
    } else {
        matchText.textContent = '✗ Las contraseñas no coinciden';
        matchText.className = 'form-text text-danger';
    }
});

// Form validation with visual feedback
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const existingError = field.parentNode.querySelector('.client-error-message');
    
    // Remove existing error message
    if (existingError) {
        existingError.remove();
    }
    
    // Add error styling
    field.classList.add('is-invalid');
    
    // Create and add error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback client-error-message';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
    
    // Focus the field
    field.focus();
}

function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    const existingError = field.parentNode.querySelector('.client-error-message');
    
    if (existingError) {
        existingError.remove();
    }
    
    field.classList.remove('is-invalid');
}

// Clear errors on input
document.getElementById('name').addEventListener('input', function() {
    clearFieldError('name');
});

document.getElementById('email').addEventListener('input', function() {
    clearFieldError('email');
});

document.getElementById('password').addEventListener('input', function() {
    clearFieldError('password');
});

document.getElementById('password_confirmation').addEventListener('input', function() {
    clearFieldError('password_confirmation');
});

document.getElementById('terms').addEventListener('change', function() {
    clearFieldError('terms');
});

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const terms = document.getElementById('terms').checked;
    
    let isValid = true;
    
    // Clear all previous errors
    clearFieldError('name');
    clearFieldError('email');
    clearFieldError('password');
    clearFieldError('password_confirmation');
    clearFieldError('terms');
    
    // Name validation
    if (name.length < 2) {
        showFieldError('name', 'El nombre debe tener al menos 2 caracteres.');
        isValid = false;
    }
    
    // Email validation
    if (!email) {
        showFieldError('email', 'Por favor ingresa tu correo electrónico.');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFieldError('email', 'Por favor ingresa un correo electrónico válido.');
        isValid = false;
    }
    
    // Password validation
    if (password.length < 8) {
        showFieldError('password', 'La contraseña debe tener al menos 8 caracteres.');
        isValid = false;
    }
    
    // Password confirmation validation
    if (password !== passwordConfirmation) {
        showFieldError('password_confirmation', 'Las contraseñas no coinciden.');
        isValid = false;
    }
    
    // Terms validation
    if (!terms) {
        showFieldError('terms', 'Debes aceptar los términos y condiciones.');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        return false;
    }
});

// Auto-focus on name field if empty
window.addEventListener('load', function() {
    const nameField = document.getElementById('name');
    if (!nameField.value) {
        nameField.focus();
    }
});
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.card-footer {
    border-radius: 0 0 15px 15px !important;
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.input-group .btn {
    border-radius: 0 6px 6px 0;
}

.shadow {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    transition: width 0.3s ease;
}

@media (max-width: 576px) {
    .container {
        padding: 0 15px;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
}
</style>
@endpush