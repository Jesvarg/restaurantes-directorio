<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * Constructor - aplicar middleware de autenticación donde sea necesario
     */
    public function __construct()
    {
        // Solo los métodos create, store, edit, update, destroy requieren autenticación
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Mostrar lista de restaurantes
     * GET /restaurants
     */
    public function index(Request $request)
    {
        // Construir query base con relaciones necesarias
        $query = Restaurant::with(['categories', 'reviews', 'photos'])
                          ->active(); // Solo restaurantes activos

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtro por rango de precio
        if ($request->filled('price_range')) {
            $query->byPriceRange($request->price_range);
        }

        // Ordenamiento
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'rating':
                $query->withAvg('reviews', 'rating')
                      ->orderByDesc('reviews_avg_rating');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'price_low':
                $query->orderBy('price_range');
                break;
            case 'price_high':
                $query->orderByDesc('price_range');
                break;
            default:
                $query->orderBy('name');
        }

        $restaurants = $query->paginate(12)->withQueryString();
        $categories = Category::popular()->orderBy('name')->get();

        return view('restaurants.index', compact('restaurants', 'categories'));
    }

    /**
     * Mostrar formulario de creación
     * GET /restaurants/create
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('restaurants.create', compact('categories'));
    }

    /**
     * Almacenar nuevo restaurante
     * POST /restaurants
     * 1. Validar datos de entrada usando StoreRestaurantRequest
     * 2. Crear restaurante
     * 3. Asociar categorías
     * 4. Redirigir con mensaje de éxito
     */
    public function store(StoreRestaurantRequest $request)
    {
        // Los datos ya están validados por StoreRestaurantRequest
        $validated = $request->validated();

        try {
            // Crear restaurante
            $restaurant = Restaurant::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'website' => $validated['website'],
                'price_range' => $validated['price_range'],
                'user_id' => Auth::id(),
                'status' => 'pending', // Requiere aprobación
            ]);

            // Asociar categorías
            $restaurant->categories()->attach($validated['categories']);

            // Procesar fotos si existen
            if ($request->hasFile('photos')) {
                $this->handlePhotoUploads($restaurant, $request->file('photos'));
            }

            return redirect()->route('restaurants.show', $restaurant)
                           ->with('success', 'Restaurante creado exitosamente. Está pendiente de aprobación.');
                           
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Ocurrió un error al crear el restaurante. Inténtalo de nuevo.'
            ])->withInput();
        }
    }

    /**
     * Mostrar restaurante específico
     * GET /restaurants/{restaurant}
     */
    public function show(Restaurant $restaurant)
    {
        // Cargar relaciones necesarias con optimización de consultas
        $restaurant->load([
            'categories',
            'photos' => function ($query) {
                $query->ordered();
            },
            'reviews' => function ($query) {
                $query->with('user')->latest()->limit(10);
            },
            'user'
        ]);

        // Verificar si el usuario actual ha marcado como favorito
        $isFavorite = Auth::check() && Auth::user()->favorites()->where('restaurant_id', $restaurant->id)->exists();
        
        // Verificar si el usuario puede editar
        $canEdit = Auth::check() && (Auth::id() === $restaurant->user_id || Auth::user()->is_admin ?? false);

        return view('restaurants.show', compact('restaurant', 'isFavorite', 'canEdit'));
    }

    /**
     * Mostrar formulario de edición
     * GET /restaurants/{restaurant}/edit
     */
    public function edit(Restaurant $restaurant)
    {
        // Verificar permisos
        if (Auth::id() !== $restaurant->user_id) {
            abort(403, 'No tienes permisos para editar este restaurante.');
        }

        $categories = Category::orderBy('name')->get();
        $restaurant->load('categories', 'photos');
        
        return view('restaurants.edit', compact('restaurant', 'categories'));
    }

    /**
     * Actualizar restaurante
     * PUT /restaurants/{restaurant}
     * Usa UpdateRestaurantRequest para validación y autorización
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        // Los permisos y validación ya están manejados por UpdateRestaurantRequest
        $validated = $request->validated();

        try {
            // Actualizar restaurante
            $restaurant->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'website' => $validated['website'],
                'price_range' => $validated['price_range'],
                'status' => 'pending', // Requiere nueva aprobación tras edición
            ]);

            // Actualizar categorías
            $restaurant->categories()->sync($validated['categories']);

            // Procesar nuevas fotos
            if ($request->hasFile('photos')) {
                $this->handlePhotoUploads($restaurant, $request->file('photos'));
            }

            return redirect()->route('restaurants.show', $restaurant)
                           ->with('success', 'Restaurante actualizado exitosamente.');
                           
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el restaurante.'
            ])->withInput();
        }
    }

    /**
     * Eliminar restaurante
     * DELETE /restaurants/{restaurant}
     */
    public function destroy(Restaurant $restaurant)
    {
        // Verificar permisos
        if (Auth::id() !== $restaurant->user_id) {
            abort(403, 'No tienes permisos para eliminar este restaurante.');
        }

        try {
            // Eliminar fotos del almacenamiento
            foreach ($restaurant->photos as $photo) {
                if (Storage::disk('public')->exists($photo->url)) {
                    Storage::disk('public')->delete($photo->url);
                }
            }

            $restaurant->delete();

            return redirect()->route('restaurants.index')
                           ->with('success', 'Restaurante eliminado exitosamente.');
                           
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Ocurrió un error al eliminar el restaurante.'
            ]);
        }
    }

    /**
     * Manejar subida de fotos
     * Método privado para procesar múltiples fotos
     */
    private function handlePhotoUploads(Restaurant $restaurant, array $photos)
    {
        foreach ($photos as $index => $photo) {
            if ($photo && $photo->isValid()) {
                // Generar nombre único
                $filename = time() . '_' . $index . '.' . $photo->getClientOriginalExtension();
                
                // Guardar archivo
                $path = $photo->storeAs('restaurants', $filename, 'public');
                
                // Crear registro en base de datos
                $restaurant->photos()->create([
                    'url' => $path,
                    'alt_text' => "Foto de {$restaurant->name}",
                    'is_primary' => $index === 0 && $restaurant->photos()->count() === 0,
                    'order' => $restaurant->photos()->count() + 1,
                ]);
            }
        }
    }
}