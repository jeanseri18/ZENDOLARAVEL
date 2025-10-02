<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Traveler;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;

class TravelerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of travelers.
     */
    public function index(Request $request)
    {
        $query = Traveler::with(['user', 'packages']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($userQuery) use ($search) {
                $userQuery->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('vehicle_type', 'like', "%{$search}%")
              ->orWhere('vehicle_model', 'like', "%{$search}%")
              ->orWhere('license_plate', 'like', "%{$search}%");
        }

        // Filter by verification status
        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === 'yes');
        }

        // Filter by availability
        if ($request->filled('available')) {
            $query->where('is_available', $request->available === 'yes');
        }

        // Filter by vehicle type
        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $travelers = $query->latest()->paginate(15);

        return view('admin.travelers.index', compact('travelers'));
    }

    /**
     * Show the form for creating a new traveler.
     */
    public function create()
    {
        $users = User::whereDoesntHave('traveler')
                    ->where('is_active', true)
                    ->get();
        
        return view('admin.travelers.create', compact('users'));
    }

    /**
     * Store a newly created traveler in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:travelers,user_id',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20|unique:travelers,license_plate',
            'driver_license' => 'required|string|max:50',
            'max_weight_kg' => 'required|numeric|min:1|max:1000',
            'max_dimensions' => 'nullable|string|max:255',
            'service_areas' => 'required|array|min:1',
            'service_areas.*' => 'string|max:255',
            'hourly_rate' => 'required|numeric|min:5|max:100',
            'bio' => 'nullable|string|max:1000',
            'is_verified' => 'boolean',
            'is_available' => 'boolean',
        ]);

        $traveler = Traveler::create([
            'user_id' => $request->user_id,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_model' => $request->vehicle_model,
            'vehicle_year' => $request->vehicle_year,
            'license_plate' => strtoupper($request->license_plate),
            'driver_license' => $request->driver_license,
            'max_weight_kg' => $request->max_weight_kg,
            'max_dimensions' => $request->max_dimensions,
            'service_areas' => $request->service_areas,
            'hourly_rate' => $request->hourly_rate,
            'bio' => $request->bio,
            'is_verified' => $request->boolean('is_verified'),
            'is_available' => $request->boolean('is_available'),
            'rating' => 0,
            'total_deliveries' => 0,
        ]);

        return redirect()->route('admin.travelers.index')
                        ->with('success', 'Livreur créé avec succès.');
    }

    /**
     * Display the specified traveler.
     */
    public function show(Traveler $traveler)
    {
        $traveler->load([
            'user', 
            'packages' => function($query) {
                $query->with(['sender', 'receiver'])->latest();
            },
            'proposedTrips' => function($query) {
                $query->latest();
            }
        ]);
        
        // Get recent deliveries statistics
        $recentStats = [
            'total_packages' => $traveler->packages()->count(),
            'completed_packages' => $traveler->packages()->where('status', 'delivered')->count(),
            'in_progress_packages' => $traveler->packages()->whereIn('status', ['active', 'in_transit'])->count(),
            'total_earnings' => $traveler->packages()->where('status', 'delivered')->sum('delivery_fee'),
            'avg_rating' => $traveler->rating,
        ];
        
        return view('admin.travelers.show', compact('traveler', 'recentStats'));
    }

    /**
     * Show the form for editing the specified traveler.
     */
    public function edit(Traveler $traveler)
    {
        $users = User::where(function($query) use ($traveler) {
            $query->whereDoesntHave('traveler')
                  ->orWhere('user_id', $traveler->user_id);
        })->where('is_active', true)->get();
        
        return view('admin.travelers.edit', compact('traveler', 'users'));
    }

    /**
     * Update the specified traveler in storage.
     */
    public function update(Request $request, Traveler $traveler)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:travelers,user_id,' . $traveler->traveler_id . ',traveler_id',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20|unique:travelers,license_plate,' . $traveler->traveler_id . ',traveler_id',
            'driver_license' => 'required|string|max:50',
            'max_weight_kg' => 'required|numeric|min:1|max:1000',
            'max_dimensions' => 'nullable|string|max:255',
            'service_areas' => 'required|array|min:1',
            'service_areas.*' => 'string|max:255',
            'hourly_rate' => 'required|numeric|min:5|max:100',
            'bio' => 'nullable|string|max:1000',
            'is_verified' => 'boolean',
            'is_available' => 'boolean',
        ]);

        $traveler->update([
            'user_id' => $request->user_id,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_model' => $request->vehicle_model,
            'vehicle_year' => $request->vehicle_year,
            'license_plate' => strtoupper($request->license_plate),
            'driver_license' => $request->driver_license,
            'max_weight_kg' => $request->max_weight_kg,
            'max_dimensions' => $request->max_dimensions,
            'service_areas' => $request->service_areas,
            'hourly_rate' => $request->hourly_rate,
            'bio' => $request->bio,
            'is_verified' => $request->boolean('is_verified'),
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()->route('admin.travelers.index')
                        ->with('success', 'Livreur mis à jour avec succès.');
    }

    /**
     * Remove the specified traveler from storage.
     */
    public function destroy(Traveler $traveler)
    {
        // Prevent deletion if traveler has active packages
        $activePackages = $traveler->packages()->whereIn('status', ['active', 'in_transit'])->count();
        if ($activePackages > 0) {
            return redirect()->route('admin.travelers.index')
                           ->with('error', 'Impossible de supprimer un livreur avec des colis actifs.');
        }

        $traveler->delete();

        return redirect()->route('admin.travelers.index')
                        ->with('success', 'Livreur supprimé avec succès.');
    }

    /**
     * Toggle traveler verification status.
     */
    public function toggleVerification(Traveler $traveler)
    {
        $traveler->update([
            'is_verified' => !$traveler->is_verified,
            'verified_at' => $traveler->is_verified ? null : now(),
        ]);

        $status = $traveler->is_verified ? 'vérifié' : 'non vérifié';
        
        return redirect()->back()
                        ->with('success', "Livreur marqué comme {$status}.");
    }

    /**
     * Toggle traveler availability status.
     */
    public function toggleAvailability(Traveler $traveler)
    {
        $traveler->update([
            'is_available' => !$traveler->is_available,
        ]);

        $status = $traveler->is_available ? 'disponible' : 'indisponible';
        
        return redirect()->back()
                        ->with('success', "Livreur marqué comme {$status}.");
    }

    /**
     * Export travelers to CSV.
     */
    public function export()
    {
        $travelers = Traveler::with(['user'])->get();
        
        $filename = 'travelers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($travelers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Nom', 'Email', 'Téléphone', 'Type véhicule', 'Modèle', 
                'Plaque', 'Poids max (kg)', 'Tarif/h (€)', 'Note', 'Livraisons', 
                'Vérifié', 'Disponible', 'Date inscription'
            ]);
            
            foreach ($travelers as $traveler) {
                fputcsv($file, [
                    $traveler->traveler_id,
                    $traveler->user->first_name . ' ' . $traveler->user->last_name,
                    $traveler->user->email,
                    $traveler->user->phone,
                    $traveler->vehicle_type,
                    $traveler->vehicle_model,
                    $traveler->license_plate,
                    $traveler->max_weight_kg,
                    $traveler->hourly_rate,
                    number_format($traveler->rating, 1),
                    $traveler->total_deliveries,
                    $traveler->is_verified ? 'Oui' : 'Non',
                    $traveler->is_available ? 'Oui' : 'Non',
                    $traveler->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get traveler statistics.
     */
    public function stats()
    {
        $stats = [
            'total' => Traveler::count(),
            'verified' => Traveler::where('is_verified', true)->count(),
            'available' => Traveler::where('is_available', true)->count(),
            'active_deliveries' => Package::whereIn('status', ['active', 'in_transit'])
                                         ->whereNotNull('traveler_id')
                                         ->count(),
            'avg_rating' => Traveler::where('rating', '>', 0)->avg('rating'),
            'total_deliveries' => Traveler::sum('total_deliveries'),
        ];

        return response()->json($stats);
    }
}