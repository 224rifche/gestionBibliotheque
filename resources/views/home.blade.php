<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestion Bibliothèque') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-slate-900">
        <div class="min-h-screen">
            <header class="sticky top-0 z-50 bg-slate-900/90 backdrop-blur border-b border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="h-16 flex items-center justify-between">
                        <a href="/" class="flex items-center gap-2">
                            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white font-semibold">MB</div>
                            <div class="text-white font-semibold">MyBlio</div>
                        </a>

                        <nav class="hidden md:flex items-center gap-8 text-sm text-white/80">
                            <a href="#offres" class="hover:text-white">Nos offres</a>
                            <a href="#pro" class="hover:text-white">Pro</a>
                            <a href="#apropos" class="hover:text-white">À propos</a>
                            <a href="#support" class="hover:text-white">Support</a>
                        </nav>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-white/20 text-xs font-semibold uppercase tracking-widest text-white hover:border-white/40">
                                Se connecter
                            </a>
                            <a href="{{ route('catalogue') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-rose-500 text-xs font-semibold uppercase tracking-widest text-white hover:bg-rose-600">
                                Accéder
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <section class="bg-gradient-to-b from-slate-900 via-slate-800 to-rose-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
                        <div class="text-white">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs">
                                Gestion de bibliothèque
                            </div>
                            <h1 class="mt-4 text-4xl sm:text-5xl font-semibold leading-tight">
                                La 1re bibliothèque
                                participative en ligne
                            </h1>
                            <p class="mt-4 text-white/80 max-w-xl">
                                Catalogue, emprunts, retours et pénalités. Une solution simple pour gérer les livres et suivre les emprunts.
                            </p>

                            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('catalogue') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-md bg-white text-slate-900 font-semibold">
                                    Découvrir le catalogue
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-md border border-white/30 text-white font-semibold">
                                    Se connecter
                                </a>
                            </div>

                            <div class="mt-8 text-xs text-white/80">
                                <div class="font-semibold text-white">Comptes de test (seed)</div>
                                <div class="mt-1">ADMIN001 / Admin@123</div>
                                <div>BIBLIO001 / Biblio@123</div>
                                <div>ETU001 / Etudiant@123</div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute -inset-6 bg-white/10 blur-2xl rounded-full"></div>
                            <div class="relative bg-white/10 border border-white/15 rounded-2xl p-6">
                                <div class="aspect-[4/3] rounded-xl bg-white/10 flex items-center justify-center text-white/80 text-sm">
                                    Aperçu de l'application
                                </div>
                                <div class="mt-4 grid grid-cols-3 gap-3">
                                    <div class="rounded-lg bg-white/10 p-3">
                                        <div class="text-white text-sm font-semibold">Catalogue</div>
                                        <div class="text-white/70 text-xs">Recherche & filtres</div>
                                    </div>
                                    <div class="rounded-lg bg-white/10 p-3">
                                        <div class="text-white text-sm font-semibold">Emprunts</div>
                                        <div class="text-white/70 text-xs">Validation & retours</div>
                                    </div>
                                    <div class="rounded-lg bg-white/10 p-3">
                                        <div class="text-white text-sm font-semibold">Pénalités</div>
                                        <div class="text-white/70 text-xs">Retards calculés</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="apropos" class="bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-4xl font-semibold text-slate-900">Créez votre bibliothèque</h2>
                            <p class="mt-4 text-slate-600">
                                Organisez vos livres, suivez les emprunts et gardez une visibilité claire sur la disponibilité des exemplaires.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 rounded-md bg-rose-500 text-white font-semibold hover:bg-rose-600">
                                    J'y vais
                                </a>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <div class="aspect-[16/9] rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 text-sm">
                                Section démonstration
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="pro" class="bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div class="rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="aspect-[16/9] rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 text-sm">
                                Suivez vos lectures
                            </div>
                        </div>
                        <div>
                            <h2 class="text-4xl font-semibold text-slate-900">Suivez vos lectures</h2>
                            <p class="mt-4 text-slate-600">
                                Consultez l'état de vos emprunts : demandes, en cours, retours effectués et retards.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('catalogue') }}" class="inline-flex items-center px-6 py-3 rounded-md bg-rose-500 text-white font-semibold hover:bg-rose-600">
                                    Je me lance
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="offres" class="bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-4xl font-semibold text-slate-900">Gérez vos prêts et emprunts</h2>
                            <p class="mt-4 text-slate-600">
                                Les bibliothécaires valident les demandes et les retours. Les retards passent automatiquement en statut "en_retard".
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 rounded-md bg-rose-500 text-white font-semibold hover:bg-rose-600">
                                    C'est parti
                                </a>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <div class="aspect-[16/9] rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 text-sm">
                                Gestion des emprunts
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="text-center">
                        <h2 class="text-3xl font-semibold text-slate-900">LES PLUS DE MYBLIO</h2>
                        <p class="mt-2 text-slate-600">Découvrez les avantages pour optimiser votre bibliothèque.</p>
                    </div>

                    <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white border border-slate-200 rounded-2xl p-6">
                            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-semibold">G</div>
                            <div class="mt-4 font-semibold">Gestion intuitive</div>
                            <div class="mt-1 text-sm text-slate-600">Organisez, filtrez et retrouvez rapidement vos livres.</div>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-2xl p-6">
                            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-semibold">M</div>
                            <div class="mt-4 font-semibold">Multiplateforme</div>
                            <div class="mt-1 text-sm text-slate-600">Accédez facilement depuis ordinateur.</div>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-2xl p-6">
                            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-semibold">S</div>
                            <div class="mt-4 font-semibold">Sécurisé</div>
                            <div class="mt-1 text-sm text-slate-600">Données protégées et accès contrôlé par rôle.</div>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-2xl p-6">
                            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-semibold">0</div>
                            <div class="mt-4 font-semibold">Sans publicité</div>
                            <div class="mt-1 text-sm text-slate-600">Interface simple, claire et rapide.</div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="support" class="bg-slate-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="rounded-3xl bg-white/5 border border-white/10 p-8 md:p-12 flex flex-col md:flex-row gap-6 items-center justify-between">
                        <div class="text-white">
                            <div class="text-2xl font-semibold">Prêt à commencer ?</div>
                            <div class="mt-1 text-white/80">Connecte-toi pour accéder au catalogue et gérer tes emprunts.</div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 rounded-md bg-white text-slate-900 font-semibold">
                                Se connecter
                            </a>
                            <a href="{{ route('catalogue') }}" class="inline-flex items-center px-6 py-3 rounded-md bg-rose-500 text-white font-semibold hover:bg-rose-600">
                                Catalogue
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="bg-slate-950 text-white/70">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm">© {{ date('Y') }} MyBlio</div>
                        <div class="text-sm">Gestion de bibliothèque • Laravel</div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
