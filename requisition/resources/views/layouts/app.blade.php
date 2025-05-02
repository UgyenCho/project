{{-- PASTE THIS ENTIRE CODE INTO resources/views/layouts/app.blade.php --}}
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
        {{-- This loads your compiled CSS and JS assets using Vite --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        {{-- Include Livewire styles if you are using the Livewire stack --}}
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        {{-- Jetstream banner component for flash messages etc. --}}
        <x-banner />

        {{-- Main wrapper div - Background set to white --}}
        <div class="min-h-screen bg-white"> {{-- MODIFIED: Was bg-gray-100 dark:bg-gray-900 --}}

            {{-- Include the Livewire navigation menu component --}}
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                {{-- Header background can still respect dark mode if needed --}}
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{-- This displays the content passed into the 'header' slot --}}
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{-- This displays the main content of the specific view using this layout --}}
                {{ $slot }}
            </main>
        </div>

        {{-- Stack for modals, often used by Jetstream components --}}
        @stack('modals')

        {{-- Include Livewire scripts if you are using the Livewire stack --}}
        @livewireScripts
    </body>
</html>