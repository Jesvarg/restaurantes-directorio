<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Restaurant extends Model
{
    use HasFactory;

    // Constantes para estados del restaurante
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_ACTIVE = 'active';     // Mantener para compatibilidad
    public const STATUS_INACTIVE = 'inactive'; // Mantener para compatibilidad

    // Array de estados válidos
    public const VALID_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_SUSPENDED,
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    /**
     * The attributes that are mass assignable.
     * Campos que pueden ser asignados masivamente
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'opening_hours',
        'price_range',
        'status',
        'rejection_reason',
        'suspension_reason',
        'latitude',
        'longitude',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'opening_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'price_range' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Un restaurante pertenece a un usuario
     * Relación muchos a uno: belongsTo(User::class)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un restaurante puede tener muchas reseñas
     * Relación uno a muchos: hasMany(Review::class)
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Un restaurante puede pertenecer a muchas categorías
     * Relación muchos a muchos: belongsToMany(Category::class)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * Un restaurante puede tener muchas fotos (relación polimórfica)
     * Relación polimórfica: morphMany(Photo::class, 'imageable')
     */
    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'imageable')->orderBy('order');
    }

    /**
     * Un restaurante puede ser favorito de muchos usuarios
     * Relación muchos a muchos: belongsToMany(User::class, 'favorites')
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Un restaurante puede tener razones de rechazo
     * Relación uno a muchos: hasMany(RestaurantRejectionReason::class)
     */
    public function rejectionReasons(): HasMany
    {
        return $this->hasMany(RestaurantRejectionReason::class);
    }

    /**
     * Obtiene la última razón de rechazo
     */
    public function getLatestRejectionReason()
    {
        return $this->rejectionReasons()->latest()->first();
    }

    /**
     * Scope para restaurantes activos/aprobados
     * Filtra solo restaurantes con status = 'approved'
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope para restaurantes visibles públicamente
     */
    public function scopePublic($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Verifica si el restaurante está aprobado
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Verifica si el restaurante está pendiente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifica si el restaurante está rechazado
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Verifica si el restaurante está suspendido
     */
    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /**
     * Scope para restaurantes por rango de precio
     * Filtra restaurantes por rango de precio específico
     */
    public function scopeByPriceRange($query, $range)
    {
        return $query->where('price_range', $range);
    }

    /**
     * Scope para búsqueda por nombre o descripción
     * Busca en nombre y descripción del restaurante
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('address', 'like', "%{$term}%");
        });
    }

    /**
     * Accessor para capitalizar el nombre
     * Capitaliza cada palabra del nombre del restaurante
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Accessor para obtener el rating promedio
     * Calcula el promedio de todas las reseñas
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Accessor para obtener el total de reseñas
     * Cuenta el número total de reseñas
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Accessor para obtener la foto principal
     * Retorna la primera foto marcada como principal
     */
    public function getPrimaryPhotoAttribute()
    {
        return $this->photos()->where('is_primary', true)->first();
    }

    /**
     * Accessor para obtener la URL de la foto principal
     * Retorna la URL de la primera foto marcada como principal
     */
    public function getPrimaryPhotoUrlAttribute()
    {
        $primaryPhoto = $this->primaryPhoto;
        return $primaryPhoto ? $primaryPhoto->full_url : asset('images/placeholder-restaurant.svg');
    }

    /**
     * Accessor para formatear el rango de precios
     * Convierte el número a símbolos de dólar
     */
    public function getPriceRangeDisplayAttribute()
    {
        return str_repeat('$', $this->price_range);
    }
}