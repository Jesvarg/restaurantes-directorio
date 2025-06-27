# Directorio de Restaurantes

Una aplicación web moderna para descubrir, compartir y reseñar restaurantes en tu área. Construida con Laravel.

## Características

### 🍽️ Para Usuarios
- **Explorar Restaurantes**: Navega por una amplia variedad de restaurantes
- **Búsqueda Avanzada**: Busca por nombre, categoría, ubicación y rango de precios
- **Reseñas y Calificaciones**: Lee y escribe reseñas detalladas
- **Favoritos**: Guarda tus restaurantes favoritos
- **Galería de Fotos**: Ve fotos de los restaurantes y sus platillos
- **Información Detallada**: Horarios, contacto, ubicación en mapa

### 👨‍💼 Para Propietarios
- **Agregar Restaurantes**: Registra tu restaurante en el directorio
- **Gestión Completa**: Edita información, fotos y detalles
- **Estado de Publicación**: Control de visibilidad (pendiente/activo/inactivo)
- **Múltiples Categorías**: Clasifica tu restaurante en hasta 5 categorías

### 🔧 Características Técnicas
- **Responsive Design**: Optimizado para móviles, tablets y desktop
- **Autenticación Segura**: Sistema de login y registro
- **Validación Robusta**: Validación tanto en frontend como backend
- **Subida de Imágenes**: Soporte para múltiples formatos de imagen
- **Búsqueda en Tiempo Real**: Filtros dinámicos y búsqueda instantánea
- **Interfaz Moderna**: UI/UX intuitiva con Bootstrap 5

## Tecnologías Utilizadas

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Bootstrap 5, JavaScript ES6+
- **Base de Datos**: MySQL/PostgreSQL/SQLite
- **Iconos**: Bootstrap Icons
- **Estilos**: CSS3 con variables personalizadas

## Requisitos del Sistema

- PHP >= 8.1
- Composer
- Node.js & NPM (opcional, para compilar assets)
- MySQL >= 5.7 o PostgreSQL >= 10 o SQLite
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Instalación

### 1. Clonar el Repositorio
```bash
git clone <repository-url>
cd RestaurantesApp
```

### 2. Instalar Dependencias
```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js (opcional)
npm install
```

### 3. Configuración del Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos
Edita el archivo `.env` con tus credenciales de base de datos:

```env
APP_NAME="Directorio Restaurantes"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_directory
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Ejecutar Migraciones y Seeders
```bash
# Crear las tablas de la base de datos
php artisan migrate

# Poblar con datos de ejemplo (categorías)
php artisan db:seed
```

### 6. Configurar Almacenamiento (Opcional)
Si planeas subir imágenes:

```bash
# Crear enlace simbólico para storage público
php artisan storage:link
```

### 7. Iniciar el Servidor
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## Estructura del Proyecto

```
RestaurantesApp/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   └── RestaurantController.php
│   ├── Http/Requests/
│   │   ├── StoreRestaurantRequest.php
│   │   └── UpdateRestaurantRequest.php
│   └── Models/
│       ├── User.php
│       ├── Restaurant.php
│       ├── Category.php
│       ├── Review.php
│       ├── Photo.php
│       └── Favorite.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── restaurants/
│       └── auth/
└── routes/
    └── web.php
```

## Uso de la Aplicación

### Para Usuarios

1. **Registro/Login**: Crea una cuenta o inicia sesión
2. **Explorar**: Navega por los restaurantes disponibles
3. **Buscar**: Usa los filtros para encontrar restaurantes específicos
4. **Reseñar**: Deja reseñas y calificaciones
5. **Favoritos**: Guarda restaurantes para visitarlos después

### Para Propietarios de Restaurantes

1. **Registro**: Crea una cuenta de usuario
2. **Agregar Restaurante**: Usa el formulario para registrar tu restaurante
3. **Completar Información**: Agrega descripción, fotos, horarios, etc.
4. **Gestionar**: Edita la información cuando sea necesario

## Características de Seguridad

- **Autenticación**: Sistema seguro de login/registro
- **Autorización**: Solo los propietarios pueden editar sus restaurantes
- **Validación**: Validación robusta en frontend y backend
- **Sanitización**: Limpieza de datos de entrada
- **CSRF Protection**: Protección contra ataques CSRF
- **Rate Limiting**: Limitación de solicitudes para prevenir spam

## Personalización

### Estilos
Los estilos personalizados se encuentran en las vistas Blade usando `@push('styles')`.

### JavaScript
La funcionalidad JavaScript se incluye usando `@push('scripts')` en cada vista.

### Configuración
Modifica el archivo `.env` para personalizar:
- Nombre de la aplicación
- URL base
- Configuración de base de datos
- Configuración de correo (para futuras funcionalidades)

## Funcionalidades Futuras

- [ ] Integración de Chatbot
- [ ] Sistema de notificaciones por email
- [ ] Integración con mapas (Google Maps/OpenStreetMap)
- [ ] Sistema de reservas
- [ ] Sistema de cupones y descuentos
- [ ] Integración con redes sociales
