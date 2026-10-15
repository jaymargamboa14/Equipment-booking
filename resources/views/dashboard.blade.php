<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Dashboard') }}</h2>
                <p class="text-sm text-zinc-400">Welcome back, {{ Auth::user()->name }}. Here's what's happening with school equipment.</p>
            </div>
            <a href="{{ route('bookings.create') }}"
               class="inline-flex items-center justify-center rounded-md bg-olive-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-darkgreen-950/50 transition hover:bg-olive-500">
                + New Booking
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur shadow-lg shadow-black/30">
                    <p class="text-sm font-medium text-zinc-400">Equipment Types</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $stats['equipment_types'] }}</p>
                </div>
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur shadow-lg shadow-black/30">
                    <p class="text-sm font-medium text-zinc-400">Total Items</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $stats['total_items'] }}</p>
                </div>
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur shadow-lg shadow-black/30">
                    <p class="text-sm font-medium text-zinc-400">{{ Auth::user()->isAdmin() ? 'All Bookings' : 'My Bookings' }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ Auth::user()->isAdmin() ? $stats['all_bookings'] : $stats['my_bookings'] }}</p>
                </div>
                <div class="rounded-xl border border-olive-500/40 bg-darkgreen-950/40 p-5 backdrop-blur shadow-lg shadow-darkgreen-950/40">
                    <p class="text-sm font-medium text-olive-300">Upcoming Events</p>
                    <p class="mt-2 text-3xl font-bold text-olive-100">{{ $stats['upcoming'] }}</p>
                </div>
            </div>

            <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 backdrop-blur shadow-lg shadow-black/30">
                <div class="flex items-center justify-between border-b border-darkgreen-900/40 px-5 py-4">
                    <h3 class="font-semibold text-white">
                        {{ Auth::user()->isAdmin() ? 'Upcoming Bookings (all groups)' : 'My Upcoming Bookings' }}
                    </h3>
                    <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-olive-400 hover:text-olive-300">View all</a>
                </div>

                @if ($upcoming->isEmpty())
                    <div class="px-5 py-12 text-center text-sm text-zinc-400">
                        No upcoming bookings yet.
                        <a href="{{ route('bookings.create') }}" class="font-medium text-olive-400 hover:text-olive-300">Create the first one.</a>
                    </div>
                @else
                    <ul role="list" class="divide-y divide-darkgreen-900/30">
                        @foreach ($upcoming as $booking)
                            <li class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('bookings.show', $booking) }}" class="truncate font-medium text-white hover:text-olive-300">
                                            {{ $booking->event_name }}
                                        </a>
                                        @include('partials.status-badge', ['status' => $booking->status])
                                    </div>
                                    <p class="mt-1 text-sm text-zinc-400">
                                        {{ $booking->event_date->format('D, M j, Y') }} &middot;
                                        {{ \Illuminate\Support\Str::substr($booking->start_time, 0, 5) }}–{{ \Illuminate\Support\Str::substr($booking->end_time, 0, 5) }}
                                        @if ($booking->venue) &middot; {{ $booking->venue }} @endif
                                        @if (Auth::user()->isAdmin()) &middot; {{ $booking->user->name }} @endif
                                    </p>
                                </div>
                                <div class="text-sm text-zinc-500">
                                    {{ $booking->items->sum('quantity') }} item(s) &middot; {{ $booking->items->count() }} type(s)
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <a href="{{ route('equipment.index') }}" class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur transition hover:border-olive-600/60 hover:bg-zinc-900">
                    <p class="font-semibold text-white">Browse Equipment</p>
                    <p class="mt-1 text-sm text-zinc-400">See chairs, tables, sound systems and projectors.</p>
                </a>
                <a href="{{ route('bookings.index') }}" class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur transition hover:border-olive-600/60 hover:bg-zinc-900">
                    <p class="font-semibold text-white">My Bookings</p>
                    <p class="mt-1 text-sm text-zinc-400">Track and cancel your reservations.</p>
                </a>
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('equipment.create') }}" class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur transition hover:border-olive-600/60 hover:bg-zinc-900">
                        <p class="font-semibold text-white">Add Equipment</p>
                        <p class="mt-1 text-sm text-zinc-400">Register new items into the inventory.</p>
                    </a>
                @else
                    <a href="{{ route('bookings.create') }}" class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur transition hover:border-olive-600/60 hover:bg-zinc-900">
                        <p class="font-semibold text-white">Reserve Equipment</p>
                        <p class="mt-1 text-sm text-zinc-400">Book items for your next school event.</p>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

