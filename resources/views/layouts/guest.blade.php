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
    <body class="font-sans text-white antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-black via-emerald-950/40 to-emerald-950/30">
            <div>
                <a href="/" class="flex flex-col items-center gap-3">
                    <x-application-logo class="w-16 h-16 fill-current text-olive-400" />
                    <span class="text-sm font-semibold uppercase tracking-widest text-olive-200">{{ config('app.name') }}</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-emerald-950/30 border border-emerald-900/60 shadow-xl shadow-emerald-950/40 backdrop-blur overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <footer class="mt-12 w-full">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-center gap-2">
                    <div class="text-sm text-emerald-100/80">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
                </div>
            </footer>
        </div>
    </body>
</html>
