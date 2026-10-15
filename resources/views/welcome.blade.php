<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-black text-white">
        <div class="min-h-screen bg-gradient-to-b from-black via-emerald-950/40 to-emerald-950/30">
            <header class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
                <div class="flex items-center gap-2">
                    <x-application-logo class="h-9 w-auto fill-current text-olive-400" />
                    <span class="font-semibold text-white">{{ config('app.name') }}</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-md bg-olive-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-emerald-950/40 hover:bg-olive-500">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-emerald-100/80 hover:text-white">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-olive-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-emerald-950/40 hover:bg-olive-500">Register</a>
                    @endauth
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-6 pt-12 pb-20">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="inline-flex items-center rounded-full border border-emerald-800/60 bg-emerald-950/40 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-olive-200">School Events</span>
                    <h1 class="mt-6 text-4xl font-bold tracking-tight text-white sm:text-5xl">School Event Equipment Booking System</h1>
                    <p class="mt-5 text-lg text-emerald-100/80">
                        Reserve chairs, tables, sound systems and projectors for school events — with automatic conflict detection
                        so two groups can never book the same items at the same time.
                    </p>
                    <div class="mt-8 flex items-center justify-center gap-3">
                        @auth
                            <a href="{{ route('bookings.create') }}" class="rounded-md bg-olive-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-emerald-950/40 hover:bg-olive-500">Book Equipment</a>
                            <a href="{{ route('equipment.index') }}" class="rounded-md border border-emerald-900/60 bg-emerald-950/30 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-950/50">Browse Equipment</a>
                        @else
                            <a href="{{ route('register') }}" class="rounded-md bg-olive-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-emerald-950/40 hover:bg-olive-500">Get Started</a>
                            <a href="{{ route('equipment.index') }}" class="rounded-md border border-emerald-900/60 bg-emerald-950/30 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-950/50">Browse Equipment</a>
                        @endauth
                    </div>
                </div>

                <div class="mx-auto mt-16 grid max-w-4xl gap-5 sm:grid-cols-3">
                    <div class="rounded-2xl border border-emerald-900/60 bg-emerald-950/30 p-6 backdrop-blur">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-olive-500/15 text-olive-200 ring-1 ring-olive-500/40">1</div>
                        <h3 class="mt-4 font-semibold text-white">Browse equipment</h3>
                        <p class="mt-1 text-sm text-emerald-100/80">See chairs, tables, sound systems and projectors with live stock levels.</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-900/60 bg-emerald-950/30 p-6 backdrop-blur">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-olive-500/15 text-olive-200 ring-1 ring-olive-500/40">2</div>
                        <h3 class="mt-4 font-semibold text-white">Book date &amp; time</h3>
                        <p class="mt-1 text-sm text-emerald-100/80">Pick your event schedule and choose how many of each item you need.</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-900/60 bg-emerald-950/30 p-6 backdrop-blur">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-olive-500/15 text-olive-200 ring-1 ring-olive-500/40">3</div>
                        <h3 class="mt-4 font-semibold text-white">No double booking</h3>
                        <p class="mt-1 text-sm text-emerald-100/80">Overlapping reservations are rejected automatically, keeping every group's items safe.</p>
                    </div>
                </div>
            </main>

            <footer class="border-t border-emerald-900/60 bg-emerald-950/20 backdrop-blur">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm text-emerald-100/80">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
                    <div class="text-sm text-emerald-100/60">School Event Equipment Booking System</div>
                </div>
            </footer>
        </div>
    </body>
</html>
