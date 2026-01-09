<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajouter un nouveau livre') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('livres.store') }}" class="space-y-6" id="livreForm">
                        @csrf
                        @if(session('success'))
                            <script>
                                (function() {
                                    var form = document.getElementById('livreForm');
                                    if (form) {
                                        form.reset();
                                        // Réinitialiser les sélections
                                        var disponibleSelect = form.querySelector('#disponible');
                                        if (disponibleSelect) disponibleSelect.selectedIndex = 0;
                                        var categorieSelect = form.querySelector('#categorie_id');
                                        if (categorieSelect) categorieSelect.selectedIndex = 0;
                                        // Décocher toutes les checkboxes d'auteurs
                                        var auteurCheckboxes = form.querySelectorAll('input[type="checkbox"][name="auteurs[]"]');
                                        auteurCheckboxes.forEach(function(cb) { cb.checked = false; });
                                    }
                                })();
                            </script>
                        @endif

                        <!-- Section Informations de base -->
                        <div class="space-y-6 bg-gray-50 dark:bg-gray-700/30 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">
                                <i class="fas fa-book mr-2"></i>Informations du livre
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="titre" :value="__('Titre du livre *')" />
                                    <x-text-input id="titre" name="titre" type="text" class="mt-1 block w-full" 
                                                value="{{ old('titre') }}" required autofocus />
                                    <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="resume" :value="__('Résumé')" />
                                    <textarea id="resume" name="resume" rows="3" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">{{ old('resume') }}</textarea>
                                    <x-input-error :messages="$errors->get('resume')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <x-input-label for="isbn" :value="__('ISBN *')" />
                                        <x-text-input id="isbn" name="isbn" type="text" 
                                                    class="mt-1 block w-full" value="{{ old('isbn') }}" required />
                                        <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="nombre_exemplaires" :value="__('Exemplaires *')" />
                                        <x-text-input id="nombre_exemplaires" name="nombre_exemplaires" type="number" 
                                                    min="1" value="{{ old('nombre_exemplaires', 1) }}" 
                                                    class="mt-1 block w-full" required />
                                        <x-input-error :messages="$errors->get('nombre_exemplaires')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="disponible" :value="__('Disponible *')" />
                                        <select id="disponible" name="disponible" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">
                                            <option value="1" {{ old('disponible', true) ? 'selected' : '' }}>Oui</option>
                                            <option value="0" {{ old('disponible') === '0' ? 'selected' : '' }}>Non</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('disponible')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="categorie_id" :value="__('Catégorie *')" />
                                    <select id="categorie_id" name="categorie_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">
                                        <option value="">Sélectionnez une catégorie</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('categorie_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Auteurs -->
                        <div class="space-y-6 bg-gray-50 dark:bg-gray-700/30 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">
                                <i class="fas fa-users mr-2"></i>Auteurs *
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    @forelse($auteurs as $auteur)
                                        <div class="flex items-center">
                                            <input id="auteur-{{ $auteur->id }}" type="checkbox" name="auteurs[]" 
                                                value="{{ $auteur->id }}" 
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
                                                {{ in_array($auteur->id, (array)old('auteurs', [])) ? 'checked' : '' }}>
                                            <label for="auteur-{{ $auteur->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $auteur->nom }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucun auteur disponible. Veuillez d'abord en ajouter un.</p>
                                    @endforelse
                                </div>
                                <div class="flex justify-end">
                                    <a href="{{ route('auteurs.create') }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ajouter un auteur
                                    </a>
                                </div>
                                <x-input-error :messages="$errors->get('auteurs')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <x-secondary-button type="button" onclick="window.history.back()" class="flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                {{ __('Retour') }}
                            </x-secondary-button>
                            
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save mr-2"></i>
                                {{ __('Enregistrer le livre') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Script pour gérer l'affichage conditionnel basé sur la disponibilité
        document.addEventListener('DOMContentLoaded', function() {
            const disponibleSelect = document.getElementById('disponible');
            
            function updateDisponibilite() {
                // Vous pouvez ajouter ici une logique supplémentaire si nécessaire
                console.log('Disponibilité mise à jour :', disponibleSelect.value);
            }
            
            disponibleSelect.addEventListener('change', updateDisponibilite);
            updateDisponibilite(); // Appel initial
        });
    </script>
    @endpush
</x-app-layout>
