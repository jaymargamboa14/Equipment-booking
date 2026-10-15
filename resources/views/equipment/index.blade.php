<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Equipment') }}</h2>
                <p class="text-sm text-zinc-400">Browse available school equipment and their stock levels.</p>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('equipment.create') }}"
                   class="inline-flex items-center justify-center rounded-md bg-olive-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-darkgreen-950/50 transition hover:bg-olive-500">
                    + Add Equipment
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" action="{{ route('equipment.index') }}" class="flex flex-col gap-3 rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-4 backdrop-blur shadow-lg shadow-black/30 sm:flex-row sm:items-center">
                <div class="sm:flex-1">
                    <x-text-input id="equipment-search" name="search" type="text" class="block w-full" :value="request('search')" placeholder="Search equipment..." />
                </div>
                <div>
                    <select name="category" class="block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 shadow-sm focus:border-olive-500 focus:ring-olive-500 sm:w-48">
                        <option value="">All categories</option>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <x-primary-button>Filter</x-primary-button>
                    @if (request()->filled('search') || request()->filled('category'))
                        <a href="{{ route('equipment.index') }}" class="inline-flex items-center rounded-md border border-darkgreen-900/60 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-zinc-300 hover:bg-zinc-800 hover:text-white">Reset</a>
                    @endif
                </div>
            </form>

            @if ($equipment->isEmpty())
                <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-12 text-center backdrop-blur">
                    <p class="text-zinc-400">No equipment found.</p>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($equipment as $item)
                        <div class="flex flex-col rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-5 backdrop-blur shadow-lg shadow-black/30 transition hover:border-olive-600/60">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <a href="{{ route('equipment.show', $item) }}" class="font-semibold text-white hover:text-olive-300">
                                        {{ $item->name }}
                                    </a>
                                    <p class="text-xs font-medium uppercase tracking-wide text-olive-400">{{ $item->category_label }}</p>
                                </div>
                                @include('partials.status-badge', ['status' => $item->status])
                            </div>

                            <p class="mt-3 flex-1 text-sm text-zinc-400">{{ $item->description ?: 'No description provided.' }}</p>

                            <div class="mt-4 flex items-center justify-between border-t border-darkgreen-900/30 pt-4">
                                <div class="text-sm">
                                    <span class="font-semibold text-white">{{ number_format($item->quantity) }}</span>
                                    <span class="text-zinc-400">in stock</span>
                                    @if ($item->location)
                                        <p class="text-xs text-zinc-500">Located: {{ $item->location }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    @if (Auth::user()->isAdmin())
                                        <a href="{{ route('equipment.edit', $item) }}" class="text-sm font-medium text-olive-400 hover:text-olive-300">Edit</a>
                                    @endif
                                    <a href="{{ route('equipment.show', $item) }}" class="text-sm font-medium text-zinc-400 hover:text-white">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>{{ $equipment->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

