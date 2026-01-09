<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Emprunts en cours</h1>
                <p class="mt-1 text-sm text-gray-500">Gérer les prêts actuellement en cours</p>
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
                            <th>Lecteur</th>
                            <th>Livre</th>
                            <th>Statut</th>
                            <th>Date emprunt</th>
                            <th>Retour prévu</th>
                            <th>Jours restants</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emprunts as $emprunt)
                            @php
                                $joursRestants = $emprunt->date_retour_prevue ? \Carbon\Carbon::parse($emprunt->date_retour_prevue)->diffInDays(\Carbon\Carbon::now(), false) : null;
                                $isRetard = $emprunt->statut === 'en_retard';
                                $isBientotExpire = $joursRestants !== null && $joursRestants <= 3 && $joursRestants >= 0;
                            @endphp
                            <tr class="{{ $isRetard ? 'bg-red-50' : ($isBientotExpire ? 'bg-amber-50' : '') }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-sm">
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
                                    @if($isRetard)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            En retard
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            En cours
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">{{ optional($emprunt->date_emprunt)->format('d/m/Y') ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">{{ optional($emprunt->date_retour_prevue)->format('d/m/Y') ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($joursRestants !== null)
                                        @if($isRetard)
                                            <span class="text-sm font-semibold text-red-600">+{{ abs($joursRestants) }}j</span>
                                        @elseif($isBientotExpire)
                                            <span class="text-sm font-semibold text-amber-600">{{ $joursRestants }}j</span>
                                        @else
                                            <span class="text-sm text-gray-600">{{ $joursRestants }}j</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('emprunts.retour', $emprunt) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors duration-200 shadow-sm hover:shadow">
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
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucun emprunt en cours</h3>
                                        <p class="text-sm text-gray-500">Il n'y a actuellement aucun prêt actif.</p>
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
