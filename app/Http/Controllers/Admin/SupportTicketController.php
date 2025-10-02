<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of support tickets.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'package', 'transaction']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by assigned status
        if ($request->filled('assigned')) {
            if ($request->assigned === 'yes') {
                $query->whereNotNull('assigned_to');
            } else {
                $query->whereNull('assigned_to');
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->latest()->paginate(15);
        $admins = User::where('role', 'admin')->where('is_active', true)->get();

        return view('admin.tickets.index', compact('tickets', 'admins'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $users = User::where('is_active', true)->get();
        $packages = Package::with(['sender', 'receiver'])->get();
        $transactions = Transaction::with(['user', 'package'])->get();
        $admins = User::where('role', 'admin')->where('is_active', true)->get();
        
        return view('admin.tickets.create', compact('users', 'packages', 'transactions', 'admins'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
            'package_id' => 'nullable|exists:packages,package_id',
            'transaction_id' => 'nullable|exists:transactions,transaction_id',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => $request->user_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'package_id' => $request->package_id,
            'transaction_id' => $request->transaction_id,
            'status' => 'open',
            'ticket_number' => SupportTicket::generateTicketNumber(),
        ]);

        return redirect()->route('admin.support-tickets.index')
                        ->with('success', 'Ticket créé avec succès.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'package', 'transaction', 'assignedTo']);
        
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(SupportTicket $ticket)
    {
        $users = User::where('is_active', true)->get();
        $packages = Package::with(['sender', 'receiver'])->get();
        $transactions = Transaction::with(['user', 'package'])->get();
        $admins = User::where('role', 'admin')->where('is_active', true)->get();
        
        return view('admin.tickets.edit', compact('ticket', 'users', 'packages', 'transactions', 'admins'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'package_id' => 'nullable|exists:packages,package_id',
            'transaction_id' => 'nullable|exists:transactions,transaction_id',
            'assigned_to' => 'nullable|exists:users,user_id',
            'resolution' => 'nullable|string',
        ]);

        $ticket->update([
            'user_id' => $request->user_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'package_id' => $request->package_id,
            'transaction_id' => $request->transaction_id,
            'assigned_to' => $request->assigned_to,
            'resolution' => $request->resolution,
        ]);

        // Update resolved/closed dates
        if ($request->status === 'resolved' && !$ticket->resolved_at) {
            $ticket->update(['resolved_at' => now()]);
        }
        if ($request->status === 'closed' && !$ticket->closed_at) {
            $ticket->update(['closed_at' => now()]);
        }

        return redirect()->route('admin.support-tickets.index')
                        ->with('success', 'Ticket mis à jour avec succès.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(SupportTicket $ticket)
    {
        // Prevent deletion of open or in-progress tickets
        if (in_array($ticket->status, ['open', 'in_progress'])) {
            return redirect()->route('admin.tickets.index')
                           ->with('error', 'Impossible de supprimer un ticket ouvert ou en cours.');
        }

        $ticket->delete();

        return redirect()->route('admin.support-tickets.index')
                        ->with('success', 'Ticket supprimé avec succès.');
    }

    /**
     * Assign ticket to admin.
     */
    public function assign(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,user_id',
        ]);

        $ticket->assign($request->assigned_to);

        return redirect()->back()
                        ->with('success', 'Ticket assigné avec succès.');
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution' => 'nullable|string',
        ]);

        if ($request->status === 'resolved') {
            $ticket->resolve($request->resolution);
        } elseif ($request->status === 'closed') {
            $ticket->close();
        } else {
            $ticket->update(['status' => $request->status]);
        }

        return redirect()->back()
                        ->with('success', 'Statut du ticket mis à jour avec succès.');
    }

    /**
     * Reopen a closed ticket.
     */
    public function reopen(SupportTicket $ticket)
    {
        $ticket->reopen();

        return redirect()->back()
                        ->with('success', 'Ticket rouvert avec succès.');
    }

    /**
     * Export tickets to CSV.
     */
    public function export()
    {
        $tickets = SupportTicket::with(['user', 'package', 'transaction', 'assignedTo'])->get();
        
        $filename = 'tickets_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Numéro ticket', 'Utilisateur', 'Sujet', 'Catégorie', 
                'Priorité', 'Statut', 'Assigné à', 'Date création', 'Date résolution'
            ]);
            
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_id,
                    $ticket->ticket_number,
                    $ticket->user->first_name . ' ' . $ticket->user->last_name,
                    $ticket->subject,
                    $ticket->category,
                    $ticket->priority,
                    $ticket->status,
                    $ticket->assignedTo ? $ticket->assignedTo->first_name . ' ' . $ticket->assignedTo->last_name : 'Non assigné',
                    $ticket->created_at->format('d/m/Y H:i'),
                    $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y H:i') : '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get ticket statistics.
     */
    public function stats()
    {
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::open()->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::resolved()->count(),
            'closed' => SupportTicket::closed()->count(),
            'high_priority' => SupportTicket::priority('high')->count(),
            'urgent_priority' => SupportTicket::priority('urgent')->count(),
            'unassigned' => SupportTicket::unassigned()->count(),
        ];

        return response()->json($stats);
    }
}