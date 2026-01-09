<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Modifier l'auteur</h1>
                <p class="mt-1 text-sm text-gray-500">Modifier les informations de l'auteur</p>
            </div>
            <a href="{{ route('auteurs.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('auteurs.update', $auteur) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="nom" value="Nom de l'auteur" />
                    <x-text-input id="nom" name="nom" type="text" class="input-modern mt-1" value="{{ old('nom', $auteur->nom) }}" required autofocus />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('auteurs.index') }}" class="px-6 py-3 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Annuler
                    </a>
                    <x-primary-button>
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer les modifications
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
