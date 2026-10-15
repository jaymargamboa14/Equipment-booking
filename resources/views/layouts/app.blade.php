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
    <body class="font-sans antialiased bg-black text-white">
        <div class="min-h-screen bg-gradient-to-b from-black via-emerald-950/30 to-emerald-950/20">
            @include('layouts.navigation')

            @include('partials.flash')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-emerald-900/60 bg-emerald-950/30 backdrop-blur">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class="border-t border-emerald-900/60 bg-emerald-950/20 backdrop-blur mt-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm text-emerald-100/80">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
                    <div class="text-sm text-emerald-100/60">School Event Equipment Booking System</div>
                </div>
            </footer>
        </div>
    </body>
</html>
