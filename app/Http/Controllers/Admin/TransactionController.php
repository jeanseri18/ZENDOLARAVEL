<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Package;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['sender', 'traveler', 'package', 'promoCode']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('traveler', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('package', function($packageQuery) use ($search) {
                      $packageQuery->where('title', 'like', "%{$search}%")
                                  ->orWhere('tracking_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15);

        // Calculate summary statistics
        $stats = [
            'total_amount' => $query->sum('amount'),
            'total_transactions' => $query->count(),
            'successful_transactions' => $query->where('transaction_status', 'completed')->count(),
            'pending_transactions' => $query->where('transaction_status', 'pending')->count(),
            'failed_transactions' => $query->where('transaction_status', 'failed')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        $users = User::where('is_active', true)->get();
        $packages = Package::with(['sender', 'receiver'])->get();
        $promoCodes = PromoCode::active()->get();
        
        return view('admin.transactions.create', compact('users', 'packages', 'promoCodes'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,user_id',
            'package_id' => 'required|exists:packages,package_id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:payment,refund,fee,commission',
            'payment_method' => 'required|string|max:100',
            'promo_code_id' => 'nullable|exists:promo_codes,promo_code_id',
            'description' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::create([
            'sender_id' => $request->sender_id,
            'package_id' => $request->package_id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,
            'payment_method' => $request->payment_method,
            'promo_code_id' => $request->promo_code_id,
            'description' => $request->description,
            'status' => 'pending',
            'payment_reference' => $this->generatePaymentReference(),
        ]);

        return redirect()->route('admin.transactions.index')
                        ->with('success', 'Transaction créée avec succès.');
    }

    /**
     * Display the specified transaction.
     */
    public function show($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        $transaction->load(['user', 'package.sender', 'package.receiver', 'promoCode']);
        
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        $users = User::where('is_active', true)->get();
        $packages = Package::with(['sender', 'receiver'])->get();
        $promoCodes = PromoCode::active()->get();
        
        return view('admin.transactions.edit', compact('transaction', 'users', 'packages', 'promoCodes'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, $transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        
        $request->validate([
            'sender_id' => 'required|exists:users,user_id',
            'package_id' => 'required|exists:packages,package_id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:payment,refund,fee,commission',
            'payment_method' => 'required|string|max:100',
            'status' => 'required|in:pending,processing,completed,failed,cancelled,refunded',
            'promo_code_id' => 'nullable|exists:promo_codes,promo_code_id',
            'description' => 'nullable|string|max:500',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $transaction->update([
            'sender_id' => $request->sender_id,
            'package_id' => $request->package_id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'promo_code_id' => $request->promo_code_id,
            'description' => $request->description,
            'payment_reference' => $request->payment_reference,
        ]);

        // Update processed date if status changed to completed
        if ($request->status === 'completed' && !$transaction->processed_at) {
            $transaction->update(['processed_at' => now()]);
        }

        return redirect()->route('admin.transactions.index')
                        ->with('success', 'Transaction mise à jour avec succès.');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        
        // Prevent deletion of completed transactions
        if ($transaction->status === 'completed') {
            return redirect()->route('admin.transactions.index')
                           ->with('error', 'Impossible de supprimer une transaction complétée.');
        }

        $transaction->delete();

        return redirect()->route('admin.transactions.index')
                        ->with('success', 'Transaction supprimée avec succès.');
    }

    /**
     * Update transaction status.
     */
    public function updateStatus(Request $request, $transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:pending,processing,completed,failed,cancelled,refunded',
            'notes' => 'nullable|string|max:500',
        ]);

        $transaction->update([
            'status' => $request->status,
        ]);

        // Update processed date if status is completed
        if ($request->status === 'completed') {
            $transaction->update(['processed_at' => now()]);
        }

        // Add notes to description if provided
        if ($request->filled('notes')) {
            $currentDescription = $transaction->description ?? '';
            $newDescription = $currentDescription . "\n[" . now()->format('d/m/Y H:i') . "] " . $request->notes;
            $transaction->update(['description' => trim($newDescription)]);
        }

        return redirect()->back()
                        ->with('success', 'Statut de la transaction mis à jour avec succès.');
    }

    /**
     * Process refund for transaction.
     */
    public function refund(Request $request, $transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $transaction->amount,
            'refund_reason' => 'required|string|max:500',
        ]);

        // Create refund transaction
        $refundTransaction = Transaction::create([
            'sender_id' => $transaction->sender_id,
            'package_id' => $transaction->package_id,
            'amount' => $request->refund_amount,
            'transaction_type' => 'refund',
            'payment_method' => $transaction->payment_method,
            'status' => 'completed',
            'description' => 'Remboursement pour transaction #' . $transaction->transaction_id . ': ' . $request->refund_reason,
            'payment_reference' => 'REF-' . $this->generatePaymentReference(),
            'processed_at' => now(),
        ]);

        // Update original transaction status
        $transaction->update([
            'status' => 'refunded',
            'description' => ($transaction->description ?? '') . "\n[" . now()->format('d/m/Y H:i') . "] Remboursé: " . $request->refund_amount . " XOF - " . $request->refund_reason,
        ]);

        return redirect()->back()
                        ->with('success', 'Remboursement traité avec succès.');
    }

    /**
     * Export transactions to CSV.
     */
    public function export()
    {
        $transactions = Transaction::with(['user', 'package', 'promoCode'])->get();
        
        $filename = 'transactions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Utilisateur', 'Colis', 'Montant (XOF)', 'Type', 'Méthode paiement', 
                'Statut', 'Code promo', 'Référence', 'Date création', 'Date traitement'
            ]);
            
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_id,
                    $transaction->user->first_name . ' ' . $transaction->user->last_name,
                    $transaction->package ? $transaction->package->package_description : 'N/A',
                    number_format($transaction->amount, 2),
                    $transaction->transaction_type,
                    $transaction->payment_method,
                    $transaction->status,
                    $transaction->promoCode ? $transaction->promoCode->code : 'N/A',
                    $transaction->payment_reference,
                    $transaction->created_at->format('d/m/Y H:i'),
                    $transaction->processed_at ? $transaction->processed_at->format('d/m/Y H:i') : 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get transaction statistics.
     */
    public function stats()
    {
        $stats = [
            'total_transactions' => Transaction::count(),
            'total_amount' => Transaction::sum('amount'),
            'completed_transactions' => Transaction::where('transaction_status', 'completed')->count(),
            'completed_amount' => Transaction::where('transaction_status', 'completed')->sum('amount'),
            'pending_transactions' => Transaction::where('transaction_status', 'pending')->count(),
            'pending_amount' => Transaction::where('transaction_status', 'pending')->sum('amount'),
            'failed_transactions' => Transaction::where('transaction_status', 'failed')->count(),
            'refunded_transactions' => Transaction::where('transaction_status', 'refunded')->count(),
            'refunded_amount' => Transaction::where('transaction_type', 'refund')->sum('amount'),
            'avg_transaction_amount' => Transaction::where('transaction_status', 'completed')->avg('amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Generate unique payment reference.
     */
    private function generatePaymentReference()
    {
        do {
            $reference = 'PAY' . date('Ymd') . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Transaction::where('payment_reference', $reference)->exists());
        
        return $reference;
    }
}
