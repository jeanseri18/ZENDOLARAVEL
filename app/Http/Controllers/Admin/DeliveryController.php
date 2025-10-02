<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Package;
use App\Models\Traveler;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of deliveries.
     */
    public function index(Request $request)
    {
        $query = Delivery::with(['package.sender', 'package.receiver', 'traveler.user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pickup_location', 'like', "%{$search}%")
                  ->orWhere('delivery_location', 'like', "%{$search}%")
                  ->orWhereHas('package', function($packageQuery) use ($search) {
                      $packageQuery->where('package_number', 'like', "%{$search}%")
                                  ->orWhere('description', 'like', "%{$search}%");
                  })
                  ->orWhereHas('traveler.user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by delivery type
        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $deliveries = $query->latest()->paginate(15);

        return view('admin.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new delivery.
     */
    public function create()
    {
        $packages = Package::whereDoesntHave('delivery')
                          ->with(['sender', 'receiver'])
                          ->where('status', 'confirmed')
                          ->get();
        $travelers = Traveler::with('user')
                           ->where('status', 'active')
                           ->whereHas('user', function($query) {
                               $query->where('is_active', true)
                                     ->where('is_verified', true);
                           })
                           ->get();

        return view('admin.deliveries.create', compact('packages', 'travelers'));
    }

    /**
     * Store a newly created delivery in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,package_id|unique:deliveries,package_id',
            'traveler_id' => 'nullable|exists:travelers,traveler_id',
            'delivery_type' => 'nullable|in:urban,intercity,international',
            'pickup_location' => 'required|string|max:255',
            'delivery_location' => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'customs_declaration' => 'nullable|string',
            'flight_ticket_path' => 'nullable|string|max:255',
            'commission_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:to_pickup,in_transit,delivered,delayed,canceled'
        ]);

        // Get package to determine delivery type automatically
        $package = Package::find($request->package_id);
        
        // Use package delivery type or determine from locations
        $deliveryType = $request->delivery_type;
        if (!$deliveryType) {
            if ($package && $package->delivery_type) {
                $deliveryType = $package->delivery_type;
            } else {
                // Determine from pickup and delivery locations
                $deliveryType = Traveler::determineDeliveryType(
                    $request->pickup_location,
                    $request->delivery_location
                );
            }
        }
        
        $request->merge(['delivery_type' => $deliveryType]);

        // Set default commission fees based on delivery type
        if (!$request->filled('commission_fee')) {
            $commissionFees = [
                'urban' => 0,
                'intercity' => 1000,
                'international' => 2000
            ];
            $request->merge(['commission_fee' => $commissionFees[$deliveryType]]);
        }

        $delivery = Delivery::create($request->all());

        return redirect()->route('admin.deliveries.index')
                        ->with('success', 'Livraison créée avec succès.');
    }

    /**
     * Display the specified delivery.
     */
    public function show($deliveryId)
    {
        $delivery = Delivery::where('delivery_id', $deliveryId)->firstOrFail();
        
        $delivery->load(['package.sender', 'package.receiver', 'traveler.user']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified delivery.
     */
    public function edit($deliveryId)
    {
        $delivery = Delivery::where('delivery_id', $deliveryId)->firstOrFail();
        
        $packages = Package::with(['sender', 'receiver'])->get();
        $travelers = Traveler::with('user')
                           ->where('status', 'active')
                           ->whereHas('user', function($query) {
                               $query->where('is_active', true)
                                     ->where('is_verified', true);
                           })
                           ->get();

        return view('admin.deliveries.edit', compact('delivery', 'packages', 'travelers'));
    }

    /**
     * Update the specified delivery in storage.
     */
    public function update(Request $request, $deliveryId)
    {
        $delivery = Delivery::where('delivery_id', $deliveryId)->firstOrFail();
        
        $request->validate([
            'package_id' => 'required|exists:packages,package_id|unique:deliveries,package_id,' . $delivery->delivery_id . ',delivery_id',
            'traveler_id' => 'nullable|exists:travelers,traveler_id',
            'delivery_type' => 'required|in:urban,intercity,international',
            'pickup_location' => 'required|string|max:255',
            'delivery_location' => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'actual_delivery_time' => 'nullable|string|max:10',
            'customs_declaration' => 'nullable|string',
            'flight_ticket_path' => 'nullable|string|max:255',
            'commission_fee' => 'nullable|numeric|min:0',
            'gps_tracking_data' => 'nullable|json',
            'status' => 'required|in:to_pickup,in_transit,delivered,delayed,canceled'
        ]);

        $delivery->update($request->all());

        return redirect()->route('admin.deliveries.index')
                        ->with('success', 'Livraison mise à jour avec succès.');
    }

    /**
     * Update delivery status.
     */
    public function updateStatus(Request $request, $deliveryId)
    {
        $delivery = Delivery::where('delivery_id', $deliveryId)->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:to_pickup,in_transit,delivered,delayed,canceled'
        ]);

        $delivery->update([
            'status' => $request->status,
            'end_time' => $request->status === 'delivered' ? now() : $delivery->end_time
        ]);

        return redirect()->back()
                        ->with('success', 'Statut de la livraison mis à jour.');
    }

    /**
     * Remove the specified delivery from storage.
     */
    public function destroy($deliveryId)
    {
        $delivery = Delivery::where('delivery_id', $deliveryId)->firstOrFail();
        
        $delivery->delete();

        return redirect()->route('admin.deliveries.index')
                        ->with('success', 'Livraison supprimée avec succès.');
    }

    /**
     * Export deliveries data.
     */
    public function export(Request $request)
    {
        $query = Delivery::with(['package.sender', 'package.receiver', 'traveler.user']);

        // Apply same filters as index
        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $deliveries = $query->get();

        $filename = 'livraisons_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($deliveries) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID Livraison',
                'Numéro Colis',
                'Type de Livraison',
                'Statut',
                'Expéditeur',
                'Destinataire',
                'Livreur',
                'Lieu de Récupération',
                'Lieu de Livraison',
                'Frais de Commission',
                'Date de Création',
                'Date de Début',
                'Date de Fin'
            ]);

            foreach ($deliveries as $delivery) {
                fputcsv($file, [
                    $delivery->delivery_id,
                    $delivery->package->package_number ?? '',
                    $delivery->delivery_type_label,
                    $delivery->status_label,
                    $delivery->package->sender->name ?? '',
                    $delivery->package->receiver->name ?? '',
                    ($delivery->traveler ? $delivery->traveler->user->first_name . ' ' . $delivery->traveler->user->last_name : 'Non assigné'),
                    $delivery->pickup_location,
                    $delivery->delivery_location,
                    $delivery->commission_fee,
                    $delivery->created_at->format('Y-m-d H:i:s'),
                    $delivery->start_time ? $delivery->start_time->format('Y-m-d H:i:s') : '',
                    $delivery->end_time ? $delivery->end_time->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}