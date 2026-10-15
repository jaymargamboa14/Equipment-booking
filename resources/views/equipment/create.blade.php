<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Add Equipment') }}</h2>
        <p class="text-sm text-zinc-400">Register a new item into the school equipment inventory.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                <form method="POST" action="{{ route('equipment.store') }}">
                    @csrf

                    @include('equipment._form')

                    <div class="mt-6 flex items-center gap-3">
                        <x-primary-button>{{ __('Save Equipment') }}</x-primary-button>
                        <a href="{{ route('equipment.index') }}" class="text-sm font-medium text-zinc-400 hover:text-white">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

