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
    
    // Review routes
    Route::post('/restaurants/{restaurant}/reviews', [RestaurantController::class, 'storeReview'])
        ->name('restaurants.reviews.store');
    
    // Ruta para toggle de favoritos
    Route::post('/restaurants/{restaurant}/toggle-favorite', [RestaurantController::class, 'toggleFavorite'])
        ->name('restaurants.toggleFavorite');
});

// API Routes for AJAX requests
Route::prefix('api')->middleware('auth')->group(function () {
    
    Route::post('/restaurants/{restaurant}/reviews', [RestaurantController::class, 'storeReview'])
        ->name('api.restaurants.reviews.store');
});

// Search and Filter Routes
Route::get('/search', [RestaurantController::class, 'search'])->name('restaurants.search');
Route::get('/category/{category}', [RestaurantController::class, 'byCategory'])->name('restaurants.category');

// User Dashboard Routes
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return redirect()->route('restaurants.index');
    })->name('dashboard');
    
    // Solo propietarios y admins pueden acceder a mis restaurantes
    Route::get('/my-restaurants', [RestaurantController::class, 'myRestaurants'])
        ->name('dashboard.restaurants')
        ->middleware('role:owner,admin');
    
    Route::get('/my-favorites', [RestaurantController::class, 'myFavorites'])
        ->name('dashboard.favorites');
    
    Route::get('/my-reviews', [RestaurantController::class, 'myReviews'])
        ->name('dashboard.reviews');
    
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])
        ->name('profile.show');
});

// Admin Routes (solo para administradores)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])
        ->name('dashboard');
    
    Route::get('/restaurants/pending', [App\Http\Controllers\AdminController::class, 'pendingRestaurants'])
        ->name('restaurants.pending');
    
    Route::get('/restaurants/all', [App\Http\Controllers\AdminController::class, 'allRestaurants'])
        ->name('restaurants.all');
    
    Route::patch('/restaurants/{restaurant}/approve', [App\Http\Controllers\AdminController::class, 'approveRestaurant'])
        ->name('restaurants.approve');
    
    Route::patch('/restaurants/{restaurant}/reject', [App\Http\Controllers\AdminController::class, 'rejectRestaurant'])
        ->name('restaurants.reject');
    
    Route::patch('/restaurants/{restaurant}/suspend', [App\Http\Controllers\AdminController::class, 'suspendRestaurant'])
        ->name('restaurants.suspend');
    
    Route::patch('/restaurants/{restaurant}/reactivate', [App\Http\Controllers\AdminController::class, 'reactivateRestaurant'])
        ->name('restaurants.reactivate');
    
    // User management routes
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])
        ->name('users');
    
    Route::patch('/users/{user}/suspend', [App\Http\Controllers\AdminController::class, 'suspendUser'])
        ->name('users.suspend');
    
    Route::patch('/users/{user}/reactivate', [App\Http\Controllers\AdminController::class, 'reactivateUser'])
        ->name('users.reactivate');
    
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])
        ->name('users.delete');
});
