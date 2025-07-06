<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $roles - Roles separados por comas (ej: 'admin,owner')
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Verificar si el usuario está suspendido
        if ($user->suspended_at) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido suspendida.');
        }

        // Verificar si el usuario tiene alguno de los roles requeridos
        if (!in_array($user->role, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}