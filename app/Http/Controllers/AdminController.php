<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
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
     * Rechazar restaurante
     */
    public function rejectRestaurant(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ], [
            'reason.required' => 'Debe proporcionar una razón para el rechazo.'
        ]);

        try {
            $restaurant->update([
                'status' => 'rejected',
                'rejection_reason' => $request->input('reason'),
                'suspension_reason' => null
            ]);
            
            return back()->with('success', "🚫 Restaurante '{$restaurant->name}' rechazado.");
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
            'reason' => 'required|string|max:500'
        ], [
            'reason.required' => 'Debe proporcionar una razón para la suspensión.'
        ]);

        try {
            $restaurant->update([
                'status' => 'suspended',
                'suspension_reason' => $request->input('reason'),
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
}