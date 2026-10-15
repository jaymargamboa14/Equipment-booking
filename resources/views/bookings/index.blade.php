<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Bookings') }}</h2>
                <p class="text-sm text-zinc-400">{{ Auth::user()->isAdmin() ? 'All equipment reservations across the school.' : 'Your equipment reservations.' }}</p>
            </div>
            <a href="{{ route('bookings.create') }}"
               class="inline-flex items-center justify-center rounded-md bg-olive-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-darkgreen-950/50 transition hover:bg-olive-500">
                + New Booking
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" action="{{ route('bookings.index') }}" class="flex flex-col gap-3 rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-4 backdrop-blur shadow-lg shadow-black/30 sm:flex-row sm:items-center">
                <select name="status" class="block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 shadow-sm focus:border-olive-500 focus:ring-olive-500 sm:w-48">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="scope" class="block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 shadow-sm focus:border-olive-500 focus:ring-olive-500 sm:w-48">
                    <option value="">All dates</option>
                    <option value="upcoming" @selected(request('scope') === 'upcoming')>Upcoming</option>
                    <option value="past" @selected(request('scope') === 'past')>Past</option>
                </select>
                <div class="flex gap-2">
                    <x-primary-button>Filter</x-primary-button>
                    @if (request()->filled('status') || request()->filled('scope'))
                        <a href="{{ route('bookings.index') }}" class="inline-flex items-center rounded-md border border-darkgreen-900/60 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-zinc-300 hover:bg-zinc-800 hover:text-white">Reset</a>
                    @endif
                </div>
            </form>

            @if ($bookings->isEmpty())
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-12 text-center backdrop-blur">
                    <p class="text-zinc-400">No bookings found.</p>
                    <a href="{{ route('bookings.create') }}" class="mt-2 inline-block text-sm font-medium text-olive-400 hover:text-olive-300">Create the first booking.</a>
                </div>
            @else
                <div class="overflow-hidden rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 backdrop-blur shadow-lg shadow-black/30">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-darkgreen-900/30">
                            <thead class="bg-darkgreen-950/40">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Event</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Schedule</th>
                                    @if (Auth::user()->isAdmin())
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Booked by</th>
                                    @endif
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Items</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Status</th>
                                    <th class="px-5 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-darkgreen-900/30">
                                @foreach ($bookings as $booking)
                                    <tr class="transition hover:bg-darkgreen-950/20">
                                        <td class="px-5 py-4">
                                            <a href="{{ route('bookings.show', $booking) }}" class="font-medium text-white hover:text-olive-300">{{ $booking->event_name }}</a>
                                            @if ($booking->venue)
                                                <p class="text-xs text-zinc-500">{{ $booking->venue }}</p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-sm text-zinc-300">
                                            {{ $booking->event_date->format('M j, Y') }}<br>
                                            <span class="text-xs text-zinc-500">{{ \Illuminate\Support\Str::substr($booking->start_time, 0, 5) }}–{{ \Illuminate\Support\Str::substr($booking->end_time, 0, 5) }}</span>
                                        </td>
                                        @if (Auth::user()->isAdmin())
                                            <td class="px-5 py-4 text-sm text-zinc-300">{{ $booking->user->name }}</td>
                                        @endif
                                        <td class="px-5 py-4 text-sm text-zinc-300">
                                            {{ $booking->items->count() }} type(s)<br>
                                            <span class="text-xs text-zinc-500">{{ $booking->items->sum('quantity') }} total item(s)</span>
                                        </td>
                                        <td class="px-5 py-4">
                                            @include('partials.status-badge', ['status' => $booking->status])
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('bookings.show', $booking) }}" class="text-sm font-medium text-olive-400 hover:text-olive-300">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>{{ $bookings->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

