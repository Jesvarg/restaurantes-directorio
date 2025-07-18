<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRestaurantRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta petición
     * Solo el propietario del restaurante o admin pueden actualizarlo
     */
    public function authorize(): bool
    {
        $restaurant = $this->route('restaurant');
        
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return false;
        }

        // El propietario puede editar su restaurante
        if (Auth::id() === $restaurant->user_id) {
            return true;
        }

        // Los administradores pueden editar cualquier restaurante
        return Auth::user()->role === 'admin';
    }

    /**
     * Obtener las reglas de validación que se aplican a la petición
     */
    public function rules(): array
    {
        $restaurant = $this->route('restaurant');
        
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30', // Limitado a 50 caracteres para mejor visualización
                'regex:/^[\pL\s\-\.\d]+$/u', // Solo letras, espacios, guiones, puntos y números
            ],
            'description' => [
                'nullable',
                'string',
                'min:10',
                'max:500',
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:60',
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/', // Formato de teléfono flexible
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
                // Email único excepto para el restaurante actual
                Rule::unique('restaurants', 'email')->ignore($restaurant->id),
            ],
            'website' => [
                'nullable',
                'url',
                'max:255',
                'regex:/^https?:\/\/.+/', // Debe empezar con http:// o https://
            ],
            // Opening status for each day
            'is_open' => 'nullable|array',
            'is_open.monday' => 'nullable|boolean',
            'is_open.tuesday' => 'nullable|boolean',
            'is_open.wednesday' => 'nullable|boolean',
            'is_open.thursday' => 'nullable|boolean',
            'is_open.friday' => 'nullable|boolean',
            'is_open.saturday' => 'nullable|boolean',
            'is_open.sunday' => 'nullable|boolean',
            
            // Opening times
            'open_time' => 'nullable|array',
            'open_time.monday' => 'nullable|date_format:H:i',
            'open_time.tuesday' => 'nullable|date_format:H:i',
            'open_time.wednesday' => 'nullable|date_format:H:i',
            'open_time.thursday' => 'nullable|date_format:H:i',
            'open_time.friday' => 'nullable|date_format:H:i',
            'open_time.saturday' => 'nullable|date_format:H:i',
            'open_time.sunday' => 'nullable|date_format:H:i',
            
            // Closing times
            'close_time' => 'nullable|array',
            'close_time.monday' => 'nullable|date_format:H:i',
            'close_time.tuesday' => 'nullable|date_format:H:i',
            'close_time.wednesday' => 'nullable|date_format:H:i',
            'close_time.thursday' => 'nullable|date_format:H:i',
            'close_time.friday' => 'nullable|date_format:H:i',
            'close_time.saturday' => 'nullable|date_format:H:i',
            'close_time.sunday' => 'nullable|date_format:H:i',
            'price_range' => [
                'required',
                'integer',
                'between:1,4', // 1=Económico, 2=Moderado, 3=Caro, 4=Muy caro
            ],
            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],
            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],
            'categories' => [
                'required',
                'array',
                'min:1',
                'max:5', // Máximo 5 categorías por restaurante
            ],
            'categories.*' => [
                'integer',
                'exists:categories,id',
            ],
            'photos' => [
                'nullable',
                'array',
                'max:8', // Máximo 8 fotos
            ],
            'photos.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // Máximo 2MB por imagen
                'dimensions:min_width=300,min_height=200,max_width=2000,max_height=2000',
            ],
            'photo_urls' => [
                'nullable',
                'array',
                'max:8', // Máximo 8 URLs de fotos
            ],
            'photo_urls.*' => [
                'nullable',
                'url',
                'regex:/\.(jpeg|jpg|png|gif|webp)(\?.*)?$/i', // Debe terminar en extensión de imagen
            ],
            'delete_photos' => [
                'nullable',
                'array',
            ],
            'delete_photos.*' => [
                'integer',
                'exists:photos,id',
            ],
            // Campos adicionales para administradores
            'status' => [
                'sometimes',
                'string',
                Rule::in(['pending', 'approved', 'rejected', 'suspended']),
            ],
        ];
    }

    /**
     * Obtener los mensajes de error personalizados para las reglas de validación
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del restaurante es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder 30 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
            
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'description.max' => 'La descripción no puede exceder 500 caracteres.',
            
            'address.required' => 'La dirección es obligatoria.',
            'address.min' => 'La dirección debe tener al menos 10 caracteres.',
            'address.max' => 'La dirección no puede exceder 500 caracteres.',
            
            'phone.regex' => 'El formato del teléfono no es válido.',
            
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Ya existe un restaurante registrado con este email.',
            
            'website.url' => 'El sitio web debe ser una URL válida.',
            'website.regex' => 'El sitio web debe empezar con http:// o https://',
            
            'price_range.required' => 'El rango de precio es obligatorio.',
            'price_range.between' => 'El rango de precio debe estar entre 1 y 4.',
            
            'latitude.between' => 'La latitud debe estar entre -90 y 90 grados.',
            'longitude.between' => 'La longitud debe estar entre -180 y 180 grados.',
            
            'categories.required' => 'Debe seleccionar al menos una categoría.',
            'categories.min' => 'Debe seleccionar al menos una categoría.',
            'categories.max' => 'No puede seleccionar más de 5 categorías.',
            'categories.*.exists' => 'Una de las categorías seleccionadas no es válida.',
            
            'photos.max' => 'No puede subir más de 8 fotos.',
            'photos.*.image' => 'Todos los archivos deben ser imágenes.',
            'photos.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png, jpg, gif o webp.',
            'photos.*.max' => 'Cada imagen no puede exceder 2MB.',
            'photos.*.dimensions' => 'Las imágenes deben tener entre 300x200 y 2000x2000 píxeles.',
            
            'photo_urls.max' => 'No puede agregar más de 8 URLs de fotos.',
            'photo_urls.*.url' => 'Cada URL de foto debe ser una dirección web válida.',
            'photo_urls.*.regex' => 'Cada URL debe apuntar a una imagen (jpeg, jpg, png, gif, webp).',
            
            // Mensajes para horarios de atención
            'open_time.*.date_format' => 'La hora de apertura debe tener el formato HH:MM (24 horas).',
            'close_time.*.date_format' => 'La hora de cierre debe tener el formato HH:MM (24 horas).',
            
            'status.in' => 'El estado seleccionado no es válido.',
        ];
    }

    /**
     * Obtener los nombres de atributos personalizados para los errores de validación
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'address' => 'dirección',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'website' => 'sitio web',
            'price_range' => 'rango de precio',
            'latitude' => 'latitud',
            'longitude' => 'longitud',
            'categories' => 'categorías',
            'photos' => 'fotos',
            'photo_urls' => 'URLs de fotos',
            'status' => 'estado',
        ];
    }

    /**
     * Preparar los datos para validación
     * Se ejecuta antes de la validación
     */
    protected function prepareForValidation(): void
    {
        // Limpiar y formatear datos antes de validar
        $this->merge([
            'name' => trim($this->name),
            'description' => $this->description ? trim($this->description) : null,
            'address' => trim($this->address),
            'phone' => $this->phone ? preg_replace('/[^\+\d\s\-\(\)]/', '', $this->phone) : null,
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'website' => $this->website ? trim($this->website) : null,
        ]);

        // Solo permitir cambio de estado a administradores
        if (Auth::user()->role !== 'admin') {
            $this->request->remove('status');
        }
    }

    /**
     * Configurar el validador después de que se haya creado
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validación personalizada: si se proporciona latitud, también debe haber longitud
            if ($this->filled('latitude') && !$this->filled('longitude')) {
                $validator->errors()->add('longitude', 'La longitud es requerida cuando se proporciona la latitud.');
            }
            
            if ($this->filled('longitude') && !$this->filled('latitude')) {
                $validator->errors()->add('latitude', 'La latitud es requerida cuando se proporciona la longitud.');
            }

            // Validar formato de horarios de apertura
            if ($this->filled('opening_hours')) {
                foreach ($this->opening_hours as $day => $hours) {
                    if ($hours && !preg_match('/^\d{1,2}:\d{2}\s*-\s*\d{1,2}:\d{2}$|^cerrado$/i', $hours)) {
                        $validator->errors()->add(
                            "opening_hours.{$day}", 
                            "El horario para {$day} debe tener el formato HH:MM - HH:MM o 'Cerrado'."
                        );
                    }
                }
            }

            // Validación adicional: verificar que el usuario no esté intentando cambiar el propietario
            $restaurant = $this->route('restaurant');
            if ($this->filled('user_id') && $this->user_id != $restaurant->user_id) {
                if (Auth::user()->role !== 'admin') {
                    $validator->errors()->add('user_id', 'No tienes permisos para cambiar el propietario del restaurante.');
                }
            }
        });
    }
}