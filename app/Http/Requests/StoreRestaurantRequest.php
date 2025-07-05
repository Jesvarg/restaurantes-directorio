<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRestaurantRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta petición
     * Solo usuarios autenticados pueden crear restaurantes
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Obtener las reglas de validación que se aplican a la petición
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[\pL\s\-\.\d]+$/u', // Solo letras, espacios, guiones, puntos y números
            ],
            'description' => [
                'nullable',
                'string',
                'min:10',
                'max:1000',
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:500',
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
                'unique:restaurants,email', // Email único por restaurante
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
                'max:8', // Máximo 8 URLs
            ],
            'photo_urls.*' => [
                'nullable',
                'url',
                'regex:/\.(jpeg|jpg|png|gif|webp)(\?.*)?$/i', // Debe terminar en extensión de imagen
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
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
            
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'description.max' => 'La descripción no puede exceder 1000 caracteres.',
            
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
            'photo_urls.*.url' => 'Cada URL debe ser válida.',
            'photo_urls.*.regex' => 'Las URLs deben apuntar a archivos de imagen (jpeg, jpg, png, gif, webp).',
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
        });
    }
}