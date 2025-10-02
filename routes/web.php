<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route pour afficher la page de connexion
Route::get('/login', function () {
    return view('login');
})->name('login');

// Route pour traiter le formulaire de connexion
Route::post('/login', function () {
    // Logique de connexion à implémenter
    return redirect()->back()->with('message', 'Connexion en cours de traitement...');
})->name('login.submit');

Route::get('/expediteur', function () {
    return view('expediteur');
})->name('expediteur');

Route::get('/coursier', function () {
    return view('coursier');
})->name('coursier');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function () {
    // Logique d'envoi de message ici
    return redirect()->back()->with('success', 'Message envoyé avec succès!');
})->name('contact.send');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::get('/login', [App\Http\Controllers\Admin\AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('reports');
        Route::get('/settings', [App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [App\Http\Controllers\Admin\AdminController::class, 'updateSettings'])->name('settings.update');
        
        // Users Management
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::patch('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::patch('users/{user}/toggle-verification', [App\Http\Controllers\Admin\UserController::class, 'toggleVerification'])->name('users.toggle-verification');
        Route::get('users/export/csv', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
        
        // Packages Management
        Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
        Route::patch('packages/{package}/assign-traveler', [App\Http\Controllers\Admin\PackageController::class, 'assignTraveler'])->name('packages.assign-traveler');
        Route::patch('packages/{package}/update-status', [App\Http\Controllers\Admin\PackageController::class, 'updateStatus'])->name('packages.update-status');
        Route::get('packages/export/csv', [App\Http\Controllers\Admin\PackageController::class, 'export'])->name('packages.export');
        
        // Support Tickets Management
        Route::resource('support-tickets', App\Http\Controllers\Admin\SupportTicketController::class);
        Route::patch('support-tickets/{support_ticket}/assign', [App\Http\Controllers\Admin\SupportTicketController::class, 'assign'])->name('support-tickets.assign');
        Route::patch('support-tickets/{support_ticket}/update-status', [App\Http\Controllers\Admin\SupportTicketController::class, 'updateStatus'])->name('support-tickets.update-status');
        Route::patch('support-tickets/{support_ticket}/reopen', [App\Http\Controllers\Admin\SupportTicketController::class, 'reopen'])->name('support-tickets.reopen');
        Route::get('support-tickets/export/csv', [App\Http\Controllers\Admin\SupportTicketController::class, 'export'])->name('support-tickets.export');
        Route::get('support-tickets/stats/json', [App\Http\Controllers\Admin\SupportTicketController::class, 'stats'])->name('support-tickets.stats');
        
        // Travelers Management
        Route::resource('travelers', App\Http\Controllers\Admin\TravelerController::class);
        Route::patch('travelers/{traveler}/toggle-verification', [App\Http\Controllers\Admin\TravelerController::class, 'toggleVerification'])->name('travelers.toggle-verification');
        Route::patch('travelers/{traveler}/toggle-availability', [App\Http\Controllers\Admin\TravelerController::class, 'toggleAvailability'])->name('travelers.toggle-availability');
        Route::get('travelers/export/csv', [App\Http\Controllers\Admin\TravelerController::class, 'export'])->name('travelers.export');
        Route::get('travelers/stats/json', [App\Http\Controllers\Admin\TravelerController::class, 'stats'])->name('travelers.stats');
        
        // Transactions Management
        Route::resource('transactions', App\Http\Controllers\Admin\TransactionController::class);
        Route::patch('transactions/{transaction}/update-status', [App\Http\Controllers\Admin\TransactionController::class, 'updateStatus'])->name('transactions.update-status');
        Route::post('transactions/{transaction}/refund', [App\Http\Controllers\Admin\TransactionController::class, 'refund'])->name('transactions.refund');
        Route::get('transactions/export/csv', [App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('transactions.export');
        Route::get('transactions/stats/json', [App\Http\Controllers\Admin\TransactionController::class, 'stats'])->name('transactions.stats');
        
        // Deliveries Management
        Route::resource('deliveries', App\Http\Controllers\Admin\DeliveryController::class);
        Route::patch('deliveries/{delivery}/update-status', [App\Http\Controllers\Admin\DeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
        Route::get('deliveries/export/csv', [App\Http\Controllers\Admin\DeliveryController::class, 'export'])->name('deliveries.export');
        
        // Identity Documents Management
        Route::resource('identity-documents', App\Http\Controllers\Admin\IdentityDocumentController::class);
        Route::patch('identity-documents/{identity_document}/verify', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'verify'])->name('identity-documents.verify');
        Route::patch('identity-documents/{identity_document}/set-primary', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'setPrimary'])->name('identity-documents.set-primary');
        Route::get('identity-documents/{identity_document}/download-photo/{type}', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'downloadPhoto'])->name('identity-documents.download-photo');
        Route::get('identity-documents/stats/json', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'getStats'])->name('identity-documents.stats');
    });
});
