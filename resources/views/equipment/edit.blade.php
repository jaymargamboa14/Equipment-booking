<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Edit Equipment') }}</h2>
        <p class="text-sm text-zinc-400">Update the details for <span class="font-medium text-zinc-200">{{ $equipment->name }}</span>.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border border-darkgreen-900/40 bg-zinc-900/60 p-6 backdrop-blur shadow-lg shadow-black/30">
                <form method="POST" action="{{ route('equipment.update', $equipment) }}">
                    @csrf
                    @method('PUT')

                    @include('equipment._form')

                    <div class="mt-6 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <x-primary-button>{{ __('Update Equipment') }}</x-primary-button>
                            <a href="{{ route('equipment.index') }}" class="text-sm font-medium text-zinc-400 hover:text-white">Cancel</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('equipment.destroy', $equipment) }}" class="mt-8 border-t border-darkgreen-900/30 pt-6"
                      onsubmit="return confirm('Delete this equipment? Items with existing bookings will be archived instead.');">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>{{ __('Delete Equipment') }}</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

