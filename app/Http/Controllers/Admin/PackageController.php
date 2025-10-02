<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\Traveler;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of packages.
     */
    public function index(Request $request)
    {
        $query = Package::with(['sender', 'receiver', 'traveler']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('origin_city', 'like', "%{$search}%")
                  ->orWhere('destination_city', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $packages = $query->latest()->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        $users = User::where('is_active', true)->get();
        $travelers = Traveler::with('user')->whereHas('user', function($q) {
            $q->where('is_active', true);
        })->get();
        
        return view('admin.packages.create', compact('users', 'travelers'));
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,user_id',
            'receiver_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'weight_kg' => 'required|numeric|min:0.1|max:50',
            'dimensions' => 'nullable|string|max:255',
            'value_euros' => 'required|numeric|min:0',
            'origin_city' => 'required|string|max:255',
            'destination_city' => 'required|string|max:255',
            'pickup_date' => 'required|date|after:today',
            'delivery_deadline' => 'required|date|after:pickup_date',
            'priority' => 'required|in:normal,urgent,express',
            'requires_signature' => 'boolean',
            'fragile' => 'boolean',
            'special_instructions' => 'nullable|string',
        ]);

        $package = Package::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'weight_kg' => $request->weight_kg,
            'dimensions' => $request->dimensions,
            'value_euros' => $request->value_euros,
            'origin_city' => $request->origin_city,
            'destination_city' => $request->destination_city,
            'pickup_date' => $request->pickup_date,
            'delivery_deadline' => $request->delivery_deadline,
            'priority' => $request->priority,
            'requires_signature' => $request->boolean('requires_signature'),
            'fragile' => $request->boolean('fragile'),
            'special_instructions' => $request->special_instructions,
            'status' => 'pending',
            'tracking_number' => $this->generateTrackingNumber(),
        ]);

        return redirect()->route('admin.packages.index')
                        ->with('success', 'Colis créé avec succès.');
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package)
    {
        $package->load(['sender', 'receiver', 'traveler', 'transactions', 'proposals', 'messages']);
        
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package)
    {
        $users = User::where('is_active', true)->get();
        $travelers = Traveler::with('user')->whereHas('user', function($q) {
            $q->where('is_active', true);
        })->get();
        
        return view('admin.packages.edit', compact('package', 'users', 'travelers'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,user_id',
            'receiver_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'weight_kg' => 'required|numeric|min:0.1|max:50',
            'dimensions' => 'nullable|string|max:255',
            'value_euros' => 'required|numeric|min:0',
            'origin_city' => 'required|string|max:255',
            'destination_city' => 'required|string|max:255',
            'pickup_date' => 'required|date',
            'delivery_deadline' => 'required|date|after:pickup_date',
            'priority' => 'required|in:normal,urgent,express',
            'status' => 'required|in:pending,active,in_transit,delivered,cancelled',
            'requires_signature' => 'boolean',
            'fragile' => 'boolean',
            'special_instructions' => 'nullable|string',
        ]);

        $package->update([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'weight_kg' => $request->weight_kg,
            'dimensions' => $request->dimensions,
            'value_euros' => $request->value_euros,
            'origin_city' => $request->origin_city,
            'destination_city' => $request->destination_city,
            'pickup_date' => $request->pickup_date,
            'delivery_deadline' => $request->delivery_deadline,
            'priority' => $request->priority,
            'status' => $request->status,
            'requires_signature' => $request->boolean('requires_signature'),
            'fragile' => $request->boolean('fragile'),
            'special_instructions' => $request->special_instructions,
        ]);

        return redirect()->route('admin.packages.index')
                        ->with('success', 'Colis mis à jour avec succès.');
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(Package $package)
    {
        // Prevent deletion of packages that are in transit or delivered
        if (in_array($package->status, ['in_transit', 'delivered'])) {
            return redirect()->route('admin.packages.index')
                           ->with('error', 'Impossible de supprimer un colis en transit ou livré.');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
                        ->with('success', 'Colis supprimé avec succès.');
    }

    /**
     * Assign traveler to package.
     */
    public function assignTraveler(Request $request, Package $package)
    {
        $request->validate([
            'traveler_id' => 'required|exists:travelers,traveler_id',
        ]);

        $package->update([
            'traveler_id' => $request->traveler_id,
            'status' => 'active',
        ]);

        return redirect()->back()
                        ->with('success', 'Livreur assigné avec succès.');
    }

    /**
     * Update package status.
     */
    public function updateStatus(Request $request, Package $package)
    {
        $request->validate([
            'status' => 'required|in:pending,active,in_transit,delivered,cancelled',
        ]);

        $package->update([
            'status' => $request->status,
        ]);

        // Update delivery date if status is delivered
        if ($request->status === 'delivered') {
            $package->update([
                'delivered_at' => now(),
            ]);
        }

        return redirect()->back()
                        ->with('success', 'Statut du colis mis à jour avec succès.');
    }

    /**
     * Export packages to CSV.
     */
    public function export()
    {
        $packages = Package::with(['sender', 'receiver', 'traveler'])->get();
        
        $filename = 'packages_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($packages) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Numéro de suivi', 'Titre', 'Expéditeur', 'Destinataire', 
                'Origine', 'Destination', 'Poids (kg)', 'Valeur (€)', 'Statut', 
                'Priorité', 'Date création', 'Date livraison prévue'
            ]);
            
            foreach ($packages as $package) {
                fputcsv($file, [
                    $package->package_id,
                    $package->tracking_number,
                    $package->title,
                    $package->sender->first_name . ' ' . $package->sender->last_name,
                    $package->receiver->first_name . ' ' . $package->receiver->last_name,
                    $package->origin_city,
                    $package->destination_city,
                    $package->weight_kg,
                    $package->value_euros,
                    $package->status,
                    $package->priority,
                    $package->created_at->format('d/m/Y H:i'),
                    $package->delivery_deadline ? $package->delivery_deadline->format('d/m/Y') : '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate unique tracking number.
     */
    private function generateTrackingNumber()
    {
        do {
            $number = 'ZEN' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Package::where('tracking_number', $number)->exists());
        
        return $number;
    }
}
