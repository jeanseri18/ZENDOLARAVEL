<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdentityDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IdentityDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of identity documents.
     */
    public function index(Request $request)
    {
        $query = IdentityDocument::with(['user', 'verifiedBy']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Filter by expiry status
        if ($request->filled('expiry_status')) {
            if ($request->expiry_status === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($request->expiry_status === 'expiring_soon') {
                $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
            }
        }

        $documents = $query->latest('uploaded_at')->paginate(15);

        return view('admin.identity-documents.index', compact('documents'));
    }

    /**
     * Display the specified identity document.
     */
    public function show($id)
    {
        $document = IdentityDocument::with(['user', 'verifiedBy'])->findOrFail($id);
        
        return view('admin.identity-documents.show', compact('document'));
    }

    /**
     * Verify an identity document.
     */
    public function verify(Request $request, $id)
    {
        $document = IdentityDocument::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:verify,reject',
            'rejection_reason' => 'required_if:action,reject|string|max:500'
        ]);

        if ($request->action === 'verify') {
            $document->update([
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'rejection_reason' => null
            ]);
            
            return redirect()->back()->with('success', 'Document vérifié avec succès.');
        } else {
            $document->update([
                'verification_status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason
            ]);
            
            return redirect()->back()->with('success', 'Document rejeté.');
        }
    }

    /**
     * Set document as primary.
     */
    public function setPrimary($id)
    {
        $document = IdentityDocument::findOrFail($id);
        
        // Remove primary status from other documents of the same user
        IdentityDocument::where('user_id', $document->user_id)
                       ->where('document_id', '!=', $id)
                       ->update(['is_primary' => false]);
        
        // Set this document as primary
        $document->update(['is_primary' => true]);
        
        return redirect()->back()->with('success', 'Document défini comme principal.');
    }

    /**
     * Download document photo.
     */
    public function downloadPhoto($id, $type = 'front')
    {
        $document = IdentityDocument::findOrFail($id);
        
        $photoPath = $type === 'back' ? $document->document_photo_back : $document->document_photo;
        
        if (!$photoPath || !Storage::exists($photoPath)) {
            abort(404, 'Photo non trouvée.');
        }
        
        return Storage::download($photoPath);
    }

    /**
     * Remove the specified identity document.
     */
    public function destroy($id)
    {
        $document = IdentityDocument::findOrFail($id);
        
        // Delete associated files
        if ($document->document_photo && Storage::exists($document->document_photo)) {
            Storage::delete($document->document_photo);
        }
        
        if ($document->document_photo_back && Storage::exists($document->document_photo_back)) {
            Storage::delete($document->document_photo_back);
        }
        
        $document->delete();
        
        return redirect()->route('admin.identity-documents.index')
                        ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Get documents statistics for dashboard.
     */
    public function getStats()
    {
        return [
            'total' => IdentityDocument::count(),
            'pending' => IdentityDocument::where('verification_status', 'pending')->count(),
            'verified' => IdentityDocument::where('verification_status', 'verified')->count(),
            'rejected' => IdentityDocument::where('verification_status', 'rejected')->count(),
            'expired' => IdentityDocument::where('expiry_date', '<', now())->count(),
        ];
    }
}
