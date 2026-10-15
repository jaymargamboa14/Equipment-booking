<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('bookings.index') }}" class="text-sm text-olive-400 hover:text-olive-300">&larr; Back to bookings</a>
                <h2 class="mt-1 flex items-center gap-3 font-semibold text-xl text-white leading-tight">
                    {{ $booking->event_name }}
                    @include('partials.status-badge', ['status' => $booking->status])
                </h2>
            </div>

            @if ($booking->status !== 'cancelled' && $booking->event_date->isFuture())
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                      onsubmit="return confirm('Cancel this booking? The reserved items will become available to others.');">
                    @csrf
                    @method('PATCH')
                    <x-danger-button>{{ __('Cancel Booking') }}</x-danger-button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                    <h3 class="font-semibold text-white">Event details</h3>
                    <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="text-zinc-400">Date</dt>
                            <dd class="mt-0.5 font-medium text-white">{{ $booking->event_date->format('l, F j, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-zinc-400">Time</dt>
                            <dd class="mt-0.5 font-medium text-white">{{ \Illuminate\Support\Str::substr($booking->start_time, 0, 5) }} – {{ \Illuminate\Support\Str::substr($booking->end_time, 0, 5) }}</dd>
                        </div>
                        <div>
                            <dt class="text-zinc-400">Venue</dt>
                            <dd class="mt-0.5 font-medium text-white">{{ $booking->venue ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-zinc-400">Booked by</dt>
                            <dd class="mt-0.5 font-medium text-white">{{ $booking->user->name }}</dd>
                        </div>
                        @if ($booking->event_description)
                            <div class="sm:col-span-2">
                                <dt class="text-zinc-400">Description</dt>
                                <dd class="mt-0.5 text-zinc-300">{{ $booking->event_description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 backdrop-blur shadow-lg shadow-black/30">
                    <div class="border-b border-darkgreen-900/30 px-6 py-4">
                        <h3 class="font-semibold text-white">Reserved equipment</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-darkgreen-900/30">
                            <thead class="bg-darkgreen-950/40">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-zinc-400">Quantity</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-darkgreen-900/30">
                                @foreach ($booking->items as $line)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-white">
                                            @if ($line->equipment)
                                                <a href="{{ route('equipment.show', $line->equipment) }}" class="hover:text-olive-300">{{ $line->equipment->name }}</a>
                                            @else
                                                <span class="text-zinc-500">Item removed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-zinc-300">{{ $line->equipment?->category_label ?? '—' }}</td>
                                        <td class="px-6 py-4 text-right text-sm font-semibold text-white">{{ number_format($line->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-darkgreen-950/40">
                                <tr>
                                    <td class="px-6 py-3 text-sm font-semibold text-zinc-200" colspan="2">Total items</td>
                                    <td class="px-6 py-3 text-right text-sm font-bold text-white">{{ number_format($booking->items->sum('quantity')) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                    <h3 class="font-semibold text-white">Reference</h3>
                    <p class="mt-3 text-sm text-zinc-400">Booking ID</p>
                    <p class="font-mono text-lg font-semibold text-white">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="mt-3 text-sm text-zinc-400">Created</p>
                    <p class="text-sm font-medium text-zinc-200">{{ $booking->created_at->format('M j, Y g:i A') }}</p>
                </div>

                @if ($booking->notes)
                    <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                        <h3 class="font-semibold text-white">Notes</h3>
                        <p class="mt-2 text-sm text-zinc-300">{{ $booking->notes }}</p>
                    </div>
                @endif

                <div class="rounded-xl border border-olive-500/40 bg-darkgreen-950/40 p-6 backdrop-blur shadow-lg shadow-darkgreen-950/40">
                    <h3 class="font-semibold text-olive-100">Conflict protection</h3>
                    <p class="mt-2 text-sm text-olive-300">
                        These items are locked to this group for the selected date and time. Any other group requesting them for an overlapping schedule will be blocked automatically.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

