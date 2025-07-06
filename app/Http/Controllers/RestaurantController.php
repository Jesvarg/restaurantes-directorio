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

        $restaurants = $query->paginate(30)->withQueryString();
        $categories = Category::popular()->orderBy('name')->get();

        return view('restaurants.index', compact('restaurants', 'categories'));
    }

    /**
     * Mostrar formulario de creación
     * GET /restaurants/create
     */
    public function create()
    {
        // Solo admin y owner pueden crear restaurantes
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'owner'])) {
            abort(403, 'No tienes permisos para crear restaurantes.');
        }
        
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
        try {
            \Log::info('Store method called', [
                'has_files' => $request->hasFile('photos'),
                'files_count' => $request->hasFile('photos') ? count($request->file('photos')) : 0,
                'photo_urls_count' => $request->filled('photo_urls') ? count($request->photo_urls) : 0,
                'all_files' => $request->allFiles()
            ]);
            
            DB::beginTransaction();

            // Crear el restaurante
            $restaurant = Restaurant::create([
                'name' => $request->name,
                'description' => $request->description,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'price_range' => $request->price_range,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'user_id' => Auth::id(),
                'status' => 'pending', // Requiere aprobación
            ]);
            \Log::info('Restaurant created', ['restaurant_id' => $restaurant->id]);

            // Asociar categorías
            $restaurant->categories()->attach($request->categories);
            \Log::info('Categories attached');

            // Manejar fotos subidas
            if ($request->hasFile('photos')) {
                \Log::info('Processing uploaded photos');
                $this->handlePhotoUploads($restaurant, $request->file('photos'));
            }

            // Manejar URLs de fotos
            if ($request->filled('photo_urls')) {
                \Log::info('Processing photo URLs');
                $this->handlePhotoUrls($restaurant, $request->photo_urls);
            }

            DB::commit();
            \Log::info('Transaction committed successfully');

            return redirect()->route('restaurants.show', $restaurant)
                ->with('success', 'Restaurante creado exitosamente. Está pendiente de aprobación.');

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Error creating restaurant: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el restaurante. Por favor, inténtalo de nuevo.');
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
            'user'
        ]);
        
        // Paginar reseñas (5 por página)
        $reviews = $restaurant->reviews()
            ->with('user')
            ->latest()
            ->paginate(5, ['*'], 'reviews_page');

        // Verificar si el usuario actual ha marcado como favorito
        $isFavorite = Auth::check() && Auth::user()->favorites()->where('restaurant_id', $restaurant->id)->exists();
        
        // Verificar si el usuario puede editar
        $canEdit = Auth::check() && (Auth::id() === $restaurant->user_id || Auth::user()->role === 'admin');

        return view('restaurants.show', compact('restaurant', 'reviews', 'isFavorite', 'canEdit'));
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
            // Process opening hours from time picker format
            $openingHours = $this->processOpeningHours($validated);
            
            // Actualizar restaurante
            $restaurant->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'website' => $validated['website'],
                'opening_hours' => $openingHours,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'price_range' => $validated['price_range'],
                'status' => 'pending', // Requiere nueva aprobación tras edición
            ]);

            // Actualizar categorías
            $restaurant->categories()->sync($validated['categories']);

            // Eliminar fotos marcadas para eliminación
            if ($request->filled('delete_photos')) {
                $photosToDelete = $restaurant->photos()->whereIn('id', $request->input('delete_photos'))->get();
                foreach ($photosToDelete as $photo) {
                    // Eliminar archivo del almacenamiento si es local
                    if (str_starts_with($photo->url, 'storage/')) {
                        Storage::disk('public')->delete(str_replace('storage/', '', $photo->url));
                    }
                    $photo->delete();
                }
            }

            // Procesar nuevas fotos subidas
            if ($request->hasFile('photos')) {
                $this->handlePhotoUploads($restaurant, $request->file('photos'));
            }
            
            // Procesar nuevas URLs de fotos
            if ($request->filled('photo_urls')) {
                $this->handlePhotoUrls($restaurant, $request->input('photo_urls'));
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
     * Alternar favorito
     * POST /restaurants/{restaurant}/favorite
     */
    public function toggleFavorite(Restaurant $restaurant)
    {
        $user = Auth::user();
        
        if ($user->favorites()->where('restaurant_id', $restaurant->id)->exists()) {
            $user->favorites()->detach($restaurant->id);
            $message = 'Restaurante eliminado de favoritos';
            $isFavorite = false;
        } else {
            $user->favorites()->attach($restaurant->id);
            $message = 'Restaurante agregado a favoritos';
            $isFavorite = true;
        }
        
        if (request()->ajax() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'favorited' => $isFavorite
            ]);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Guardar review
     * POST /restaurants/{restaurant}/reviews
     */
    public function storeReview(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);
        
        // Verificar que el usuario no haya dejado ya una review
        $existingReview = $restaurant->reviews()->where('user_id', Auth::id())->first();
        
        if ($existingReview) {
            return back()->withErrors(['error' => 'Ya has dejado una reseña para este restaurante.']);
        }
        
        $restaurant->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        
        return back()->with('success', 'Reseña agregada exitosamente.');
    }
    
    /**
     * Mis restaurantes
     */
    public function myRestaurants()
    {
        $restaurants = Restaurant::where('user_id', Auth::id())
                                ->with(['categories', 'photos', 'reviews', 'rejectionReasons'])
                                ->latest()
                                ->paginate(10);
        
        return view('dashboard.my-restaurants', compact('restaurants'));
    }
    
    /**
     * Mis favoritos
     */
    public function myFavorites()
    {
        $restaurants = Auth::user()->favorites()
                          ->with(['categories', 'photos', 'reviews'])
                          ->latest('favorites.created_at')
                          ->paginate(10);
        
        return view('dashboard.my-favorites', compact('restaurants'));
    }
    
    /**
     * Mis reviews
     */
    public function myReviews()
    {
        $reviews = Auth::user()->reviews()
                      ->with('restaurant')
                      ->latest()
                      ->paginate(10);
        
        return view('dashboard.my-reviews', compact('reviews'));
    }

    /**
     * Manejar subida de fotos/**
     * Process opening hours from time picker format to JSON format
     */
    private function processOpeningHours(array $validated): ?array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $openingHours = [];
        
        foreach ($days as $day) {
            $isOpen = $validated['is_open'][$day] ?? false;
            
            if ($isOpen && isset($validated['open_time'][$day]) && isset($validated['close_time'][$day])) {
                $openTime = $validated['open_time'][$day];
                $closeTime = $validated['close_time'][$day];
                
                if ($openTime && $closeTime) {
                    $openingHours[$day] = $openTime . ' - ' . $closeTime;
                } else {
                    $openingHours[$day] = 'Cerrado';
                }
            } else {
                $openingHours[$day] = 'Cerrado';
            }
        }
        
        return empty($openingHours) ? null : $openingHours;
    }

    /**
     * Método privado para procesar múltiples fotos
     */
    private function handlePhotoUploads(Restaurant $restaurant, array $photos)
    {
        \Log::info('handlePhotoUploads called', [
            'photos_count' => count($photos),
            'restaurant_id' => $restaurant->id
        ]);
        
        foreach ($photos as $index => $photo) {
            if ($photo && $photo->isValid()) {
                try {
                    \Log::info('Processing photo', [
                        'index' => $index,
                        'original_name' => $photo->getClientOriginalName(),
                        'mime_type' => $photo->getMimeType(),
                        'size' => $photo->getSize()
                    ]);
                    
                    // Validar que sea una imagen
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                    $mimeType = $photo->getMimeType();
                    
                    if (!in_array($mimeType, $allowedMimes)) {
                        \Log::warning('Invalid mime type', ['mime' => $mimeType]);
                        continue; // Saltar archivos que no sean imágenes
                    }
                    
                    // Generar nombre único
                    $extension = $photo->getClientOriginalExtension();
                    if (empty($extension)) {
                        // Fallback basado en mime type
                        $mimeToExt = [
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',
                            'image/gif' => 'gif',
                            'image/webp' => 'webp'
                        ];
                        $extension = $mimeToExt[$mimeType] ?? 'jpg';
                    }
                    
                    $filename = time() . '_' . $index . '.' . $extension;
                    \Log::info('Generated filename', ['filename' => $filename]);
                    
                    // Guardar archivo
                    $path = $photo->storeAs('restaurants', $filename, 'public');
                    \Log::info('File stored', ['path' => $path]);
                    
                    // Crear registro en base de datos
                    $photoRecord = $restaurant->photos()->create([
                        'url' => $path,
                        'alt_text' => "Foto de {$restaurant->name}",
                        'is_primary' => $index === 0 && $restaurant->photos()->count() === 0,
                        'order' => $restaurant->photos()->count() + 1,
                    ]);
                    \Log::info('Photo record created', ['photo_id' => $photoRecord->id]);
                    
                } catch (\Exception $e) {
                    // Log error but continue with other photos
                    \Log::error('Error uploading photo: ' . $e->getMessage(), [
                        'index' => $index,
                        'trace' => $e->getTraceAsString()
                    ]);
                    continue;
                }
            }
        }
    }
    
    /**
     * Método privado para procesar URLs de fotos
     */
    private function handlePhotoUrls(Restaurant $restaurant, array $photoUrls)
    {
        foreach ($photoUrls as $index => $url) {
            if (!empty(trim($url))) {
                try {
                    // Validar que la URL sea válida y apunte a una imagen
                    if (filter_var($url, FILTER_VALIDATE_URL) && 
                        preg_match('/\.(jpeg|jpg|png|gif|webp)(\?.*)?$/i', $url)) {
                        
                        // Crear registro en base de datos
                        $restaurant->photos()->create([
                            'url' => $url,
                            'alt_text' => "Foto de {$restaurant->name}",
                            'is_primary' => $index === 0 && $restaurant->photos()->count() === 0,
                            'order' => $restaurant->photos()->count() + 1,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log error but continue with other URLs
                    \Log::error('Error processing photo URL: ' . $e->getMessage());
                    continue;
                }
            }
        }
    }
}