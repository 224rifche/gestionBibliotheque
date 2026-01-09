@php
    use App\Models\Livre;
    use App\Models\Emprunt;
    use App\Models\User;
    use App\Models\Categorie;
    use App\Models\Auteur;
    use Illuminate\Support\Facades\DB;

    $user = Auth::user();

    $stats = [
        'total_livres' => Livre::count(),
        'livres_disponibles' => Livre::where('disponible', true)->count(),
        'livres_indisponibles' => Livre::where('disponible', false)->count(),
        'total_emprunts' => Emprunt::whereNull('date_retour_effective')->count(),
        'emprunts_en_retard' => Emprunt::where('statut', 'en_retard')->count(),
        'demandes_en_attente' => Emprunt::where('statut', 'demande')->count(),
        'emprunts_actifs' => Emprunt::where('statut', 'en_cours')->count(),
        'total_utilisateurs' => User::where('actif', true)->count(),
        'total_categories' => Categorie::count(),
        'total_auteurs' => Auteur::count(),
        'emprunts_ce_mois' => Emprunt::whereMonth('date_emprunt', now()->month)
            ->whereYear('date_emprunt', now()->year)
            ->count(),
        'retours_ce_mois' => Emprunt::whereNotNull('date_retour_effective')
            ->whereMonth('date_retour_effective', now()->month)
            ->whereYear('date_retour_effective', now()->year)
            ->count(),
    ];

    // Statistiques pour les lecteurs
    if($user && $user->role === 'Rlecteur') {
        $stats['mes_emprunts'] = Emprunt::where('user_id', $user->id)
            ->whereNull('date_retour_effective')
            ->count();
        $stats['mes_retards'] = Emprunt::where('user_id', $user->id)
            ->where('statut', 'en_retard')
            ->count();
    }

    // Emprunts récents (5 derniers)
    $emprunts_recents = Emprunt::with(['user', 'livre'])
        ->orderBy('date_emprunt', 'desc')
        ->limit(5)
        ->get();
@endphp

<x-app-layout>
    <div class="space-y-6 animate-slide-up">
        <!-- En-tête avec salutation -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="mt-2 text-gray-600">Bienvenue, {{ $user?->login }} ! Voici un aperçu de votre bibliothèque.</p>
        </div>

        <!-- Cartes de statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Livres -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-white/90 mb-1">Total Livres</p>
                <p class="text-4xl font-bold text-white mb-2">{{ $stats['total_livres'] }}</p>
                <div class="flex items-center gap-2 text-xs text-white/80">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ $stats['livres_disponibles'] }} disponibles
                    </span>
                </div>
            </div>

            <!-- Emprunts en cours -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-white/90 mb-1">Emprunts en cours</p>
                <p class="text-4xl font-bold text-white mb-2">{{ $stats['total_emprunts'] }}</p>
                <div class="flex items-center gap-2 text-xs text-white/80">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        {{ $stats['demandes_en_attente'] }} en attente
                    </span>
                </div>
            </div>

            <!-- Retards -->
            <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-white/90 mb-1">Retards</p>
                <p class="text-4xl font-bold text-white mb-2">{{ $stats['emprunts_en_retard'] }}</p>
                <div class="mt-3 h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ $stats['total_emprunts'] > 0 ? min(($stats['emprunts_en_retard'] / $stats['total_emprunts']) * 100, 100) : 0 }}%"></div>
                </div>
            </div>

            <!-- Utilisateurs -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-white/90 mb-1">Utilisateurs actifs</p>
                <p class="text-4xl font-bold text-white mb-2">{{ $stats['total_utilisateurs'] }}</p>
                <p class="text-xs text-white/80">Membres enregistrés</p>
            </div>
        </div>

        <!-- Deuxième ligne de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Catégories -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Catégories</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Auteurs -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Auteurs</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_auteurs'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Emprunts ce mois -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Emprunts ce mois</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['emprunts_ce_mois'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-cyan-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Retours ce mois -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Retours ce mois</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['retours_ce_mois'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emprunts récents -->
        @if($emprunts_recents->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Emprunts récents</h2>
                <a href="{{ route('emprunts.en_cours') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="space-y-4">
                @foreach($emprunts_recents as $emprunt)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $emprunt->livre->titre ?? 'Livre supprimé' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $emprunt->user->login ?? 'Utilisateur supprimé' }} • {{ $emprunt->date_emprunt->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        @if($emprunt->statut === 'en_retard')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">En retard</span>
                        @elseif($emprunt->statut === 'demande')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">En attente</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">En cours</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
