<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\Traveler;
use App\Services\DeliveryTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $query = Package::with(['sender', 'traveler']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('package_description', 'like', "%{$search}%")
                  ->orWhere('pickup_city', 'like', "%{$search}%")
                  ->orWhere('delivery_city', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
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
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:15',
            'recipient_email' => 'nullable|email|max:100',
            'recipient_address' => 'required|string',
            'package_description' => 'required|string',
            'category' => 'required|in:documents,electronics,clothing,food,medicine,fragile,other',
            'weight' => 'required|numeric|min:0.01|max:999.99',
            'declared_value' => 'nullable|numeric|min:0',
            'pickup_address' => 'required|string',
            'delivery_address' => 'required|string',
            'pickup_city' => 'required|string|max:50',
            'delivery_city' => 'required|string|max:50',
            'priority' => 'required|in:standard,express,urgent',
            'delivery_type' => 'nullable|in:urban,intercity,international',
            'requires_signature' => 'boolean',
            'fragile' => 'boolean',
            'special_instructions' => 'nullable|string',
        ]);

        // Determine delivery type automatically if not provided
        $deliveryType = $request->delivery_type;
        if (!$deliveryType) {
            $deliveryType = DeliveryTypeService::determineDeliveryType(
                $request->pickup_city,
                $request->delivery_city
            );
        }

        $package = Package::create([
            'sender_id' => $request->sender_id,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'recipient_email' => $request->recipient_email,
            'recipient_address' => $request->recipient_address,
            'package_description' => $request->package_description,
            'category' => $request->category,
            'weight' => $request->weight,
            'declared_value' => $request->declared_value ?? 0,
            'pickup_address' => $request->pickup_address,
            'delivery_address' => $request->delivery_address,
            'pickup_city' => $request->pickup_city,
            'delivery_city' => $request->delivery_city,
            'priority' => $request->priority,
            'delivery_type' => $deliveryType,
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
    public function show($packageId)
    {
        $package = Package::where('package_id', $packageId)->firstOrFail();
        $package->load(['sender', 'receiver', 'traveler', 'transactions', 'proposals', 'messages']);
        
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit($packageId)
    {
        $package = Package::where('package_id', $packageId)->firstOrFail();
        $users = User::where('is_active', true)->get();
        $travelers = Traveler::with('user')->whereHas('user', function($q) {
            $q->where('is_active', true);
        })->get();
        
        return view('admin.packages.edit', compact('package', 'users', 'travelers'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, $packageId)
    {
        $package = Package::where('package_id', $packageId)->firstOrFail();
        
        $request->validate([
            'sender_id' => 'required|exists:users,user_id',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:15',
            'recipient_email' => 'nullable|email|max:100',
            'recipient_address' => 'required|string',
            'package_description' => 'required|string',
            'category' => 'required|in:documents,electronics,clothing,food,medicine,fragile,other',
            'weight' => 'required|numeric|min:0.01|max:999.99',
            'dimensions_length' => 'nullable|numeric|min:0|max:999.99',
            'dimensions_width' => 'nullable|numeric|min:0|max:999.99',
            'dimensions_height' => 'nullable|numeric|min:0|max:999.99',
            'declared_value' => 'nullable|numeric|min:0',
            'pickup_address' => 'required|string',
            'delivery_address' => 'required|string',
            'pickup_city' => 'required|string|max:50',
            'delivery_city' => 'required|string|max:50',
            'priority' => 'required|in:standard,express,urgent',
            'status' => 'required|in:pending,accepted,picked_up,in_transit,arrived,out_for_delivery,delivered,cancelled,returned',
            'delivery_type' => 'nullable|in:urban,intercity,international',
            'requires_signature' => 'boolean',
            'fragile' => 'boolean',
            'special_instructions' => 'nullable|string',
        ]);

        // Determine delivery type automatically if not provided
        $deliveryType = $request->delivery_type;
        if (!$deliveryType) {
            $deliveryType = DeliveryTypeService::determineDeliveryType(
                $request->pickup_city,
                $request->delivery_city
            );
        }

        $package->update([
            'sender_id' => $request->sender_id,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'recipient_email' => $request->recipient_email,
            'recipient_address' => $request->recipient_address,
            'package_description' => $request->package_description,
            'category' => $request->category,
            'weight' => $request->weight,
            'dimensions_length' => $request->dimensions_length,
            'dimensions_width' => $request->dimensions_width,
            'dimensions_height' => $request->dimensions_height,
            'declared_value' => $request->declared_value ?? 0,
            'pickup_address' => $request->pickup_address,
            'delivery_address' => $request->delivery_address,
            'pickup_city' => $request->pickup_city,
            'delivery_city' => $request->delivery_city,
            'priority' => $request->priority,
            'status' => $request->status,
            'delivery_type' => $deliveryType,
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
    public function destroy($packageId)
    {
        $package = Package::where('package_id', $packageId)->firstOrFail();
        
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
    public function assignTraveler(Request $request, $packageId)
    {
        $package = Package::where('package_id', $packageId)->firstOrFail();
        
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
    public function updateStatus(Request $request, $packageId)
    {
        $package = Package::where('package_id', $packageId)->firstOrFail();
        
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
                'ID', 'Numéro de suivi', 'Description', 'Expéditeur', 'Destinataire', 
                'Ville départ', 'Ville arrivée', 'Poids (kg)', 'Valeur déclarée (XOF)', 'Statut', 
                'Priorité', 'Date création', 'Date livraison prévue'
            ]);
            
            foreach ($packages as $package) {
                fputcsv($file, [
                    $package->package_id,
                    $package->tracking_number,
                    $package->package_description,
                    $package->sender->first_name . ' ' . $package->sender->last_name,
                    $package->recipient_name,
                    $package->pickup_city,
                    $package->delivery_city,
                    $package->weight,
                    $package->declared_value,
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
