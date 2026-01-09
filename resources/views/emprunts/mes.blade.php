<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Mes emprunts</h1>
                <p class="mt-1 text-sm text-gray-500">Historique de tous mes emprunts</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $emprunts->count() }} emprunt{{ $emprunts->count() > 1 ? 's' : '' }}
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Date emprunt</th>
                            <th>Retour prévu</th>
                            <th>Retour effectif</th>
                            <th>Pénalité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emprunts as $emprunt)
                            @php
                                $isRetard = $emprunt->statut === 'en_retard';
                                $isEnCours = $emprunt->statut === 'en_cours';
                                $isRetourne = $emprunt->statut === 'retourne';
                                $joursRetard = $emprunt->date_retour_prevue && !$emprunt->date_retour_effective ? max(0, \Carbon\Carbon::parse($emprunt->date_retour_prevue)->diffInDays(\Carbon\Carbon::now(), false)) : 0;
                            @endphp
                            <tr class="{{ $isRetard ? 'bg-red-50' : ($isEnCours ? 'bg-blue-50' : '') }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $emprunt->livre->titre ?? 'N/A' }}</div>
                                            @if($emprunt->livre->auteurs && $emprunt->livre->auteurs->count() > 0)
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $emprunt->livre->auteurs->pluck('nom')->join(', ') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $emprunt->livre->categorie->libelle ?? 'Non catégorisé' }}
                                    </span>
                                </td>
                                <td>
                                    @if($isRetard)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            En retard
                                        </span>
                                    @elseif($isEnCours)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            En cours
                                        </span>
                                    @elseif($isRetourne)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Retourné
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">{{ optional($emprunt->date_emprunt)->format('d/m/Y') ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">{{ optional($emprunt->date_retour_prevue)->format('d/m/Y') ?? '-' }}</div>
                                    @if($isEnCours && $joursRetard === 0)
                                        @php
                                            $joursRestants = \Carbon\Carbon::parse($emprunt->date_retour_prevue)->diffInDays(\Carbon\Carbon::now(), false);
                                        @endphp
                                        <div class="text-xs {{ $joursRestants <= 3 ? 'text-amber-600' : 'text-gray-500' }} mt-1">
                                            {{ abs($joursRestants) }} jour{{ abs($joursRestants) > 1 ? 's' : '' }} restant{{ abs($joursRestants) > 1 ? 's' : '' }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($emprunt->date_retour_effective)
                                        <div class="text-sm text-gray-900">{{ optional($emprunt->date_retour_effective)->format('d/m/Y') }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($emprunt->penalite)
                                        <div class="text-sm font-semibold text-red-600">{{ number_format($emprunt->penalite->montant, 0, ',', ' ') }} FCFA</div>
                                        <div class="text-xs {{ $emprunt->penalite->payee ? 'text-emerald-600' : 'text-red-600' }} mt-1">
                                            {{ $emprunt->penalite->payee ? 'Payée' : 'Non payée' }}
                                        </div>
                                    @elseif($isRetard && $joursRetard > 0)
                                        <div class="text-sm font-semibold text-red-600">{{ number_format($joursRetard * 500, 0, ',', ' ') }} FCFA</div>
                                        <div class="text-xs text-red-600 mt-1">À payer</div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucun emprunt</h3>
                                        <p class="text-sm text-gray-500 mb-4">Vous n'avez pas encore emprunté de livre.</p>
                                        <a href="{{ route('catalogue') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Parcourir le catalogue
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

