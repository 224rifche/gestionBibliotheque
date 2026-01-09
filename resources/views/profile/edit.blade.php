<x-app-layout>
    <div class="space-y-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Mon profil</h1>
            <p class="mt-1 text-sm text-gray-500">Modifier votre login et votre mot de passe</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Login -->
                <div>
                    <x-input-label for="login" value="Login (Matricule)" />
                    <x-text-input id="login" name="login" type="text" class="input-modern mt-1" value="{{ old('login', $user->login) }}" required autofocus placeholder="Ex: ETU202401" />
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-500">Uniquement des lettres et des chiffres</p>
                </div>

                <!-- Mot de passe actuel -->
                <div>
                    <x-input-label for="current_password" value="Mot de passe actuel" />
                    <x-text-input id="current_password" name="current_password" type="password" class="input-modern mt-1" required />
                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <x-input-label for="password" value="Nouveau mot de passe" />
                    <x-text-input id="password" name="password" type="password" class="input-modern mt-1" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères avec lettres, chiffres et caractères spéciaux</p>
                </div>

                <!-- Confirmation nouveau mot de passe -->
                <div>
                    <x-input-label for="password_confirmation" value="Confirmer le nouveau mot de passe" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="input-modern mt-1" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Informations supplémentaires -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Rôle :</span>
                            <span class="ml-2 font-medium text-gray-900">
                                @php
                                    $roleLabels = [
                                        'Radmin' => 'Administrateur',
                                        'Rbibliothecaire' => 'Bibliothécaire',
                                        'Rlecteur' => 'Lecteur',
                                    ];
                                @endphp
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Compte créé le :</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
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
