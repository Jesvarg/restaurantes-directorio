<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Campos que pueden ser asignados masivamente: name, description, slug
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Una categoría puede pertenecer a muchos restaurantes
     * Relación muchos a muchos: belongsToMany(Restaurant::class)
     */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class)->withTimestamps();
    }

    /**
     * Scope para categorías populares
     * Filtra categorías que tienen al menos un restaurante
     */
    public function scopePopular($query)
    {
        return $query->has('restaurants');
    }

    /**
     * Accessor para capitalizar el nombre
     * Capitaliza cada palabra del nombre de la categoría
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Mutator para generar slug automáticamente
     * Genera un slug URL-friendly basado en el nombre
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get the route key for the model.
     * Permite usar el slug en las rutas en lugar del ID
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}