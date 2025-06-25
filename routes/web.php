<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route - redirect to restaurants index
Route::get('/', function () {
    return redirect()->route('restaurants.index');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Registration routes
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout route (for authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Restaurant Routes
Route::resource('restaurants', RestaurantController::class)->except(['destroy']);

// Additional Restaurant Routes
Route::middleware('auth')->group(function () {
    // Favorite routes
    Route::post('/restaurants/{restaurant}/favorite', [RestaurantController::class, 'toggleFavorite'])
        ->name('restaurants.favorite');
    
    // Review routes
    Route::post('/restaurants/{restaurant}/reviews', [RestaurantController::class, 'storeReview'])
        ->name('restaurants.reviews.store');
});

// API Routes for AJAX requests
Route::prefix('api')->middleware('auth')->group(function () {
    Route::post('/restaurants/{restaurant}/favorite', [RestaurantController::class, 'toggleFavorite'])
        ->name('api.restaurants.favorite');
    
    Route::post('/restaurants/{restaurant}/reviews', [RestaurantController::class, 'storeReview'])
        ->name('api.restaurants.reviews.store');
});

// Search and Filter Routes
Route::get('/search', [RestaurantController::class, 'search'])->name('restaurants.search');
Route::get('/category/{category}', [RestaurantController::class, 'byCategory'])->name('restaurants.category');

// User Dashboard Routes (for future implementation)
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return redirect()->route('restaurants.index');
    })->name('dashboard');
    
    Route::get('/my-restaurants', [RestaurantController::class, 'myRestaurants'])
        ->name('dashboard.restaurants');
    
    Route::get('/my-favorites', [RestaurantController::class, 'myFavorites'])
        ->name('dashboard.favorites');
    
    Route::get('/my-reviews', [RestaurantController::class, 'myReviews'])
        ->name('dashboard.reviews');
});
