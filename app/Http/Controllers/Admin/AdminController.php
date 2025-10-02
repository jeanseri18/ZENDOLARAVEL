<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Package;
use App\Models\Traveler;
use App\Models\Transaction;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLoginForm', 'login']);
        // Add admin role check middleware here if needed
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_packages' => Package::count(),
            'total_travelers' => Traveler::count(),
            'total_transactions' => Transaction::count(),
            'pending_tickets' => SupportTicket::where('status', 'open')->count(),
            'active_packages' => Package::where('status', 'active')->count(),
            'completed_deliveries' => Package::where('status', 'delivered')->count(),
            'total_revenue' => Transaction::where('transaction_status', 'completed')->sum('amount'),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_packages = Package::with('sender')->latest()->take(5)->get();
        $recent_tickets = SupportTicket::with('user')->where('status', 'open')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_packages', 'recent_tickets'));
    }

    /**
     * Show admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is admin or has both roles (you can modify this logic based on your role system)
            if (!in_array($user->role, ['admin', 'both'])) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Accès non autorisé.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    /**
     * Show reports page.
     */
    public function reports()
    {
        $monthly_stats = [];
        $current_month = now()->startOfMonth();
        
        for ($i = 0; $i < 12; $i++) {
            $month = $current_month->copy()->subMonths($i);
            $monthly_stats[] = [
                'month' => $month->format('M Y'),
                'users' => User::whereMonth('created_at', $month->month)
                              ->whereYear('created_at', $month->year)
                              ->count(),
                'packages' => Package::whereMonth('created_at', $month->month)
                                   ->whereYear('created_at', $month->year)
                                   ->count(),
                'revenue' => Transaction::whereMonth('created_at', $month->month)
                                      ->whereYear('created_at', $month->year)
                                      ->where('transaction_status', 'completed')
                                      ->sum('amount'),
            ];
        }

        $monthly_stats = array_reverse($monthly_stats);

        return view('admin.reports', compact('monthly_stats'));
    }

    /**
     * Show settings page.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'maintenance_mode' => 'boolean',
        ]);

        // Here you would typically save settings to a settings table or config
        // For now, we'll just return success
        
        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }
}
