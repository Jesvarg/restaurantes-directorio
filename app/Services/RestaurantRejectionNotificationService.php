<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\RestaurantRejectionReason;
use App\Models\User;
use App\Notifications\RestaurantRejectedNotification;

class RestaurantRejectionNotificationService
{
    /**
     * Envía notificación de rechazo al propietario del restaurante
     */
    public function sendRejectionNotification(Restaurant $restaurant, RestaurantRejectionReason $rejectionReason): void
    {
        $rejectedFields = $this->formatRejectedFields($rejectionReason);
        
        $restaurant->user->notify(new RestaurantRejectedNotification(
            $restaurant,
            $rejectedFields,
            $rejectionReason->notes
        ));
    }
    
    /**
     * Formatea los campos rechazados en un mensaje legible
     */
    private function formatRejectedFields(RestaurantRejectionReason $rejectionReason): array
    {
        $fieldMessages = [
            'name_invalid' => 'El nombre del restaurante es inválido o inapropiado',
            'description_invalid' => 'La descripción necesita ser mejorada o es inadecuada',
            'address_invalid' => 'La dirección está incorrecta o incompleta',
            'contact_invalid' => 'La información de contacto (teléfono/email) es inválida',
            'photos_missing' => 'Faltan fotos o las existentes son inadecuadas',
            'categories_invalid' => 'Las categorías seleccionadas son incorrectas',
            'duplicate_restaurant' => 'Este restaurante ya existe en nuestro directorio',
            'other_reason' => 'Otros motivos (ver notas adicionales)'
        ];
        
        $rejectedFields = [];
        $invalidFields = $rejectionReason->getInvalidFields();
        
        foreach ($invalidFields as $field) {
            if (isset($fieldMessages[$field])) {
                $rejectedFields[] = $fieldMessages[$field];
            }
        }
        
        return $rejectedFields;
    }
    
    /**
     * Genera un mensaje de rechazo completo
     */
    public function generateRejectionMessage(Restaurant $restaurant, RestaurantRejectionReason $rejectionReason): string
    {
        $rejectedFields = $this->formatRejectedFields($rejectionReason);
        
        $message = "Su restaurante '{$restaurant->name}' ha sido rechazado por las siguientes razones:\n\n";
        
        foreach ($rejectedFields as $index => $field) {
            $message .= ($index + 1) . ". {$field}\n";
        }
        
        if ($rejectionReason->notes) {
            $message .= "\nNotas adicionales del administrador:\n{$rejectionReason->notes}";
        }
        
        $message .= "\n\nPor favor, corrija estos aspectos y vuelva a enviar su restaurante para revisión.";
        
        return $message;
    }
}