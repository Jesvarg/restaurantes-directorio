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
    const icon = favoriteBtn.querySelector('i');
    const originalIcon = icon.className;
    
    // Mostrar estado de carga
    icon.className = 'bi bi-arrow-repeat';
    favoriteBtn.disabled = true;
    
    // Realizar petición AJAX
    fetch(`/restaurants/${restaurantId}/toggle-favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar el icono según el nuevo estado
            if (data.is_favorite) {
                icon.className = 'bi bi-heart-fill text-danger';
                favoriteBtn.title = 'Quitar de favoritos';
                
                // Mostrar mensaje de éxito
                showSuccessMessage(data.message);
            } else {
                icon.className = 'bi bi-heart';
                favoriteBtn.title = 'Agregar a favoritos';
                
                // Mostrar notificación de eliminación
                Swal.fire({
                    icon: 'info',
                    title: 'Eliminado de favoritos',
                    text: 'El restaurante se eliminó de tu lista de favoritos.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        } else {
            // Restaurar icono original en caso de error
            icon.className = originalIcon;
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Ocurrió un error al procesar la solicitud.',
                confirmButtonText: 'Entendido'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Restaurar icono original en caso de error
        icon.className = originalIcon;
        
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
            confirmButtonText: 'Entendido'
        });
    })
    .finally(() => {
        // Rehabilitar el botón
        favoriteBtn.disabled = false;
    });
}

/**
 * Inicializar la funcionalidad de favoritos cuando el DOM esté listo
 */
document.addEventListener('DOMContentLoaded', function() {
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