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
            'users' => User::count(),
            'packages' => Package::count(),
            'travelers' => Traveler::count(),
            'transactions' => Transaction::count(),
            'tickets' => SupportTicket::where('status', 'open')->count(),
            'revenue' => Transaction::where('transaction_status', 'completed')->sum('amount'),
        ];

        // Données pour les graphiques
        $chartData = $this->getChartData();

        $recentUsers = User::latest()->take(5)->get();
        $recentPackages = Package::with('sender')->latest()->take(5)->get();
        $recentTickets = SupportTicket::with('user')->where('status', 'open')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'recentUsers', 'recentPackages', 'recentTickets'));
    }

    /**
     * Get chart data for dashboard.
     */
    private function getChartData()
    {
        // Données mensuelles pour les 6 derniers mois
        $monthlyData = [];
        $months = [];
        $usersData = [];
        $packagesData = [];
        $revenueData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $usersData[] = User::whereMonth('created_at', $date->month)
                              ->whereYear('created_at', $date->year)
                              ->count();
                              
            $packagesData[] = Package::whereMonth('created_at', $date->month)
                                   ->whereYear('created_at', $date->year)
                                   ->count();
                                   
            $revenueData[] = Transaction::whereMonth('created_at', $date->month)
                                      ->whereYear('created_at', $date->year)
                                      ->where('transaction_status', 'completed')
                                      ->sum('amount');
        }

        // Statuts des colis pour graphique en secteurs
        $packageStatuses = [
            'pending' => Package::where('status', 'pending')->count(),
            'in_transit' => Package::where('status', 'in_transit')->count(),
            'delivered' => Package::where('status', 'delivered')->count(),
            'cancelled' => Package::where('status', 'cancelled')->count(),
        ];

        // Évolution des revenus par semaine (4 dernières semaines)
        $weeklyRevenue = [];
        $weeks = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();
            $weeks[] = 'Sem ' . ($i + 1);
            $weeklyRevenue[] = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                        ->where('transaction_status', 'completed')
                                        ->sum('amount');
        }

        return [
            'monthly' => [
                'labels' => $months,
                'users' => $usersData,
                'packages' => $packagesData,
                'revenue' => $revenueData,
            ],
            'packageStatuses' => [
                'labels' => ['En attente', 'En transit', 'Livré', 'Annulé'],
                'data' => array_values($packageStatuses),
                'colors' => ['#FCD34D', '#60A5FA', '#34D399', '#F87171'],
            ],
            'weeklyRevenue' => [
                'labels' => $weeks,
                'data' => $weeklyRevenue,
            ],
        ];
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
