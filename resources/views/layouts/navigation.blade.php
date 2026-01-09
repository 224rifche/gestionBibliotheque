<nav x-data="{ open: false }" class="relative">
    @php
        $user = Auth::user();
        $userInitial = $user?->login ? strtoupper(substr($user->login, 0, 1)) : '?';
    @endphp
    <!-- Mobile header -->
    <div class="sm:hidden sticky top-0 z-40">
        <div class="h-16 px-4 flex items-center justify-between bg-white border-b border-gray-200">
            <button @click="open = true" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:bg-gray-100">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ in_array($user?->role, ['Rbibliothecaire', 'Radmin'], true) ? route('dashboard') : route('catalogue') }}" class="flex items-center gap-2">
                <x-application-logo class="block h-8 w-auto fill-current text-gray-900" />
                <span class="text-lg font-semibold">Bibliothèque</span>
            </a>

            <!-- Profil mobile -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2">
                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                        {{ $userInitial }}
                    </div>
                </button>

                <!-- Menu déroulant mobile -->
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                    <div class="px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-semibold text-gray-900">{{ $user?->login }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            @php
                                $roleLabels = [
                                    'Radmin' => 'Admin',
                                    'Rbibliothecaire' => 'Bibliothécaire',
                                    'Rlecteur' => 'Lecteur',
                                ];
                            @endphp
                            {{ $roleLabels[$user?->role] ?? $user?->role }}
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Mon profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-200 text-left">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div x-show="open" x-cloak class="fixed inset-0 z-50 sm:hidden">
        <!-- Overlay -->
        <div @click="open = false" class="absolute inset-0 bg-slate-900/40"></div>

        <!-- Panneau latéral -->
        <div class="absolute inset-y-0 left-0 w-72 bg-gradient-to-b from-sky-400 to-sky-600 text-white shadow-2xl">
            <!-- En-tête -->
            <div class="h-16 px-5 flex items-center justify-between border-b border-white/20">
                <a href="{{ $user?->role === 'Rlecteur' ? route('catalogue') : route('dashboard') }}" class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto fill-current text-white" />
                    <span class="text-base font-semibold tracking-wide">Bibliothèque</span>
                </a>
                <button @click="open = false" class="p-2 rounded-md hover:bg-white/10">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4 overflow-y-auto h-[calc(100vh-4rem)]">
                <!-- Menu principal -->
                <div class="space-y-1">
                    @if(in_array($user?->role, ['Rbibliothecaire', 'Radmin'], true))
                        <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z' /></svg>">
                            Tableau de bord
                        </x-sidebar-link>
                    @endif

                    <x-sidebar-link href="{{ route('catalogue') }}" :active="request()->routeIs('catalogue')"
                        icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 01-6 2.292m0-14.25v14.25' /></svg>">
                        Catalogue
                    </x-sidebar-link>
                </div>

                <!-- Menu Lecteur -->
                @if($user?->role === 'Rlecteur')
                <div class="mt-6">
                    <div class="text-xs uppercase tracking-wider text-white/70 px-3 mb-2">Mon Espace</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('emprunts.mes') }}" :active="request()->routeIs('emprunts.mes')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155' /></svg>">
                            Mes emprunts
                        </x-sidebar-link>
                        <x-sidebar-link href="#"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z' /></svg>">
                            Favoris
                        </x-sidebar-link>
                        <x-sidebar-link href="#"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>">
                            Historique
                        </x-sidebar-link>
                    </div>
                </div>
                @endif

                <!-- Menu Bibliothécaire & Admin -->
                @if(in_array($user?->role, ['Rbibliothecaire', 'Radmin'], true))
                <div class="mt-6">
                    <div class="text-xs uppercase tracking-wider text-white/70 px-3 mb-2">Gestion des prêts</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('emprunts.demandes') }}" :active="request()->routeIs('emprunts.demandes')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z' /></svg>">
                            Demandes en attente
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('emprunts.en_cours') }}" :active="request()->routeIs('emprunts.en_cours')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>">
                            Prêts en cours
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('emprunts.retards') }}" :active="request()->routeIs('emprunts.retards')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' /></svg>">
                            Retards
                        </x-sidebar-link>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-xs uppercase tracking-wider text-white/70 px-3 mb-2">Gestion des livres</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('livres.index') }}" :active="request()->routeIs('livres.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 01-6 2.292m0-14.25v14.25' /></svg>">
                            Livres
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' /></svg>">
                            Catégories
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('auteurs.index') }}" :active="request()->routeIs('auteurs.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' /></svg>">
                            Auteurs
                        </x-sidebar-link>
                    </div>
                </div>
                @endif

                <!-- Menu Admin uniquement -->
                @if($user?->role === 'Radmin')
                <div class="mt-6">
                    <div class="text-xs uppercase tracking-wider text-white/70 px-3 mb-2">Administration</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z' /></svg>">
                            Utilisateurs
                        </x-sidebar-link>
                    </div>
                </div>
                @endif

                <!-- Profil retiré de la sidebar mobile, maintenant dans le header -->
            </div>
        </div>
    </div>

    <!-- Barre latérale Desktop -->
    <!-- Rail gauche + Topbar (Desktop) -->
    <aside class="hidden sm:flex sm:fixed sm:inset-y-0 sm:left-0 sm:w-64 sm:flex-col bg-[#2563eb] text-white shadow-2xl z-50">
        <div class="h-16 px-6 flex items-center gap-3">
            <a href="{{ in_array($user?->role, ['Rbibliothecaire', 'Radmin'], true) ? route('dashboard') : route('catalogue') }}" class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur-sm hover:bg-white/30 transition-colors duration-200">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
            </a>
            <div class="min-w-0">
                <div class="text-base font-semibold leading-tight text-white">Bibliothèque</div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-4">
            <div class="space-y-1">
                @if($user?->role !== 'Rlecteur')
                <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                    icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z' /></svg>">
                    Tableau de bord
                @endif
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('catalogue') }}" :active="request()->routeIs('catalogue')"
                    icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 01-6 2.292m0-14.25v14.25' /></svg>">
                    Catalogue
                </x-sidebar-link>
            </div>

            @if($user?->role === 'Rlecteur')
                <div class="mt-8">
                    <div class="text-xs uppercase tracking-wider text-white/50 px-4 mb-3 font-semibold">Mon Espace</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('emprunts.mes') }}" :active="request()->routeIs('emprunts.mes')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155' /></svg>">
                            Mes emprunts
                        </x-sidebar-link>
                    </div>
                </div>
            @endif

            @if(in_array($user?->role, ['Rbibliothecaire', 'Radmin'], true))
                <div class="mt-8">
                    <div class="text-xs uppercase tracking-wider text-white/50 px-4 mb-3 font-semibold">Gestion des prêts</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('emprunts.demandes') }}" :active="request()->routeIs('emprunts.demandes')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z' /></svg>">
                            Demandes en attente
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('emprunts.en_cours') }}" :active="request()->routeIs('emprunts.en_cours')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>">
                            Prêts en cours
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('emprunts.retards') }}" :active="request()->routeIs('emprunts.retards')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' /></svg>">
                            Retards
                        </x-sidebar-link>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="text-xs uppercase tracking-wider text-white/50 px-4 mb-3 font-semibold">Gestion des livres</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('livres.index') }}" :active="request()->routeIs('livres.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 01-6 2.292m0-14.25v14.25' /></svg>">
                            Livres
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' /></svg>">
                            Catégories
                        </x-sidebar-link>
                        <x-sidebar-link href="{{ route('auteurs.index') }}" :active="request()->routeIs('auteurs.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' /></svg>">
                            Auteurs
                        </x-sidebar-link>
                    </div>
                </div>
            @endif

            @if($user?->role === 'Radmin')
                <div class="mt-8">
                    <div class="text-xs uppercase tracking-wider text-white/50 px-4 mb-3 font-semibold">Administration</div>
                    <div class="space-y-1">
                        <x-sidebar-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                            icon="<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z' /></svg>">
                            Utilisateurs
                        </x-sidebar-link>
                    </div>
                </div>
            @endif
        </div>

        <!-- Profil retiré de la sidebar desktop, maintenant dans le header -->
    </aside>

    <header class="hidden sm:flex sm:fixed sm:top-0 sm:left-64 sm:right-0 sm:h-16 sm:items-center bg-white border-b border-gray-200 z-40">
        <div class="w-full px-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-semibold text-gray-900">
                    @if(request()->routeIs('users.*'))
                        {{ request()->routeIs('users.create') ? 'Ajouter un utilisateur' : (request()->routeIs('users.edit') ? 'Modifier un utilisateur' : 'Gestion des utilisateurs') }}
                    @elseif(request()->routeIs('emprunts.demandes'))
                        Demandes d'emprunt
                    @elseif(request()->routeIs('emprunts.en_cours'))
                        Prêts en cours
                    @elseif(request()->routeIs('emprunts.retards'))
                        Retards
                    @elseif(request()->routeIs('emprunts.mes'))
                        Mes emprunts
                    @elseif(request()->routeIs('catalogue'))
                        Catalogue
                    @elseif(request()->routeIs('livres.*'))
                        {{ request()->routeIs('livres.create') ? 'Ajouter un livre' : (request()->routeIs('livres.edit') ? 'Modifier un livre' : 'Gestion des livres') }}
                    @elseif(request()->routeIs('categories.*'))
                        {{ request()->routeIs('categories.create') ? 'Ajouter une catégorie' : (request()->routeIs('categories.edit') ? 'Modifier une catégorie' : 'Gestion des catégories') }}
                    @elseif(request()->routeIs('auteurs.*'))
                        {{ request()->routeIs('auteurs.create') ? 'Ajouter un auteur' : (request()->routeIs('auteurs.edit') ? 'Modifier un auteur' : 'Gestion des auteurs') }}
                    @elseif(request()->routeIs('profile.*'))
                        Mon profil
                    @else
                        Dashboard
                    @endif
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <!-- Profil utilisateur -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                            {{ $userInitial }}
                        </div>
                        <div class="hidden lg:block text-left">
                            <div class="text-sm font-semibold text-gray-900">{{ $user?->login }}</div>
                            <div class="text-xs text-gray-500">
                                @php
                                    $roleLabels = [
                                        'Radmin' => 'Admin',
                                        'Rbibliothecaire' => 'Bibliothécaire',
                                        'Rlecteur' => 'Lecteur',
                                    ];
                                @endphp
                                {{ $roleLabels[$user?->role] ?? $user?->role }}
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Menu déroulant -->
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Mon profil</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-200 text-left">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
</nav>
