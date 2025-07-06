<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantRejectionReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name_invalid',
        'description_invalid',
        'address_invalid',
        'phone_invalid',
        'email_invalid',
        'categories_missing',
        'photos_missing',
        'website_invalid',
        'hours_invalid',
        'notes',
        'rejected_by',
    ];

    protected $casts = [
        'name_invalid' => 'boolean',
        'description_invalid' => 'boolean',
        'address_invalid' => 'boolean',
        'phone_invalid' => 'boolean',
        'email_invalid' => 'boolean',
        'categories_missing' => 'boolean',
        'photos_missing' => 'boolean',
        'website_invalid' => 'boolean',
        'hours_invalid' => 'boolean',
    ];

    /**
     * Relación con el restaurante
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Relación con el usuario administrador que rechazó
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Obtiene los campos que están marcados como inválidos
     */
    public function getInvalidFields(): array
    {
        $invalidFields = [];
        
        if ($this->name_invalid) $invalidFields[] = 'Nombre del restaurante';
        if ($this->description_invalid) $invalidFields[] = 'Descripción';
        if ($this->address_invalid) $invalidFields[] = 'Dirección';
        if ($this->phone_invalid) $invalidFields[] = 'Teléfono';
        if ($this->email_invalid) $invalidFields[] = 'Email';
        if ($this->categories_missing) $invalidFields[] = 'Categorías';
        if ($this->photos_missing) $invalidFields[] = 'Fotos';
        if ($this->website_invalid) $invalidFields[] = 'Sitio web';
        if ($this->hours_invalid) $invalidFields[] = 'Horarios';
        
        return $invalidFields;
    }
}
