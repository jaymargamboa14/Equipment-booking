@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-zinc-300']) }}>
    {{ $value ?? $slot }}
</label>
