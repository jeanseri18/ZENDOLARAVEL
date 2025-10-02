<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with('profile');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by verification status
        if ($request->filled('verified')) {
            if ($request->verified === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->verified === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'nullable|string|email|max:100|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:expeditor,traveler,both',
            'city' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'city' => $request->city,
            'country' => $request->country,
            'bio' => $request->bio,
            'is_active' => true,
            'is_verified' => false,
        ]);

        // Create profile
        Profile::create([
            'user_id' => $user->user_id,
            'total_packages_sent' => 0,
            'total_packages_delivered' => 0,
            'success_rate' => 0,
            'cancellation_rate' => 0,
            'reliability_percentage' => 100,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified user.
     */
    public function show($userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();
        $user->load(['profile', 'packages', 'transactions', 'supportTickets']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();
        
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => ['nullable', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'phone_number' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'role' => 'required|in:expeditor,traveler,both',
            'city' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'city' => $request->city,
            'country' => $request->country,
            'bio' => $request->bio,
            'is_active' => $request->boolean('is_active'),
            'is_verified' => $request->boolean('is_verified'),
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();
        
        // Prevent deletion of admin users
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                           ->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus($userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();
        
        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        
        return redirect()->back()
                        ->with('success', "Utilisateur {$status} avec succès.");
    }

    /**
     * Toggle user verification status.
     */
    public function toggleVerification($userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();
        
        $user->update([
            'is_verified' => !$user->is_verified,
        ]);

        $status = $user->is_verified ? 'vérifié' : 'non vérifié';
        
        return redirect()->back()
                        ->with('success', "Utilisateur marqué comme {$status}.");
    }

    /**
     * Export users to CSV.
     */
    public function export()
    {
        $users = User::with('profile')->get();
        
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Prénom', 'Nom', 'Email', 'Téléphone', 'Rôle', 
                'Ville', 'Pays', 'Actif', 'Vérifié', 'Date création'
            ]);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->user_id,
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                    $user->phone_number,
                    $user->role,
                    $user->city,
                    $user->country,
                    $user->is_active ? 'Oui' : 'Non',
                    $user->is_verified ? 'Oui' : 'Non',
                    $user->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
