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
        $restaurant->update(['status' => 'approved']);
        
        return back()->with('success', "Restaurante '{$restaurant->name}' aprobado exitosamente.");
    }

    /**
     * Rechazar restaurante
     */
    public function rejectRestaurant(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $restaurant->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('reason')
        ]);
        
        return back()->with('success', "Restaurante '{$restaurant->name}' rechazado.");
    }

    /**
     * Suspender restaurante
     */
    public function suspendRestaurant(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $restaurant->update([
            'status' => 'suspended',
            'suspension_reason' => $request->input('reason')
        ]);
        
        return back()->with('success', "Restaurante '{$restaurant->name}' suspendido.");
    }

    /**
     * Reactivar restaurante
     */
    public function reactivateRestaurant(Restaurant $restaurant)
    {
        $restaurant->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'suspension_reason' => null
        ]);
        
        return back()->with('success', "Restaurante '{$restaurant->name}' reactivado.");
    }

    /**
     * Lista de todos los restaurantes para administración
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

        return view('admin.all-restaurants', compact('restaurants'));
    }
}