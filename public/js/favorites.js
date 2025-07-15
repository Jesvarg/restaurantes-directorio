/**
 * Funcionalidad de favoritos para restaurantes
 * Maneja la adición y eliminación de restaurantes de la lista de favoritos del usuario
 */

/**
 * Alterna el estado de favorito de un restaurante
 * @param {number} restaurantId - ID del restaurante
 */
function toggleFavorite(restaurantId) {
    // Verificar si el usuario está autenticado
    if (!window.isAuthenticated) {
        Swal.fire({
            icon: 'warning',
            title: 'Inicia sesión',
            text: 'Debes iniciar sesión para agregar restaurantes a favoritos.',
            confirmButtonText: 'Iniciar sesión',
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/login';
            }
        });
        return;
    }

    const favoriteBtn = document.querySelector(`[data-restaurant-id="${restaurantId}"]`);
    if (!favoriteBtn) {
        console.error('Botón de favorito no encontrado');
        return;
    }
    
    const icon = favoriteBtn.querySelector('i');
    const originalIcon = icon.className;
    
    // Aplicar efecto splash con clase CSS
    favoriteBtn.classList.add('splash-effect');
    favoriteBtn.disabled = true;
    
    // Realizar petición AJAX
    fetch(`/restaurants/${restaurantId}/toggle-favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Actualizar UI
            if (data.is_favorite) {
                icon.className = 'bi bi-heart-fill';
                favoriteBtn.className = favoriteBtn.className.replace('btn-outline-secondary', 'btn-outline-danger');
                favoriteBtn.title = 'Quitar de favoritos';
            } else {
                icon.className = 'bi bi-heart';
                favoriteBtn.className = favoriteBtn.className.replace('btn-outline-danger', 'btn-outline-secondary');
                favoriteBtn.title = 'Agregar a favoritos';
            }
            
            // Mostrar mensaje de éxito
            if (typeof showSuccessMessage === 'function') {
                showSuccessMessage(data.message);
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        } else {
            throw new Error(data.message || 'Error desconocido');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Restaurar estado original en caso de error
        icon.className = originalIcon;
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al procesar tu solicitud. Inténtalo de nuevo.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    })
    .finally(() => {
        // Remover efecto splash y rehabilitar botón
        favoriteBtn.classList.remove('splash-effect');
        favoriteBtn.disabled = false;
    });
}

/**
 * Inicializar la funcionalidad de favoritos cuando el DOM esté listo
 */
document.addEventListener('DOMContentLoaded', function() {
    // Agregar estilos CSS para el efecto splash
    const style = document.createElement('style');
    style.textContent = `
        .splash-effect {
            animation: splashAnimation 0.3s ease-out;
        }
        
        @keyframes splashAnimation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Hover sutil opcional */
        .btn[data-restaurant-id]:hover:not(:disabled) {
            transform: scale(1.02);
            transition: transform 0.1s ease;
        }
    `;
    document.head.appendChild(style);
    
    // Agregar event listeners a todos los botones de favoritos
    const favoriteButtons = document.querySelectorAll('[data-restaurant-id]');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const restaurantId = this.getAttribute('data-restaurant-id');
            toggleFavorite(restaurantId);
        });
    });
});