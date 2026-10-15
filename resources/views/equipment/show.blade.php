<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('equipment.index') }}" class="text-sm text-olive-400 hover:text-olive-300">&larr; Back to equipment</a>
                <h2 class="mt-1 font-semibold text-xl text-white leading-tight">{{ $equipment->name }}</h2>
            </div>
            <div class="flex gap-2">
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('equipment.edit', $equipment) }}"
                       class="inline-flex items-center rounded-md border border-darkgreen-900/60 bg-zinc-900 px-4 py-2 text-sm font-semibold text-zinc-200 shadow-sm hover:bg-zinc-800 hover:text-white">
                        Edit
                    </a>
                @endif
                @if ($equipment->is_bookable)
                    <a href="{{ route('bookings.create', ['equipment' => $equipment->id]) }}"
                       class="inline-flex items-center rounded-md bg-olive-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-darkgreen-950/50 transition hover:bg-olive-500">
                        Book this equipment
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold uppercase tracking-wide text-olive-400">{{ $equipment->category_label }}</span>
                        @include('partials.status-badge', ['status' => $equipment->status])
                    </div>
                    <p class="mt-4 text-zinc-400">{{ $equipment->description ?: 'No description provided for this item.' }}</p>

                    <dl class="mt-6 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                        <div class="rounded-lg border border-darkgreen-900/30 bg-zinc-950/40 p-4">
                            <dt class="text-zinc-400">In stock</dt>
                            <dd class="mt-1 text-2xl font-bold text-white">{{ number_format($equipment->quantity) }}</dd>
                        </div>
                        <div class="rounded-lg border border-darkgreen-900/30 bg-zinc-950/40 p-4">
                            <dt class="text-zinc-400">Location</dt>
                            <dd class="mt-1 font-semibold text-white">{{ $equipment->location ?: 'Not set' }}</dd>
                        </div>
                        <div class="rounded-lg border border-darkgreen-900/30 bg-zinc-950/40 p-4">
                            <dt class="text-zinc-400">Bookable</dt>
                            <dd class="mt-1 font-semibold text-white">{{ $equipment->is_bookable ? 'Yes' : 'No' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                <h3 class="font-semibold text-white">Upcoming reservations</h3>
                @if ($upcoming->isEmpty())
                    <p class="mt-4 text-sm text-zinc-400">Nothing booked yet. This item is free to reserve.</p>
                @else
                    <ul role="list" class="mt-4 space-y-3">
                        @foreach ($upcoming as $booking)
                            <li class="rounded-lg border border-darkgreen-900/30 bg-zinc-950/40 p-3">
                                <p class="text-sm font-medium text-zinc-200">{{ $booking->event_name }}</p>
                                <p class="text-xs text-zinc-500">
                                    {{ $booking->event_date->format('M j, Y') }} &middot;
                                    {{ \Illuminate\Support\Str::substr($booking->start_time, 0, 5) }}–{{ \Illuminate\Support\Str::substr($booking->end_time, 0, 5) }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

