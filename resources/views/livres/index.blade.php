<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Gestion des livres</h1>
                <p class="mt-1 text-sm text-gray-500">Liste complète des livres de la bibliothèque</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('livres.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors duration-200 shadow-sm hover:shadow">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter un livre
                </a>
                <div class="text-sm text-gray-500">
                    {{ $livres->count() }} livre{{ $livres->count() > 1 ? 's' : '' }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>ISBN</th>
                            <th>Catégorie</th>
                            <th>Auteurs</th>
                            <th>Stock</th>
                            <th>Disponibilité</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($livres as $livre)
                            <tr>
                                <td>
                                    <div class="font-medium text-gray-900">{{ $livre->titre ?? 'N/A' }}</div>
                                    @if($livre->resume)
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($livre->resume, 60) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-mono text-sm text-gray-600">{{ $livre->isbn ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($livre->categorie)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                {{ $livre->categorie->libelle }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Non catégorisé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($livre->auteurs && $livre->auteurs->count() > 0)
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $livre->auteurs->pluck('nom')->join(', ') }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $livre->auteurs->count() }} auteur{{ $livre->auteurs->count() > 1 ? 's' : '' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Aucun auteur</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900">{{ (int)($livre->exemplaires_disponibles ?? 0) }}</span>
                                        <span class="text-sm text-gray-400">/</span>
                                        <span class="text-sm text-gray-600">{{ (int)($livre->nombre_exemplaires ?? 0) }}</span>
                                    </div>
                                    @php
                                        $pourcentage = $livre->nombre_exemplaires > 0 ? (($livre->exemplaires_disponibles ?? 0) / $livre->nombre_exemplaires) * 100 : 0;
                                    @endphp
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-{{ $pourcentage > 50 ? 'emerald' : ($pourcentage > 20 ? 'amber' : 'red') }}-600 h-1.5 rounded-full" style="width: {{ $pourcentage }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    @if(($livre->exemplaires_disponibles ?? 0) > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Disponible
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Épuisé
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('livres.edit', $livre) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Modifier
                                        </a>
                                        @if(($livre->exemplaires_disponibles ?? 0) > 0)
                                            <form method="POST" action="{{ route('livres.indisponible', $livre) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-sm font-medium hover:bg-red-200 transition-colors duration-200" onclick="return confirm('Êtes-vous sûr de vouloir rendre ce livre indisponible ?')">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Retirer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucun livre</h3>
                                        <p class="text-sm text-gray-500 mb-4">Commencez par ajouter votre premier livre à la bibliothèque.</p>
                                        <a href="{{ route('livres.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Ajouter un livre
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
