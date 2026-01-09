<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Modifier l'utilisateur</h1>
                <p class="mt-1 text-sm text-gray-500">Modifier les informations de {{ $user->login }}</p>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6" id="userForm">
                @csrf
                @method('PUT')

                <!-- Login -->
                <div>
                    <x-input-label for="login" value="Login (Matricule) *" />
                    <x-text-input id="login" name="login" type="text" class="input-modern mt-1 {{ $errors->has('login') ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30' : '' }}" value="{{ old('login', $user->login) }}" required autofocus placeholder="{{ __('forms.placeholders.login') }}" />
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-500">{{ __('forms.hints.login') }}</p>
                </div>

                <!-- Mot de passe (optionnel) -->
                <div>
                    <x-input-label for="password" value="Nouveau mot de passe (optionnel)" />
                    <x-text-input id="password" name="password" type="password" class="input-modern mt-1 {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30' : '' }}" placeholder="{{ __('forms.placeholders.password_optional') }}" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-500">{{ __('forms.hints.password_optional') }}</p>
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <x-input-label for="password_confirmation" value="Confirmer le nouveau mot de passe" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="input-modern mt-1 {{ $errors->has('password_confirmation') ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30' : '' }}" placeholder="{{ __('forms.placeholders.password_confirmation_optional') }}" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Rôle -->
                <div>
                    <x-input-label for="role" value="Rôle *" />
                    <select id="role" name="role" class="input-modern mt-1 {{ $errors->has('role') ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30' : '' }}" required>
                        <option value="" disabled>-- Sélectionnez un rôle --</option>
                        <option value="Rlecteur" {{ old('role', $user->role) === 'Rlecteur' ? 'selected' : '' }}>Lecteur</option>
                        <option value="Rbibliothecaire" {{ old('role', $user->role) === 'Rbibliothecaire' ? 'selected' : '' }}>Bibliothécaire</option>
                        <option value="Radmin" {{ old('role', $user->role) === 'Radmin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Statut actif -->
                <div class="flex items-center">
                    <input id="actif" name="actif" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('actif', $user->actif) ? 'checked' : '' }}>
                    <label for="actif" class="ml-2 text-sm font-medium text-gray-900">Compte actif</label>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" class="px-6 py-3 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200">
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
