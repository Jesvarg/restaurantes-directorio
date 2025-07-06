/**
 * Utilidades comunes para formularios
 * Funciones reutilizables para validación y manejo de errores
 */

/**
 * Muestra un mensaje de error para un campo específico
 * @param {string} fieldId - ID del campo
 * @param {string} message - Mensaje de error a mostrar
 */
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    // Remover clases de error previas
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    
    // Buscar o crear el elemento de feedback
    let feedback = field.parentNode.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        field.parentNode.appendChild(feedback);
    }
    
    feedback.textContent = message;
    feedback.style.display = 'block';
}

/**
 * Limpia el mensaje de error de un campo específico
 * @param {string} fieldId - ID del campo
 */
function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    field.classList.remove('is-invalid');
    
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.style.display = 'none';
    }
}

/**
 * Muestra un campo como válido
 * @param {string} fieldId - ID del campo
 */
function showFieldValid(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.style.display = 'none';
    }
}

/**
 * Muestra una confirmación con SweetAlert antes de ejecutar una acción
 * @param {Event} event - Evento del formulario
 * @param {string} title - Título de la confirmación
 * @param {string} text - Texto descriptivo
 * @param {string} confirmButtonText - Texto del botón de confirmación
 * @param {string} icon - Icono de SweetAlert (warning, question, etc.)
 * @returns {Promise} - Promise que se resuelve si el usuario confirma
 */
function showConfirmation(event, title, text, confirmButtonText = 'Sí, continuar', icon = 'warning') {
    event.preventDefault();
    
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Cancelar'
    });
}

/**
 * Muestra un mensaje de éxito con SweetAlert
 * @param {string} message - Mensaje a mostrar
 */
function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

/**
 * Muestra un mensaje de error con SweetAlert
 * @param {string} message - Mensaje de error
 * @param {string} title - Título del error (opcional)
 */
function showErrorMessage(message, title = 'Error') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

/**
 * Valida un campo de email
 * @param {string} email - Email a validar
 * @returns {boolean} - True si es válido
 */
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Valida un número de teléfono (formato básico)
 * @param {string} phone - Teléfono a validar
 * @returns {boolean} - True si es válido
 */
function validatePhone(phone) {
    const phoneRegex = /^[\d\s\-\+\(\)]{8,}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

/**
 * Formatea un número de teléfono automáticamente
 * @param {HTMLInputElement} input - Campo de input del teléfono
 */
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length >= 10) {
        value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    } else if (value.length >= 6) {
        value = value.replace(/(\d{3})(\d{3})/, '$1-$2');
    } else if (value.length >= 3) {
        value = value.replace(/(\d{3})/, '$1');
    }
    
    input.value = value;
}