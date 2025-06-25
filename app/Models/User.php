<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Campos que pueden ser asignados masivamente: name, email, password
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Un usuario puede tener muchos restaurantes
     * Relación uno a muchos: hasMany(Restaurant::class)
     */
    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class);
    }

    /**
     * Un usuario puede tener muchas reseñas
     * Relación uno a muchos: hasMany(Review::class)
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Un usuario puede marcar muchos restaurantes como favoritos
     * Relación muchos a muchos: belongsToMany(Restaurant::class, 'favorites')
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'favorites')->withTimestamps();
    }

    /**
     * Scope para usuarios activos
     * Filtra usuarios que han verificado su email
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Accessor para capitalizar el nombre
     * Capitaliza cada palabra del nombre del usuario
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}
