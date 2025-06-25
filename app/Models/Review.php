<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Campos que pueden ser asignados masivamente: rating, comment, user_id, restaurant_id
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'restaurant_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Una reseña pertenece a un usuario
     * Relación muchos a uno: belongsTo(User::class)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una reseña pertenece a un restaurante
     * Relación muchos a uno: belongsTo(Restaurant::class)
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Scope para reseñas por rating específico
     * Filtra reseñas por calificación específica
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope para reseñas con comentarios
     * Filtra solo reseñas que tienen comentarios
     */
    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment')->where('comment', '!=', '');
    }

    /**
     * Scope para reseñas recientes
     * Filtra reseñas de los últimos 30 días
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Accessor para obtener las estrellas como string
     * Convierte el rating numérico a estrellas visuales
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Accessor para truncar comentarios largos
     * Limita el comentario a 100 caracteres para vistas de resumen
     */
    public function getShortCommentAttribute()
    {
        if (!$this->comment) {
            return null;
        }
        
        return strlen($this->comment) > 100 
            ? substr($this->comment, 0, 100) . '...' 
            : $this->comment;
    }

    /**
     * Mutator para validar el rating
     * Asegura que el rating esté entre 1 y 5
     */
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = max(1, min(5, (int) $value));
    }
}