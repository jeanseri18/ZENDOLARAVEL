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
        Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::patch('users/{user}/toggle-verification', [App\Http\Controllers\Admin\UserController::class, 'toggleVerification'])->name('users.toggle-verification');
        Route::get('users/export/csv', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
        
        // Packages Management
        Route::get('packages', [App\Http\Controllers\Admin\PackageController::class, 'index'])->name('packages.index');
        Route::get('packages/create', [App\Http\Controllers\Admin\PackageController::class, 'create'])->name('packages.create');
        Route::post('packages', [App\Http\Controllers\Admin\PackageController::class, 'store'])->name('packages.store');
        Route::get('packages/{package}', [App\Http\Controllers\Admin\PackageController::class, 'show'])->name('packages.show');
        Route::get('packages/{package}/edit', [App\Http\Controllers\Admin\PackageController::class, 'edit'])->name('packages.edit');
        Route::put('packages/{package}', [App\Http\Controllers\Admin\PackageController::class, 'update'])->name('packages.update');
        Route::delete('packages/{package}', [App\Http\Controllers\Admin\PackageController::class, 'destroy'])->name('packages.destroy');
        Route::patch('packages/{package}/assign-traveler', [App\Http\Controllers\Admin\PackageController::class, 'assignTraveler'])->name('packages.assign-traveler');
        Route::patch('packages/{package}/update-status', [App\Http\Controllers\Admin\PackageController::class, 'updateStatus'])->name('packages.update-status');
        Route::get('packages/export/csv', [App\Http\Controllers\Admin\PackageController::class, 'export'])->name('packages.export');
        
        // Support Tickets Management
        Route::get('support-tickets', [App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('support-tickets.index');
        Route::get('support-tickets/create', [App\Http\Controllers\Admin\SupportTicketController::class, 'create'])->name('support-tickets.create');
        Route::post('support-tickets', [App\Http\Controllers\Admin\SupportTicketController::class, 'store'])->name('support-tickets.store');
        Route::get('support-ticketsshow/{support_ticket}', [App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('support-tickets.show');
        Route::get('support-ticketsedit/{support_ticket}/edit', [App\Http\Controllers\Admin\SupportTicketController::class, 'edit'])->name('support-tickets.edit');
        Route::put('support-tickets/{support_ticket}', [App\Http\Controllers\Admin\SupportTicketController::class, 'update'])->name('support-tickets.update');
        Route::delete('support-tickets/{support_ticket}', [App\Http\Controllers\Admin\SupportTicketController::class, 'destroy'])->name('support-tickets.destroy');
        Route::patch('support-tickets/{support_ticket}/assign', [App\Http\Controllers\Admin\SupportTicketController::class, 'assign'])->name('support-tickets.assign');
        Route::patch('support-tickets/{support_ticket}/update-status', [App\Http\Controllers\Admin\SupportTicketController::class, 'updateStatus'])->name('support-tickets.update-status');
        Route::patch('support-tickets/{support_ticket}/reopen', [App\Http\Controllers\Admin\SupportTicketController::class, 'reopen'])->name('support-tickets.reopen');
        Route::post('support-tickets/{support_ticket}/reply', [App\Http\Controllers\Admin\SupportTicketController::class, 'addReply'])->name('support-tickets.add-reply');
        Route::post('support-tickets/{support_ticket}/user-message', [App\Http\Controllers\Admin\SupportTicketController::class, 'addUserMessage'])->name('support-tickets.add-user-message');
        Route::get('support-tickets/export/csv', [App\Http\Controllers\Admin\SupportTicketController::class, 'export'])->name('support-tickets.export');
        Route::get('support-tickets/stats/json', [App\Http\Controllers\Admin\SupportTicketController::class, 'stats'])->name('support-tickets.stats');
        
        // Travelers Management
        Route::get('travelers', [App\Http\Controllers\Admin\TravelerController::class, 'index'])->name('travelers.index');
        Route::get('travelers/create', [App\Http\Controllers\Admin\TravelerController::class, 'create'])->name('travelers.create');
        Route::post('travelers', [App\Http\Controllers\Admin\TravelerController::class, 'store'])->name('travelers.store');
        Route::get('travelers/{traveler}', [App\Http\Controllers\Admin\TravelerController::class, 'show'])->name('travelers.show');
        Route::get('travelers/{traveler}/edit', [App\Http\Controllers\Admin\TravelerController::class, 'edit'])->name('travelers.edit');
        Route::put('travelers/{traveler}', [App\Http\Controllers\Admin\TravelerController::class, 'update'])->name('travelers.update');
        Route::delete('travelers/{traveler}', [App\Http\Controllers\Admin\TravelerController::class, 'destroy'])->name('travelers.destroy');
        Route::patch('travelers/{traveler}/toggle-verification', [App\Http\Controllers\Admin\TravelerController::class, 'toggleVerification'])->name('travelers.toggle-verification');
        Route::patch('travelers/{traveler}/toggle-availability', [App\Http\Controllers\Admin\TravelerController::class, 'toggleAvailability'])->name('travelers.toggle-availability');
        Route::get('travelers/export/csv', [App\Http\Controllers\Admin\TravelerController::class, 'export'])->name('travelers.export');
        Route::get('travelers/stats/json', [App\Http\Controllers\Admin\TravelerController::class, 'stats'])->name('travelers.stats');
        
        // Transactions Management
        Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/create', [App\Http\Controllers\Admin\TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('transactions.show');
        Route::get('transactions/{transaction}/edit', [App\Http\Controllers\Admin\TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('transactions/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('transactions/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::patch('transactions/{transaction}/update-status', [App\Http\Controllers\Admin\TransactionController::class, 'updateStatus'])->name('transactions.update-status');
        Route::post('transactions/{transaction}/refund', [App\Http\Controllers\Admin\TransactionController::class, 'refund'])->name('transactions.refund');
        Route::get('transactions/export/csv', [App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('transactions.export');
        Route::get('transactions/stats/json', [App\Http\Controllers\Admin\TransactionController::class, 'stats'])->name('transactions.stats');
        
        // Deliveries Management
        Route::get('deliveries', [App\Http\Controllers\Admin\DeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('deliveries/create', [App\Http\Controllers\Admin\DeliveryController::class, 'create'])->name('deliveries.create');
        Route::post('deliveries', [App\Http\Controllers\Admin\DeliveryController::class, 'store'])->name('deliveries.store');
        Route::get('deliveries/{delivery}', [App\Http\Controllers\Admin\DeliveryController::class, 'show'])->name('deliveries.show');
        Route::get('deliveries/{delivery}/edit', [App\Http\Controllers\Admin\DeliveryController::class, 'edit'])->name('deliveries.edit');
        Route::put('deliveries/{delivery}', [App\Http\Controllers\Admin\DeliveryController::class, 'update'])->name('deliveries.update');
        Route::delete('deliveries/{delivery}', [App\Http\Controllers\Admin\DeliveryController::class, 'destroy'])->name('deliveries.destroy');
        Route::patch('deliveries/{delivery}/update-status', [App\Http\Controllers\Admin\DeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
        Route::get('deliveries/export/csv', [App\Http\Controllers\Admin\DeliveryController::class, 'export'])->name('deliveries.export');
        
        // Identity Documents Management
        Route::get('identity-documents', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'index'])->name('identity-documents.index');
        Route::get('identity-documents/create', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'create'])->name('identity-documents.create');
        Route::post('identity-documents', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'store'])->name('identity-documents.store');
        Route::get('identity-documents/{identity_document}', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'show'])->name('identity-documents.show');
        Route::get('identity-documents/{identity_document}/edit', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'edit'])->name('identity-documents.edit');
        Route::put('identity-documents/{identity_document}', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'update'])->name('identity-documents.update');
        Route::delete('identity-documents/{identity_document}', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'destroy'])->name('identity-documents.destroy');
        Route::patch('identity-documents/{identity_document}/verify', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'verify'])->name('identity-documents.verify');
        Route::patch('identity-documents/{identity_document}/set-primary', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'setPrimary'])->name('identity-documents.set-primary');
        Route::get('identity-documents/{identity_document}/download-photo/{type}', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'downloadPhoto'])->name('identity-documents.download-photo');
        Route::get('identity-documents/stats/json', [App\Http\Controllers\Admin\IdentityDocumentController::class, 'getStats'])->name('identity-documents.stats');
    });
});
