<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Emprunts en retard</h1>
                <p class="mt-1 text-sm text-gray-500">Liste des prêts en retard nécessitant une attention</p>
            </div>
            <div class="text-sm text-red-600 font-semibold">
                {{ $emprunts->count() }} retard{{ $emprunts->count() > 1 ? 's' : '' }}
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Lecteur</th>
                            <th>Livre</th>
                            <th>Retour prévu</th>
                            <th>Jours de retard</th>
                            <th>Pénalité</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emprunts as $emprunt)
                            @php
                                $joursRetard = $emprunt->date_retour_prevue ? \Carbon\Carbon::parse($emprunt->date_retour_prevue)->diffInDays(\Carbon\Carbon::now(), false) : 0;
                                $penalite = $emprunt->penalite ? $emprunt->penalite->montant : ($joursRetard > 0 ? $joursRetard * 500 : 0);
                            @endphp
                            <tr class="bg-red-50">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-semibold text-sm">
                                            {{ strtoupper(substr($emprunt->user->login ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $emprunt->user->login ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-medium text-gray-900">{{ $emprunt->livre->titre ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $emprunt->livre->categorie->libelle ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">{{ optional($emprunt->date_retour_prevue)->format('d/m/Y') ?? '-' }}</div>
                                    <div class="text-xs text-red-600 mt-1">{{ optional($emprunt->date_retour_prevue)->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        +{{ $joursRetard }} jour{{ $joursRetard > 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td>
                                    @if($penalite > 0)
                                        <div class="text-sm font-semibold text-gray-900">{{ number_format($penalite, 0, ',', ' ') }} FCFA</div>
                                        @if($emprunt->penalite && $emprunt->penalite->payee)
                                            <span class="text-xs text-emerald-600">Payée</span>
                                        @else
                                            <span class="text-xs text-red-600">Non payée</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('emprunts.retour', $emprunt) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Valider retour
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucun retard</h3>
                                        <p class="text-sm text-gray-500">Excellent ! Tous les prêts sont à jour.</p>
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
