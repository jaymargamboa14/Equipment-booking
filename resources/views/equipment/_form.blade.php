@php($equipment = $equipment ?? null)

<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="name" :value="__('Equipment name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $equipment?->name)" required autofocus placeholder="e.g. Monobloc Chairs" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category" :value="__('Category')" />
        <select id="category" name="category" class="mt-1 block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 shadow-sm focus:border-olive-500 focus:ring-olive-500">
            @foreach ($categories as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $equipment?->category) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="quantity" :value="__('Quantity in stock')" />
        <x-text-input id="quantity" name="quantity" type="number" min="1" class="mt-1 block w-full" :value="old('quantity', $equipment?->quantity ?? 1)" required />
        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 shadow-sm focus:border-olive-500 focus:ring-olive-500">
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $equipment?->status ?? 'available') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="location" :value="__('Storage location (optional)')" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $equipment?->location)" placeholder="e.g. Gym Storage" />
        <x-input-error :messages="$errors->get('location')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 placeholder-zinc-500 shadow-sm focus:border-olive-500 focus:ring-olive-500" placeholder="Details about condition, accessories, etc.">{{ old('description', $equipment?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
</div>

