<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Ajouter une catégorie</h1>
                <p class="mt-1 text-sm text-gray-500">Créer une nouvelle catégorie de livres</p>
            </div>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('categories.store') }}" class="space-y-6" id="categorieForm">
                @csrf
                @if(session('success'))
                    <script>
                        (function() {
                            var form = document.getElementById('categorieForm');
                            if (form) {
                                form.reset();
                            }
                        })();
                    </script>
                @endif

                <div>
                    <x-input-label for="libelle" value="Libellé de la catégorie" />
                    <x-text-input id="libelle" name="libelle" type="text" class="input-modern mt-1" value="{{ old('libelle') }}" required autofocus placeholder="Ex: Roman, Science-fiction, Histoire..." />
                    <x-input-error :messages="$errors->get('libelle')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('categories.index') }}" class="px-6 py-3 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Annuler
                    </a>
                    <x-primary-button>
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Créer la catégorie
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
