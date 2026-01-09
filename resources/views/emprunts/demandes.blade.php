<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Demandes d'emprunt</h1>
                <p class="mt-1 text-sm text-gray-500">Gérer les demandes d'emprunt en attente</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $emprunts->count() }} demande{{ $emprunts->count() > 1 ? 's' : '' }}
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Lecteur</th>
                            <th>Livre</th>
                            <th>Catégorie</th>
                            <th>Date demande</th>
                            <th>Retour prévu</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emprunts as $emprunt)
                            <tr>
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
                                    @if($emprunt->livre->isbn ?? null)
                                        <div class="text-xs text-gray-500 mt-1">ISBN: {{ $emprunt->livre->isbn }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $emprunt->livre->categorie->libelle ?? 'Non catégorisé' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">{{ optional($emprunt->date_emprunt)->format('d/m/Y') ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ optional($emprunt->date_emprunt)->format('H:i') ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900">{{ optional($emprunt->date_retour_prevue)->format('d/m/Y') ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">Dans {{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('emprunts.valider', $emprunt) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors duration-200 shadow-sm hover:shadow">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Valider
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucune demande</h3>
                                        <p class="text-sm text-gray-500">Il n'y a actuellement aucune demande d'emprunt en attente.</p>
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
