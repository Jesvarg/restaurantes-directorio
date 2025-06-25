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
     * Scope para restaurantes activos
     * Filtra solo restaurantes con status = 'active'
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
     * Accessor para formatear el rango de precios
     * Convierte el número a símbolos de dólar
     */
    public function getPriceRangeDisplayAttribute()
    {
        return str_repeat('$', $this->price_range);
    }
}