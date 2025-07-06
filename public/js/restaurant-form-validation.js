/**
 * Validaciones en tiempo real para formularios de restaurantes
 * Funciona tanto para creación como edición
 */

class RestaurantFormValidator {
    constructor() {
        this.init();
    }

    init() {
        this.setupNameValidation();
        this.setupDescriptionValidation();
        this.setupAddressValidation();
        this.setupPhoneValidation();
        this.setupEmailValidation();
        this.setupWebsiteValidation();
        this.setupPhotosValidation();
        this.setupCategoriesValidation();
        this.setupCoordinatesValidation();
    }

    // Validación del nombre
    setupNameValidation() {
        const nameField = document.getElementById('name');
        if (!nameField) return;

        const counter = this.createCounter('text-muted');
        nameField.parentNode.appendChild(counter);

        const validate = () => {
            const length = nameField.value.length;
            const minLength = 3;
            const maxLength = 50;

            counter.textContent = `${length}/${maxLength} caracteres`;

            if (length === 0) {
                this.setFieldState(nameField, counter, 'neutral', 'El nombre es obligatorio');
            } else if (length < minLength) {
                this.setFieldState(nameField, counter, 'invalid', `Mínimo ${minLength} caracteres`);
            } else if (length > maxLength) {
                this.setFieldState(nameField, counter, 'invalid', `Máximo ${maxLength} caracteres`);
            } else {
                this.setFieldState(nameField, counter, 'valid', `${length}/${maxLength} caracteres ✓`);
            }
        };

        nameField.addEventListener('input', validate);
        validate(); // Validar valor inicial
    }

    // Validación de la descripción
    setupDescriptionValidation() {
        const descField = document.getElementById('description');
        if (!descField) return;

        const counter = this.createCounter('text-muted');
        descField.parentNode.appendChild(counter);

        const validate = () => {
            const length = descField.value.length;
            const maxLength = 1000;

            counter.textContent = `${length}/${maxLength} caracteres`;

            if (length > maxLength) {
                this.setFieldState(descField, counter, 'invalid', `Máximo ${maxLength} caracteres`);
            } else {
                this.setFieldState(descField, counter, 'valid', `${length}/${maxLength} caracteres ✓`);
            }
        };

        descField.addEventListener('input', validate);
        validate();
    }

    // Validación de la dirección
    setupAddressValidation() {
        const addressField = document.getElementById('address');
        if (!addressField) return;

        const counter = this.createCounter('text-muted');
        addressField.parentNode.appendChild(counter);

        const validate = () => {
            const length = addressField.value.length;
            const minLength = 10;
            const maxLength = 255;

            counter.textContent = `${length}/${maxLength} caracteres`;

            if (length === 0) {
                this.setFieldState(addressField, counter, 'neutral', 'La dirección es obligatoria');
            } else if (length < minLength) {
                this.setFieldState(addressField, counter, 'invalid', `Mínimo ${minLength} caracteres`);
            } else if (length > maxLength) {
                this.setFieldState(addressField, counter, 'invalid', `Máximo ${maxLength} caracteres`);
            } else {
                this.setFieldState(addressField, counter, 'valid', `${length}/${maxLength} caracteres ✓`);
            }
        };

        addressField.addEventListener('input', validate);
        validate();
    }

    // Validación del teléfono
    setupPhoneValidation() {
        const phoneField = document.getElementById('phone');
        if (!phoneField) return;

        const counter = this.createCounter('text-muted');
        counter.textContent = 'Formato: +1 234 567 8900 (opcional)';
        phoneField.parentNode.appendChild(counter);

        const validate = () => {
            const value = phoneField.value;
            const phoneRegex = /^[\+]?[1-9][\d]{0,14}$/;

            if (value === '') {
                this.setFieldState(phoneField, counter, 'neutral', 'Formato: +1 234 567 8900 (opcional)');
            } else if (phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
                this.setFieldState(phoneField, counter, 'valid', 'Formato válido ✓');
            } else {
                this.setFieldState(phoneField, counter, 'invalid', 'Formato inválido. Ej: +1 234 567 8900');
            }
        };

        phoneField.addEventListener('input', validate);
        validate();
    }

    // Validación del email
    setupEmailValidation() {
        const emailField = document.getElementById('email');
        if (!emailField) return;

        const counter = this.createCounter('text-muted');
        counter.textContent = 'Formato: contacto@restaurante.com (opcional)';
        emailField.parentNode.appendChild(counter);

        const validate = () => {
            const value = emailField.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (value === '') {
                this.setFieldState(emailField, counter, 'neutral', 'Formato: contacto@restaurante.com (opcional)');
            } else if (emailRegex.test(value)) {
                this.setFieldState(emailField, counter, 'valid', 'Email válido ✓');
            } else {
                this.setFieldState(emailField, counter, 'invalid', 'Formato inválido. Ej: contacto@restaurante.com');
            }
        };

        emailField.addEventListener('input', validate);
        validate();
    }

    // Validación del sitio web
    setupWebsiteValidation() {
        const websiteField = document.getElementById('website');
        if (!websiteField) return;

        const counter = this.createCounter('text-muted');
        counter.textContent = 'Formato: https://www.restaurante.com (opcional)';
        websiteField.parentNode.appendChild(counter);

        const validate = () => {
            const value = websiteField.value;
            const urlRegex = /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/;

            if (value === '') {
                this.setFieldState(websiteField, counter, 'neutral', 'Formato: https://www.restaurante.com (opcional)');
            } else if (urlRegex.test(value)) {
                this.setFieldState(websiteField, counter, 'valid', 'URL válida ✓');
            } else {
                this.setFieldState(websiteField, counter, 'invalid', 'Formato inválido. Debe incluir http:// o https://');
            }
        };

        websiteField.addEventListener('input', validate);
        validate();
    }

    // Validación de fotos
    setupPhotosValidation() {
        const photosField = document.getElementById('photos');
        if (!photosField) return;

        const counter = this.createCounter('text-muted');
        counter.textContent = '0/8 fotos seleccionadas (opcional)';
        photosField.parentNode.appendChild(counter);

        photosField.addEventListener('change', function() {
            const fileCount = this.files.length;
            const maxFiles = 8;

            if (fileCount === 0) {
                this.classList.remove('is-valid', 'is-invalid');
                counter.className = 'form-text text-muted';
                counter.textContent = '0/8 fotos seleccionadas (opcional)';
            } else if (fileCount > maxFiles) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                counter.className = 'form-text text-danger';
                counter.textContent = `Máximo ${maxFiles} fotos (${fileCount}/${maxFiles})`;
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                counter.className = 'form-text text-success';
                counter.textContent = `${fileCount}/${maxFiles} fotos seleccionadas ✓`;
            }
        });
    }

    // Validación de categorías
    setupCategoriesValidation() {
        const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
        if (categoryCheckboxes.length === 0) return;

        const container = categoryCheckboxes[0].closest('.mb-3');
        const counter = this.createCounter('text-muted');
        counter.textContent = 'Selecciona entre 1 y 5 categorías';
        container.appendChild(counter);

        const validate = () => {
            const checkedCount = document.querySelectorAll('input[name="categories[]"]:checked').length;

            if (checkedCount === 0) {
                counter.className = 'form-text text-danger';
                counter.textContent = 'Debes seleccionar al menos una categoría';
            } else if (checkedCount > 5) {
                counter.className = 'form-text text-danger';
                counter.textContent = `Máximo 5 categorías (${checkedCount}/5)`;
            } else {
                counter.className = 'form-text text-success';
                counter.textContent = `${checkedCount}/5 categorías seleccionadas ✓`;
            }
        };

        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', validate);
        });
        validate();
    }

    // Validación de coordenadas
    setupCoordinatesValidation() {
        const latField = document.getElementById('latitude');
        const lngField = document.getElementById('longitude');
        if (!latField || !lngField) return;

        const validate = () => {
            const lat = latField.value;
            const lng = lngField.value;

            // Limpiar estados previos
            latField.classList.remove('is-valid', 'is-invalid');
            lngField.classList.remove('is-valid', 'is-invalid');

            if ((lat && !lng) || (!lat && lng)) {
                if (lat && !lng) {
                    lngField.classList.add('is-invalid');
                    this.showTooltip(lngField, 'Si proporcionas latitud, también debes proporcionar longitud');
                } else {
                    latField.classList.add('is-invalid');
                    this.showTooltip(latField, 'Si proporcionas longitud, también debes proporcionar latitud');
                }
            } else if (lat && lng) {
                // Validar rangos
                const latNum = parseFloat(lat);
                const lngNum = parseFloat(lng);

                if (latNum >= -90 && latNum <= 90 && lngNum >= -180 && lngNum <= 180) {
                    latField.classList.add('is-valid');
                    lngField.classList.add('is-valid');
                } else {
                    latField.classList.add('is-invalid');
                    lngField.classList.add('is-invalid');
                }
            }
        };

        latField.addEventListener('blur', validate);
        lngField.addEventListener('blur', validate);
        latField.addEventListener('input', () => {
            latField.classList.remove('is-invalid');
        });
        lngField.addEventListener('input', () => {
            lngField.classList.remove('is-invalid');
        });
    }

    // Métodos auxiliares
    createCounter(className) {
        const counter = document.createElement('div');
        counter.className = `form-text ${className}`;
        return counter;
    }

    setFieldState(field, counter, state, message) {
        field.classList.remove('is-valid', 'is-invalid');
        
        switch (state) {
            case 'valid':
                field.classList.add('is-valid');
                counter.className = 'form-text text-success';
                break;
            case 'invalid':
                field.classList.add('is-invalid');
                counter.className = 'form-text text-danger';
                break;
            case 'neutral':
            default:
                counter.className = 'form-text text-muted';
                break;
        }
        
        counter.textContent = message;
    }

    showTooltip(element, message) {
        // Crear tooltip temporal
        const tooltip = document.createElement('div');
        tooltip.className = 'invalid-feedback d-block';
        tooltip.textContent = message;
        
        // Remover tooltip existente si existe
        const existingTooltip = element.parentNode.querySelector('.invalid-feedback');
        if (existingTooltip) {
            existingTooltip.remove();
        }
        
        element.parentNode.appendChild(tooltip);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            if (tooltip.parentNode) {
                tooltip.remove();
            }
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new RestaurantFormValidator();
});