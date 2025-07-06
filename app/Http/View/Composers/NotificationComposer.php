<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;

class NotificationComposer
{
    public function compose(View $view)
    {
        $notifications = [];
        $totalCount = 0;

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role === 'admin') {
                // Solo notificaciones de restaurantes pendientes para administradores
                $pendingCount = Restaurant::where('status', 'pending')->count();
                
                if ($pendingCount > 0) {
                    $notifications[] = [
                        'type' => 'pending_restaurants',
                        'title' => 'Restaurantes pendientes',
                        'message' => $pendingCount === 1 
                            ? '1 restaurante esperando aprobación' 
                            : "{$pendingCount} restaurantes esperando aprobación",
                        'count' => $pendingCount,
                        'url' => route('admin.restaurants.pending'),
                        'icon' => 'bi-clock-history',
                        'color' => 'warning'
                    ];
                    $totalCount += $pendingCount;
                }
            } elseif ($user->role === 'owner') {
                // Notificaciones para propietarios
                $userRestaurants = $user->restaurants();
                
                // Restaurantes pendientes del propietario
                $pendingCount = $userRestaurants->where('status', 'pending')->count();
                if ($pendingCount > 0) {
                    $notifications[] = [
                        'type' => 'my_pending_restaurants',
                        'title' => 'Mis restaurantes pendientes',
                        'message' => $pendingCount === 1 
                            ? '1 restaurante esperando aprobación' 
                            : "{$pendingCount} restaurantes esperando aprobación",
                        'count' => $pendingCount,
                        'url' => route('dashboard.restaurants'),
                        'icon' => 'bi-clock-history',
                        'color' => 'warning'
                    ];
                    $totalCount += $pendingCount;
                }

                // Restaurantes rechazados del propietario
                $rejectedCount = $userRestaurants->where('status', 'rejected')->count();
                if ($rejectedCount > 0) {
                    $notifications[] = [
                        'type' => 'my_rejected_restaurants',
                        'title' => 'Restaurantes rechazados',
                        'message' => $rejectedCount === 1 
                            ? '1 restaurante fue rechazado' 
                            : "{$rejectedCount} restaurantes fueron rechazados",
                        'count' => $rejectedCount,
                        'url' => route('dashboard.restaurants'),
                        'icon' => 'bi-x-circle',
                        'color' => 'danger'
                    ];
                    $totalCount += $rejectedCount;
                }

                // Restaurantes suspendidos del propietario
                $suspendedCount = $userRestaurants->where('status', 'suspended')->count();
                if ($suspendedCount > 0) {
                    $notifications[] = [
                        'type' => 'my_suspended_restaurants',
                        'title' => 'Restaurantes suspendidos',
                        'message' => $suspendedCount === 1 
                            ? '1 restaurante fue suspendido' 
                            : "{$suspendedCount} restaurantes fueron suspendidos",
                        'count' => $suspendedCount,
                        'url' => route('dashboard.restaurants'),
                        'icon' => 'bi-pause-circle',
                        'color' => 'warning'
                    ];
                    $totalCount += $suspendedCount;
                }
            }
        }

        $view->with([
            'notifications' => $notifications,
            'notificationCount' => $totalCount
        ]);
    }
}