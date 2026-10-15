<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ __('New Equipment Booking') }}</h2>
        <p class="text-sm text-zinc-400">The system prevents two groups from reserving the same items for the same date and time.</p>
    </x-slot>

    @php
        $equipmentOptions = $equipment->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'category' => $item->category_label,
            'quantity' => $item->quantity,
        ])->values();
    @endphp

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any() && $errors->get('items'))
                <div id="items-errors" class="mb-4 rounded-lg border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-300">
                    <p class="font-semibold">Booking could not be completed:</p>
                    <ul class="mt-1 list-inside list-disc space-y-1">
                        @foreach ($errors->get('items') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="booking-form" method="POST" action="{{ route('bookings.store') }}"
                  class="space-y-6"
                  data-availability-url="{{ route('availability') }}"
                  data-equipment='@json($equipmentOptions)'
                  data-old-items='@json(old("items", []))'
                  data-preselect="{{ request('equipment') }}">
                @csrf

                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                    <h3 class="font-semibold text-white">Event details</h3>

                    <div class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-input-label for="event_name" :value="__('Event name')" />
                            <x-text-input id="event_name" name="event_name" type="text" class="mt-1 block w-full" :value="old('event_name')" required placeholder="e.g. Foundation Day Program" />
                            <x-input-error :messages="$errors->get('event_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="venue" :value="__('Venue')" />
                            <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full" :value="old('venue')" placeholder="e.g. School Gymnasium" />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="event_date" :value="__('Event date')" />
                            <x-text-input id="event_date" name="event_date" type="date" class="mt-1 block w-full" :value="old('event_date')" required min="{{ now()->toDateString() }}" />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="start_time" :value="__('Start time')" />
                            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time', '08:00')" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="end_time" :value="__('End time')" />
                            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time', '17:00')" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="event_description" :value="__('Event description')" />
                            <textarea id="event_description" name="event_description" rows="2" class="mt-1 block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 placeholder-zinc-500 shadow-sm focus:border-olive-500 focus:ring-olive-500" placeholder="Short description of the event">{{ old('event_description') }}</textarea>
                            <x-input-error :messages="$errors->get('event_description')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-white">Equipment to reserve</h3>
                            <p class="text-sm text-zinc-400">Availability is checked against other bookings for the selected date and time.</p>
                        </div>
                        <button type="button" id="add-item"
                                class="inline-flex items-center rounded-md border border-olive-500/70 px-3 py-1.5 text-sm font-semibold text-olive-300 transition hover:bg-darkgreen-950/40">
                            + Add item
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('items.0.equipment_id')" class="mt-2" />

                    <div id="items-container" class="mt-4 space-y-3"></div>

                    <template id="item-row-template">
                        <div class="item-row flex flex-col gap-2 rounded-lg border border-darkgreen-900/40 bg-zinc-950/50 p-3 sm:flex-row sm:items-center">
                            <select class="item-select block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 shadow-sm focus:border-olive-500 focus:ring-olive-500 sm:flex-1"></select>
                            <input type="number" min="1" value="1" class="item-quantity block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 shadow-sm focus:border-olive-500 focus:ring-olive-500 sm:w-24" placeholder="Qty">
                            <div class="flex items-center justify-between gap-3 sm:justify-end sm:flex-1">
                                <span class="availability-text text-xs text-zinc-500"></span>
                                <button type="button" class="remove-row shrink-0 text-sm font-medium text-rose-400 hover:text-rose-300">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-zinc-400 hover:text-white">Cancel</a>
                    <x-primary-button>{{ __('Confirm Booking') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

