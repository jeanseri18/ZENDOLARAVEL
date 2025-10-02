@extends('admin.layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rapports</h1>
            <p class="text-gray-600">Analyses et statistiques de la plateforme</p>
        </div>
        <div class="flex space-x-3">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exporter PDF
            </button>
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 7h16"></path>
                </svg>
                Exporter Excel
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white p-6 rounded-lg shadow">
        <form class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select name="period" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="custom">Personnalisée</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus totaux</p>
                    <p class="text-2xl font-bold text-gray-900">45,231 XOF</p>
                    <p class="text-sm text-green-600">+12.5% vs période précédente</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Colis traités</p>
                    <p class="text-2xl font-bold text-gray-900">1,234</p>
                    <p class="text-sm text-green-600">+8.2% vs période précédente</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Nouveaux utilisateurs</p>
                    <p class="text-2xl font-bold text-gray-900">89</p>
                    <p class="text-sm text-red-600">-3.1% vs période précédente</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Taux de satisfaction</p>
                    <p class="text-2xl font-bold text-gray-900">94.2%</p>
                    <p class="text-sm text-green-600">+2.1% vs période précédente</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Évolution des revenus</h3>
                <select class="border border-gray-300 rounded px-3 py-1 text-sm">
                    <option>30 derniers jours</option>
                    <option>3 derniers mois</option>
                    <option>12 derniers mois</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                <p class="text-gray-500">Graphique des revenus (Chart.js à intégrer)</p>
            </div>
        </div>

        <!-- Package Status Chart -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Statut des colis</h3>
                <select class="border border-gray-300 rounded px-3 py-1 text-sm">
                    <option>Ce mois</option>
                    <option>3 derniers mois</option>
                    <option>Cette année</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                <p class="text-gray-500">Graphique en secteurs (Chart.js à intégrer)</p>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Routes -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Routes les plus populaires</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium text-gray-900">Paris → Dakar</p>
                        <p class="text-sm text-gray-600">156 colis</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">12,450 XOF</p>
                        <p class="text-sm text-green-600">+15%</p>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium text-gray-900">Lyon → Abidjan</p>
                        <p class="text-sm text-gray-600">134 colis</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">10,720 XOF</p>
                        <p class="text-sm text-green-600">+8%</p>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium text-gray-900">Marseille → Casablanca</p>
                        <p class="text-sm text-gray-600">98 colis</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">7,840 XOF</p>
                        <p class="text-sm text-red-600">-2%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Travelers -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Meilleurs voyageurs</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                            AM
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Ahmed Mamadou</p>
                            <p class="text-sm text-gray-600">45 livraisons</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">3,600 XOF</p>
                        <p class="text-sm text-yellow-600">⭐ 4.9</p>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-medium">
                            FD
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Fatou Diallo</p>
                            <p class="text-sm text-gray-600">38 livraisons</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">3,040 XOF</p>
                        <p class="text-sm text-yellow-600">⭐ 4.8</p>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-medium">
                            KT
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Karim Touré</p>
                            <p class="text-sm text-gray-600">32 livraisons</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">2,560 XOF</p>
                        <p class="text-sm text-yellow-600">⭐ 4.7</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Métriques de performance</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">2.3 jours</div>
                <div class="text-sm text-gray-600">Temps moyen de livraison</div>
                <div class="text-xs text-green-600 mt-1">-0.2 jours vs mois dernier</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">97.8%</div>
                <div class="text-sm text-gray-600">Taux de livraison réussie</div>
                <div class="text-xs text-green-600 mt-1">+1.2% vs mois dernier</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">4.6/5</div>
                <div class="text-sm text-gray-600">Note moyenne des voyageurs</div>
                <div class="text-xs text-green-600 mt-1">+0.1 vs mois dernier</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script pour gérer les filtres de période
    document.querySelector('select[name="period"]').addEventListener('change', function() {
        const period = this.value;
        const startDate = document.querySelector('input[name="start_date"]');
        const endDate = document.querySelector('input[name="end_date"]');
        
        const today = new Date();
        let start, end;
        
        switch(period) {
            case 'today':
                start = end = today.toISOString().split('T')[0];
                break;
            case 'week':
                start = new Date(today.setDate(today.getDate() - 7)).toISOString().split('T')[0];
                end = new Date().toISOString().split('T')[0];
                break;
            case 'month':
                start = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                end = new Date().toISOString().split('T')[0];
                break;
            case 'quarter':
                const quarter = Math.floor(today.getMonth() / 3);
                start = new Date(today.getFullYear(), quarter * 3, 1).toISOString().split('T')[0];
                end = new Date().toISOString().split('T')[0];
                break;
            case 'year':
                start = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                end = new Date().toISOString().split('T')[0];
                break;
        }
        
        if (period !== 'custom') {
            startDate.value = start;
            endDate.value = end;
        }
    });
</script>
@endpush