@extends('admin.layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Paramètres</h1>
            <p class="text-gray-600">Configuration générale de la plateforme</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Navigation Sidebar -->
        <div class="lg:col-span-1">
            <nav class="bg-white rounded-lg shadow p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#general" class="settings-tab-link active flex items-center px-3 py-2 text-sm font-medium rounded-md" data-tab="general">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Général
                        </a>
                    </li>
                    <li>
                        <a href="#payments" class="settings-tab-link flex items-center px-3 py-2 text-sm font-medium rounded-md" data-tab="payments">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Paiements
                        </a>
                    </li>
                    <li>
                        <a href="#notifications" class="settings-tab-link flex items-center px-3 py-2 text-sm font-medium rounded-md" data-tab="notifications">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h10V9H4v2z"></path>
                            </svg>
                            Notifications
                        </a>
                    </li>
                    <li>
                        <a href="#security" class="settings-tab-link flex items-center px-3 py-2 text-sm font-medium rounded-md" data-tab="security">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Sécurité
                        </a>
                    </li>
                    <li>
                        <a href="#maintenance" class="settings-tab-link flex items-center px-3 py-2 text-sm font-medium rounded-md" data-tab="maintenance">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                            Maintenance
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-3">
            <!-- General Settings -->
            <div id="general-tab" class="settings-tab-content bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Paramètres généraux</h2>
                
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'application</label>
                            <input type="text" value="Zendo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email de contact</label>
                            <input type="email" value="contact@zendo.com" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">Plateforme de livraison de colis entre la France et l'Afrique</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fuseau horaire</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Europe/Paris" selected>Europe/Paris (UTC+1)</option>
                                <option value="Africa/Dakar">Africa/Dakar (UTC+0)</option>
                                <option value="Africa/Abidjan">Africa/Abidjan (UTC+0)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Langue par défaut</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="fr" selected>Français</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="maintenance_mode" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">Mode maintenance</label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Payment Settings -->
            <div id="payments-tab" class="settings-tab-content bg-white rounded-lg shadow p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Paramètres de paiement</h2>
                
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Devise par défaut</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="EUR" selected>Euro (€)</option>
                                <option value="USD">Dollar US ($)</option>
                                <option value="XOF">Franc CFA (CFA)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commission plateforme (%)</label>
                            <input type="number" value="10" min="0" max="100" step="0.1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Méthodes de paiement</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <input type="checkbox" id="stripe" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="stripe" class="ml-3 text-sm font-medium text-gray-900">Stripe</label>
                                </div>
                                <button type="button" class="text-blue-600 hover:text-blue-800 text-sm">Configurer</button>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <input type="checkbox" id="paypal" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="paypal" class="ml-3 text-sm font-medium text-gray-900">PayPal</label>
                                </div>
                                <button type="button" class="text-blue-600 hover:text-blue-800 text-sm">Configurer</button>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <input type="checkbox" id="orange_money" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="orange_money" class="ml-3 text-sm font-medium text-gray-900">Orange Money</label>
                                </div>
                                <button type="button" class="text-blue-600 hover:text-blue-800 text-sm">Configurer</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notification Settings -->
            <div id="notifications-tab" class="settings-tab-content bg-white rounded-lg shadow p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Paramètres de notification</h2>
                
                <form class="space-y-6">
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Notifications email</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Nouveaux utilisateurs</label>
                                    <p class="text-sm text-gray-600">Recevoir un email lors de l'inscription d'un nouvel utilisateur</p>
                                </div>
                                <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Nouveaux colis</label>
                                    <p class="text-sm text-gray-600">Recevoir un email lors de la création d'un nouveau colis</p>
                                </div>
                                <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Tickets support</label>
                                    <p class="text-sm text-gray-600">Recevoir un email lors de la création d'un ticket support</p>
                                </div>
                                <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Configuration SMTP</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Serveur SMTP</label>
                                <input type="text" value="smtp.gmail.com" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Port</label>
                                <input type="number" value="587" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom d'utilisateur</label>
                                <input type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                                <input type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Settings -->
            <div id="security-tab" class="settings-tab-content bg-white rounded-lg shadow p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Paramètres de sécurité</h2>
                
                <form class="space-y-6">
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Authentification</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Authentification à deux facteurs</label>
                                    <p class="text-sm text-gray-600">Activer l'authentification à deux facteurs pour les administrateurs</p>
                                </div>
                                <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Vérification email obligatoire</label>
                                    <p class="text-sm text-gray-600">Obliger la vérification email pour les nouveaux comptes</p>
                                </div>
                                <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Politique de mot de passe</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Longueur minimale</label>
                                <input type="number" value="8" min="6" max="20" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiration (jours)</label>
                                <input type="number" value="90" min="30" max="365" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="require_uppercase" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="require_uppercase" class="ml-2 text-sm text-gray-900">Exiger au moins une majuscule</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="require_numbers" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="require_numbers" class="ml-2 text-sm text-gray-900">Exiger au moins un chiffre</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="require_special" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="require_special" class="ml-2 text-sm text-gray-900">Exiger au moins un caractère spécial</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Maintenance Settings -->
            <div id="maintenance-tab" class="settings-tab-content bg-white rounded-lg shadow p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Maintenance</h2>
                
                <div class="space-y-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Mode maintenance</h3>
                                <p class="text-sm text-yellow-700 mt-1">Activez le mode maintenance pour effectuer des mises à jour ou des réparations.</p>
                            </div>
                        </div>
                    </div>

                    <form class="space-y-6">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <label class="text-sm font-medium text-gray-900">Mode maintenance</label>
                                <p class="text-sm text-gray-600">Désactiver temporairement l'accès public à la plateforme</p>
                            </div>
                            <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message de maintenance</label>
                            <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Message affiché aux utilisateurs pendant la maintenance...">Nous effectuons actuellement une maintenance programmée. Le service sera rétabli sous peu.</textarea>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Actions de maintenance</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button type="button" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Vider le cache
                                </button>
                                
                                <button type="button" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Sauvegarder la DB
                                </button>
                                
                                <button type="button" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Générer logs
                                </button>
                                
                                <button type="button" class="flex items-center justify-center px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Nettoyer logs
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Gestion des onglets
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.settings-tab-link');
        const tabContents = document.querySelectorAll('.settings-tab-content');
        
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Retirer la classe active de tous les liens
                tabLinks.forEach(l => l.classList.remove('active', 'bg-blue-100', 'text-blue-700'));
                tabLinks.forEach(l => l.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-100'));
                
                // Ajouter la classe active au lien cliqué
                this.classList.add('active', 'bg-blue-100', 'text-blue-700');
                this.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-100');
                
                // Cacher tous les contenus
                tabContents.forEach(content => content.classList.add('hidden'));
                
                // Afficher le contenu correspondant
                const tabId = this.getAttribute('data-tab') + '-tab';
                document.getElementById(tabId).classList.remove('hidden');
            });
        });
        
        // Initialiser le premier onglet comme actif
        const firstTab = tabLinks[0];
        firstTab.classList.add('active', 'bg-blue-100', 'text-blue-700');
        firstTab.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-gray-100');
    });
</script>
@endpush