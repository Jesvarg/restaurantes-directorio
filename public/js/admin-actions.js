/**
 * Funciones para acciones administrativas
 * Manejo de confirmaciones para aprobar, reactivar y eliminar elementos
 */

/**
 * Confirma la aprobación de un restaurante
 * @param {Event} event - Evento del formulario
 * @param {string} restaurantName - Nombre del restaurante
 * @param {string} actionUrl - URL de la acción
 */
function confirmApprove(event, restaurantName, actionUrl) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Aprobar restaurante?',
        text: `¿Estás seguro de que deseas aprobar "${restaurantName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, aprobar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear y enviar formulario
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            
            // Token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Método PATCH
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

/**
 * Confirma la reactivación de un restaurante
 * @param {Event} event - Evento del formulario
 * @param {string} restaurantName - Nombre del restaurante
 * @param {string} actionUrl - URL de la acción
 */
function confirmReactivate(event, restaurantName, actionUrl) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Reactivar restaurante?',
        text: `¿Estás seguro de que deseas reactivar "${restaurantName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear y enviar formulario
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            
            // Token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Método PATCH
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

/**
 * Confirma la reactivación de un usuario
 * @param {Event} event - Evento del formulario
 * @param {string} userName - Nombre del usuario
 * @param {string} actionUrl - URL de la acción
 */
function confirmReactivateUser(event, userName, actionUrl) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Reactivar usuario?',
        text: `¿Estás seguro de que deseas reactivar al usuario "${userName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear y enviar formulario
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            
            // Token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Método PATCH
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

/**
 * Confirma la eliminación de un usuario
 * @param {Event} event - Evento del formulario
 * @param {string} userName - Nombre del usuario
 * @param {string} actionUrl - URL de la acción
 */
function confirmDeleteUser(event, userName, actionUrl) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: `¿Estás seguro de que deseas eliminar al usuario "${userName}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear y enviar formulario
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            
            // Token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Método DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}