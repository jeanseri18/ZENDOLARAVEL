@extends('admin.layouts.app')

@section('title', 'Tableau de Bord Admin')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord</h1>
    <p class="text-gray-600">Vue d'ensemble de votre plateforme ZENDO</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Utilisateurs</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['users'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Colis</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['packages'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.829 2.829A4 4 0 019.828 7H15a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h-.172z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Tickets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['tickets'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Revenus</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['revenue'] ?? 0, 0, ',', ' ') }} XOF</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Statistics Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Évolution Mensuelle</h3>
            <div class="flex space-x-2">
                <button onclick="toggleChart('users')" class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors" id="btn-users">Utilisateurs</button>
                <button onclick="toggleChart('packages')" class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors" id="btn-packages">Colis</button>
                <button onclick="toggleChart('revenue')" class="px-3 py-1 text-xs bg-purple-100 text-purple-800 rounded-full hover:bg-purple-200 transition-colors" id="btn-revenue">Revenus</button>
            </div>
        </div>
        <div class="h-64">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Package Status Pie Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition des Colis</h3>
        <div class="h-64">
            <canvas id="packageStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Weekly Revenue Chart -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Revenus Hebdomadaires</h3>
    <div class="h-64">
        <canvas id="weeklyRevenueChart"></canvas>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Utilisateurs Récents</h3>
        </div>
        <div class="p-6">
            @if(isset($recentUsers) && $recentUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Voir tous les utilisateurs →
                    </a>
                </div>
            @else
                <p class="text-gray-500">Aucun utilisateur récent</p>
            @endif
        </div>
    </div>

    <!-- Recent Packages -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Colis Récents</h3>
        </div>
        <div class="p-6">
            @if(isset($recentPackages) && $recentPackages->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPackages as $package)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $package->tracking_number }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $package->origin }} → {{ $package->destination }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @switch($package->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('in_transit') bg-blue-100 text-blue-800 @break
                                    @case('delivered') bg-green-100 text-green-800 @break
                                    @case('cancelled') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($package->status)
                                    @case('pending') En attente @break
                                    @case('in_transit') En transit @break
                                    @case('delivered') Livré @break
                                    @case('cancelled') Annulé @break
                                    @default {{ $package->status }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.packages.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Voir tous les colis →
                    </a>
                </div>
            @else
                <p class="text-gray-500">Aucun colis récent</p>
            @endif
        </div>
    </div>
</div>

<!-- Recent Tickets -->
<div class="mt-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Tickets de Support Récents</h3>
        </div>
        <div class="p-6">
            @if(isset($recentTickets) && $recentTickets->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentTickets as $ticket)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $ticket->ticket_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ Str::limit($ticket->subject, 30) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ($ticket->user->first_name ?? '') . ' ' . ($ticket->user->last_name ?? '') ?: 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @switch($ticket->status)
                                            @case('open') bg-red-100 text-red-800 @break
                                            @case('in_progress') bg-yellow-100 text-yellow-800 @break
                                            @case('resolved') bg-green-100 text-green-800 @break
                                            @case('closed') bg-gray-100 text-gray-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($ticket->status)
                                            @case('open') Ouvert @break
                                            @case('in_progress') En cours @break
                                            @case('resolved') Résolu @break
                                            @case('closed') Fermé @break
                                            @default {{ $ticket->status }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.support-tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Voir tous les tickets →
                    </a>
                </div>
            @else
                <p class="text-gray-500">Aucun ticket récent</p>
            @endif
        </div>
    </div>
</div>

<script>
// Données des graphiques depuis PHP
const chartData = @json($chartData);

// Configuration des couleurs
const colors = {
    primary: '#3B82F6',
    success: '#10B981',
    warning: '#F59E0B',
    danger: '#EF4444',
    purple: '#8B5CF6'
};

// Graphique mensuel (ligne)
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
let monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: chartData.monthly.labels,
        datasets: [{
            label: 'Utilisateurs',
            data: chartData.monthly.users,
            borderColor: colors.primary,
            backgroundColor: colors.primary + '20',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#F3F4F6'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Fonction pour basculer entre les données du graphique mensuel
let currentDataset = 'users';
function toggleChart(type) {
    // Réinitialiser les styles des boutons
    document.querySelectorAll('[id^="btn-"]').forEach(btn => {
        btn.className = btn.className.replace(/bg-\w+-200/, 'bg-gray-100').replace(/text-\w+-800/, 'text-gray-600');
    });
    
    // Activer le bouton sélectionné
    const activeBtn = document.getElementById(`btn-${type}`);
    activeBtn.className = activeBtn.className.replace('bg-gray-100 text-gray-600', `bg-${getColorClass(type)}-200 text-${getColorClass(type)}-800`);
    
    let data, label, color;
    switch(type) {
        case 'users':
            data = chartData.monthly.users;
            label = 'Utilisateurs';
            color = colors.primary;
            break;
        case 'packages':
            data = chartData.monthly.packages;
            label = 'Colis';
            color = colors.success;
            break;
        case 'revenue':
            data = chartData.monthly.revenue;
            label = 'Revenus (XOF)';
            color = colors.purple;
            break;
    }
    
    monthlyChart.data.datasets[0] = {
        label: label,
        data: data,
        borderColor: color,
        backgroundColor: color + '20',
        tension: 0.4,
        fill: true
    };
    
    monthlyChart.update();
    currentDataset = type;
}

function getColorClass(type) {
    switch(type) {
        case 'users': return 'blue';
        case 'packages': return 'green';
        case 'revenue': return 'purple';
        default: return 'gray';
    }
}

// Graphique en secteurs pour les statuts des colis
const packageStatusCtx = document.getElementById('packageStatusChart').getContext('2d');
const packageStatusChart = new Chart(packageStatusCtx, {
    type: 'doughnut',
    data: {
        labels: chartData.packageStatuses.labels,
        datasets: [{
            data: chartData.packageStatuses.data,
            backgroundColor: chartData.packageStatuses.colors,
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Graphique des revenus hebdomadaires (barres)
const weeklyRevenueCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
const weeklyRevenueChart = new Chart(weeklyRevenueCtx, {
    type: 'bar',
    data: {
        labels: chartData.weeklyRevenue.labels,
        datasets: [{
            label: 'Revenus (XOF)',
            data: chartData.weeklyRevenue.data,
            backgroundColor: colors.success + '80',
            borderColor: colors.success,
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#F3F4F6'
                },
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('fr-FR').format(value) + ' XOF';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenus: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' XOF';
                    }
                }
            }
        }
    }
});

// Initialiser avec le graphique des utilisateurs actif
toggleChart('users');
</script>

@endsection