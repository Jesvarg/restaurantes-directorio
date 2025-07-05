<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Photo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Campos que pueden ser asignados masivamente: url, alt_text, is_primary, order, imageable_id, imageable_type
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'alt_text',
        'is_primary',
        'order',
        'imageable_id',
        'imageable_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Una foto puede pertenecer a diferentes modelos (polimórfica)
     * Relación polimórfica: morphTo()
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope para fotos principales
     * Filtra solo fotos marcadas como principales
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope para fotos ordenadas
     * Ordena las fotos por el campo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    /**
     * Get the full URL for the photo
     */
    public function getFullUrlAttribute()
    {
        if ($this->url) {
            // Check if it's an external URL (starts with http:// or https://)
            if (filter_var($this->url, FILTER_VALIDATE_URL) && 
                (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://'))) {
                return $this->url;
            }
            
            // It's a local file, add storage path
            return asset('storage/' . $this->url);
        }
        
        return asset('images/placeholder-restaurant.svg');
    }

    /**
     * Accessor para obtener el texto alternativo por defecto
     * Genera un alt_text por defecto si no existe
     */
    public function getAltTextAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // Genera un alt_text basado en el modelo relacionado
        if ($this->imageable) {
            $modelName = class_basename($this->imageable);
            $modelTitle = $this->imageable->name ?? $this->imageable->title ?? 'Item';
            return "Foto de {$modelName}: {$modelTitle}";
        }
        
        return 'Imagen';
    }

    /**
     * Boot method para eventos del modelo
     * Maneja la lógica de fotos principales
     */
    protected static function boot()
    {
        parent::boot();
        
        // Cuando se marca una foto como principal, desmarca las demás del mismo modelo
        static::saving(function ($photo) {
            if ($photo->is_primary && $photo->imageable_id && $photo->imageable_type) {
                static::where('imageable_id', $photo->imageable_id)
                      ->where('imageable_type', $photo->imageable_type)
                      ->where('id', '!=', $photo->id)
                      ->update(['is_primary' => false]);
            }
        });
    }
}