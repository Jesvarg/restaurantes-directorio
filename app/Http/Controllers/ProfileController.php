<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'restaurants_count' => $user->restaurants()->count(),
            'favorites_count' => $user->favoriteRestaurants()->count(),
            'reviews_count' => $user->reviews()->count(),
        ];
        
        return view('profile.show', compact('user', 'stats'));
    }
}