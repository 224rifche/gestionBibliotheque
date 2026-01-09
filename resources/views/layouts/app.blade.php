<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <div class="sm:pl-64 sm:pt-16">
                <!-- Page Heading -->
                @isset($header)
                    <div class="hidden">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Page Content -->
                <main class="p-8 animate-fade-in">
                    <div class="max-w-7xl mx-auto">
                        <!-- Notifications globales -->
                        @if(session('success'))
                            <x-notification type="success" :message="session('success')" />
                        @endif
                        @if(session('error'))
                            <x-notification type="error" :message="session('error')" />
                        @endif
                        @if(session('warning'))
                            <x-notification type="warning" :message="session('warning')" />
                        @endif
                        @if(session('info'))
                            <x-notification type="info" :message="session('info')" />
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
