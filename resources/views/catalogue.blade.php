<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white">
                    Catalogue des livres
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Recherchez et explorez notre collection
                </p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $livres->count() }} livre{{ $livres->count() > 1 ? 's' : '' }}
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 animate-slide-up">
        <!-- Formulaire de recherche -->
        <div class="card p-6">
            <form method="GET" action="{{ route('catalogue') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label for="titre" value="Titre du livre" />
                        <input type="text" id="titre" name="titre" value="{{ request('titre') }}"
                               placeholder="Rechercher par titre..."
                               class="input-modern mt-1" />
                    </div>

                    <div>
                        <x-input-label for="categorie_id" value="Catégorie" />
                        <select id="categorie_id" name="categorie_id" class="input-modern mt-1">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string)request('categorie_id') === (string)$cat->id ? 'selected' : '' }}>
                                    {{ $cat->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="auteur_id" value="Auteur" />
                        <select id="auteur_id" name="auteur_id" class="input-modern mt-1">
                            <option value="">Tous les auteurs</option>
                            @foreach($auteurs as $auteur)
                                <option value="{{ $auteur->id }}" {{ (string)request('auteur_id') === (string)$auteur->id ? 'selected' : '' }}>
                                    {{ $auteur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-modern gradient-primary text-white">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Rechercher
                    </button>
                    @if(request()->anyFilled(['titre', 'categorie_id', 'auteur_id']))
                        <a href="{{ route('catalogue') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Grille de livres -->
        @if($livres->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($livres as $livre)
                    <div class="card p-5 group animate-scale-in hover:scale-105 transition-transform duration-300">
                        <!-- En-tête de la carte -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="h-12 w-12 rounded-xl gradient-primary flex items-center justify-center mb-3 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                @if($livre->categorie)
                                    <span class="badge bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 text-xs">
                                        {{ $livre->categorie->libelle }}
                                    </span>
                                @endif
                            </div>
                            @if(($livre->exemplaires_disponibles ?? 0) > 0)
                                <span class="badge bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                    Disponible
                                </span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    Épuisé
                                </span>
                            @endif
                        </div>

                        <!-- Contenu -->
                        <div class="mb-4">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $livre->titre }}
                            </h3>

                            @if($livre->auteurs && $livre->auteurs->count() > 0)
                                <div class="flex items-center gap-2 text-sm mb-3">
                                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $livre->auteurs->pluck('nom')->join(', ') }}</span>
                                </div>
                            @else
                                <div class="text-sm text-gray-400 italic mb-3">Aucun auteur</div>
                            @endif

                            @if($livre->categorie)
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span class="text-xs font-medium text-indigo-700 dark:text-indigo-300">{{ $livre->categorie->libelle }}</span>
                                </div>
                            @endif

                            @if($livre->isbn)
                                <p class="text-xs text-gray-500 dark:text-gray-500 mb-3">
                                    ISBN: <span class="font-mono">{{ $livre->isbn }}</span>
                                </p>
                            @endif

                            <div class="flex items-center justify-between text-sm pt-3 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Stock</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ (int)($livre->exemplaires_disponibles ?? 0) }} / {{ (int)($livre->nombre_exemplaires ?? 0) }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        @if(auth()->user()?->role === 'Rlecteur')
                            <div class="mt-4">
                                @if(($livre->exemplaires_disponibles ?? 0) > 0)
                                    <form method="POST" action="{{ route('emprunts.demander', $livre) }}">
                                        @csrf
                                        <button type="submit" class="w-full btn-modern gradient-primary text-white text-sm">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Demander l'emprunt
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-sm font-semibold cursor-not-allowed">
                                        Indisponible
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <!-- État vide -->
            <div class="card p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="h-20 w-20 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucun livre trouvé</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        Aucun livre ne correspond à vos critères de recherche.
                    </p>
                    @if(request()->anyFilled(['titre', 'categorie_id', 'auteur_id']))
                        <a href="{{ route('catalogue') }}" class="btn-modern gradient-primary text-white inline-flex">
                            Voir tous les livres
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
