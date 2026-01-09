<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Ajouter un auteur</h1>
                <p class="mt-1 text-sm text-gray-500">Créer un nouvel auteur</p>
            </div>
            <a href="{{ route('auteurs.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('auteurs.store') }}" class="space-y-6" id="auteurForm">
                @csrf
                @if(session('success'))
                    <script>
                        (function() {
                            var form = document.getElementById('auteurForm');
                            if (form) {
                                form.reset();
                            }
                        })();
                    </script>
                @endif

                <div>
                    <x-input-label for="nom" value="Nom de l'auteur" />
                    <x-text-input id="nom" name="nom" type="text" class="input-modern mt-1" value="{{ old('nom') }}" required autofocus placeholder="Ex: Victor Hugo, Jules Verne..." />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('auteurs.index') }}" class="px-6 py-3 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Annuler
                    </a>
                    <x-primary-button>
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Créer l'auteur
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
