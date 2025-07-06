<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\RestaurantRejectionReason;
use App\Services\RestaurantRejectionNotificationService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Constructor - solo administradores pueden acceder
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Acceso denegado. Solo administradores.');
            }
            return $next($request);
        });
    }

    /**
     * Panel de administración principal
     */
    public function dashboard()
    {
        $pendingRestaurants = Restaurant::where('status', 'pending')->count();
        $totalRestaurants = Restaurant::count();
        $totalUsers = User::count();
        $recentRestaurants = Restaurant::with(['user', 'categories'])
                                      ->where('status', 'pending')
                                      ->latest()
                                      ->take(5)
                                      ->get();

        return view('admin.dashboard', compact(
            'pendingRestaurants',
            'totalRestaurants', 
            'totalUsers',
            'recentRestaurants'
        ));
    }

    /**
     * Lista de restaurantes pendientes de aprobación
     */
    public function pendingRestaurants()
    {
        $restaurants = Restaurant::with(['user', 'categories', 'photos'])
                                ->where('status', 'pending')
                                ->latest()
                                ->paginate(10);

        return view('admin.pending-restaurants', compact('restaurants'));
    }

    /**
     * Aprobar restaurante
     */
    public function approveRestaurant(Restaurant $restaurant)
    {
        try {
            $restaurant->update([
                'status' => 'approved',
                'rejection_reason' => null,
                'suspension_reason' => null
            ]);
            
            return back()->with('success', "✅ Restaurante '{$restaurant->name}' aprobado exitosamente.");
        } catch (\Exception $e) {
            return back()->with('error', "❌ Error al aprobar el restaurante: " . $e->getMessage());
        }
    }

    /**
     * Rechazar restaurante con checks estructurados
     */
    public function rejectRestaurant(Request $request, Restaurant $restaurant, RestaurantRejectionNotificationService $notificationService)
    {
        // Validar que al menos un checkbox esté marcado
        $rejectionChecks = $request->input('rejection_checks', []);
        if (empty($rejectionChecks) || !array_filter($rejectionChecks)) {
            return back()->withErrors(['rejection_checks' => 'Debe seleccionar al menos un motivo de rechazo.'])->withInput();
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            // Actualizar estado del restaurante
            $restaurant->update([
                'status' => 'rejected',
                'rejection_reason' => 'Ver detalles en razones de rechazo',
                'suspension_reason' => null
            ]);

            // Crear registro de razones de rechazo
            $rejectionReason = $restaurant->rejectionReasons()->create([
                'name_invalid' => $request->input('rejection_checks.name_invalid', false),
                'description_invalid' => $request->input('rejection_checks.description_invalid', false),
                'address_invalid' => $request->input('rejection_checks.address_invalid', false),
                'phone_invalid' => $request->input('rejection_checks.phone_invalid', false),
                'email_invalid' => $request->input('rejection_checks.email_invalid', false),
                'categories_missing' => $request->input('rejection_checks.categories_missing', false),
                'photos_missing' => $request->input('rejection_checks.photos_missing', false),
                'website_invalid' => $request->input('rejection_checks.website_invalid', false),
                'hours_invalid' => $request->input('rejection_checks.hours_invalid', false),
                'notes' => $request->input('notes'),
                'rejected_by' => auth()->id(),
            ]);

            // Enviar notificación al propietario del restaurante
            $notificationService->sendRejectionNotification($restaurant, $rejectionReason);
            
            return back()->with('success', "🚫 Restaurante '{$restaurant->name}' rechazado con detalles estructurados. Se ha notificado al propietario.");
        } catch (\Exception $e) {
            return back()->with('error', "❌ Error al rechazar el restaurante: " . $e->getMessage());
        }
    }

    /**
     * Suspender restaurante
     */
    public function suspendRestaurant(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:500'
        ], [
            'suspension_reason.required' => 'Debe proporcionar una razón para la suspensión.'
        ]);

        try {
            $restaurant->update([
                'status' => 'suspended',
                'suspension_reason' => $request->input('suspension_reason'),
                'rejection_reason' => null
            ]);
            
            return back()->with('success', "⏸️ Restaurante '{$restaurant->name}' suspendido.");
        } catch (\Exception $e) {
            return back()->with('error', "❌ Error al suspender el restaurante: " . $e->getMessage());
        }
    }

    /**
     * Reactivar restaurante
     */
    public function reactivateRestaurant(Restaurant $restaurant)
    {
        try {
            $restaurant->update([
                'status' => 'approved',
                'rejection_reason' => null,
                'suspension_reason' => null
            ]);
            
            return back()->with('success', "✅ Restaurante '{$restaurant->name}' reactivado exitosamente.");
        } catch (\Exception $e) {
            return back()->with('error', "❌ Error al reactivar el restaurante: " . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas para all-restaurants
     */
    public function allRestaurants(Request $request)
    {
        $query = Restaurant::with(['user', 'categories', 'photos']);
    
        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Filtro por búsqueda
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }
    
        $restaurants = $query->latest()->paginate(15);
    
        // Obtener estadísticas por estado
        $statusCounts = Restaurant::selectRaw('status, COUNT(*) as count')
                                 ->groupBy('status')
                                 ->pluck('count', 'status')
                                 ->toArray();
    
        return view('admin.all-restaurants', compact('restaurants', 'statusCounts'));
    }

    /**
     * Lista de todos los usuarios
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filtro por rol
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('suspended_at');
            } elseif ($request->status === 'suspended') {
                $query->whereNotNull('suspended_at');
            }
        }

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->withCount('restaurants')->latest()->paginate(15);

        // Obtener estadísticas
        $totalUsers = User::count();
        $activeUsers = User::whereNull('suspended_at')->count();
        $suspendedUsers = User::whereNotNull('suspended_at')->count();
        $adminUsers = User::where('role', 'admin')->count();

        return view('admin.users', compact('users', 'totalUsers', 'activeUsers', 'suspendedUsers', 'adminUsers'));
    }

    /**
     * Suspender usuario
     */
    public function suspendUser(Request $request, User $user)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:1000'
        ], [
            'suspension_reason.required' => 'Debe proporcionar una razón para la suspensión.'
        ]);

        // No permitir suspender administradores
        if ($user->role === 'admin') {
            return back()->with('error', 'No se puede suspender a un administrador.');
        }

        // No permitir auto-suspensión
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes suspenderte a ti mismo.');
        }

        $user->update([
            'suspended_at' => now(),
            'suspension_reason' => $request->suspension_reason
        ]);

        return back()->with('success', 'Usuario suspendido correctamente.');
    }

    /**
     * Reactivar usuario
     */
    public function reactivateUser(User $user)
    {
        $user->update([
            'suspended_at' => null,
            'suspension_reason' => null
        ]);

        return back()->with('success', 'Usuario reactivado correctamente.');
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser(User $user)
    {
        // No permitir eliminar administradores
        if ($user->role === 'admin') {
            return back()->with('error', 'No se puede eliminar a un administrador.');
        }

        // No permitir auto-eliminación
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        // Eliminar restaurantes asociados
        $user->restaurants()->delete();
        
        // Eliminar usuario
        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}