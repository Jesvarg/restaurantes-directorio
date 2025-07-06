<?php

namespace App\Notifications;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RestaurantRejectedNotification extends Notification
{
    use Queueable;

    public Restaurant $restaurant;
    public array $rejectedFields;
    public ?string $notes;

    /**
     * Create a new notification instance.
     */
    public function __construct(Restaurant $restaurant, array $rejectedFields, ?string $notes = null)
    {
        $this->restaurant = $restaurant;
        $this->rejectedFields = $rejectedFields;
        $this->notes = $notes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Restaurante Rechazado - ' . $this->restaurant->name)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Lamentamos informarle que su restaurante "' . $this->restaurant->name . '" ha sido rechazado.')
            ->line('**Razones del rechazo:**');

        foreach ($this->rejectedFields as $index => $field) {
            $mailMessage->line(($index + 1) . '. ' . $field);
        }

        if ($this->notes) {
            $mailMessage->line('**Notas adicionales del administrador:**')
                       ->line($this->notes);
        }

        return $mailMessage
            ->line('Por favor, corrija estos aspectos y vuelva a enviar su restaurante para revisión.')
            ->action('Editar Restaurante', route('restaurants.edit', $this->restaurant))
            ->line('Gracias por su comprensión.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'restaurant_rejected',
            'restaurant_id' => $this->restaurant->id,
            'restaurant_name' => $this->restaurant->name,
            'rejected_fields' => $this->rejectedFields,
            'notes' => $this->notes,
            'message' => 'Su restaurante "' . $this->restaurant->name . '" ha sido rechazado. Revise los motivos y corrija los aspectos indicados.'
        ];
    }
}
