@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Iniciar Sesión
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">Accede a tu cuenta del directorio</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        
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
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                       placeholder="Tu contraseña"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="remember" 
                                       id="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Iniciar Sesión
                            </button>
                        </div>
                        
                        <!-- Forgot Password Link -->
                        <div class="text-center mb-3">
                            <a href="#" class="text-decoration-none small">
                                <i class="bi bi-question-circle me-1"></i>
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <p class="mb-0 text-muted">
                        ¿No tienes una cuenta? 
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold">
                            Regístrate aquí
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Benefits Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-star me-2"></i>
                        ¿Por qué crear una cuenta?
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <i class="bi bi-heart text-danger fs-4"></i>
                                <p class="small mb-0 mt-1">Guarda tus restaurantes favoritos</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <i class="bi bi-chat-dots text-primary fs-4"></i>
                                <p class="small mb-0 mt-1">Escribe reseñas y calificaciones</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <i class="bi bi-plus-circle text-success fs-4"></i>
                                <p class="small mb-0 mt-1">Agrega nuevos restaurantes</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <i class="bi bi-bell text-warning fs-4"></i>
                                <p class="small mb-0 mt-1">Recibe notificaciones personalizadas</p>
                            </div>
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
document.getElementById('email').addEventListener('input', function() {
    clearFieldError('email');
});

document.getElementById('password').addEventListener('input', function() {
    clearFieldError('password');
});

// Form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    
    let isValid = true;
    
    // Clear all previous errors
    clearFieldError('email');
    clearFieldError('password');
    
    // Email validation
    if (!email) {
        showFieldError('email', 'Por favor ingresa tu correo electrónico.');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFieldError('email', 'Por favor ingresa un correo electrónico válido.');
        isValid = false;
    }
    
    // Password validation
    if (!password) {
        showFieldError('password', 'Por favor ingresa tu contraseña.');
        isValid = false;
    } else if (password.length < 8) {
        showFieldError('password', 'La contraseña debe tener al menos 8 caracteres.');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        return false;
    }
});

// Auto-focus on email field if empty
window.addEventListener('load', function() {
    const emailField = document.getElementById('email');
    if (!emailField.value) {
        emailField.focus();
    }
});

// Enter key handling
document.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const form = document.getElementById('loginForm');
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.click();
        }
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
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.input-group .btn {
    border-radius: 0 6px 6px 0;
}

.shadow {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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