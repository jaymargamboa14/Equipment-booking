@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-olive-500 bg-darkgreen-950/50 py-2 pe-4 ps-3 text-start text-base font-medium text-white focus:border-olive-400 focus:bg-darkgreen-950/70 focus:text-white focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-zinc-400 hover:border-darkgreen-800 hover:bg-darkgreen-950/30 hover:text-white focus:border-olive-700 focus:bg-darkgreen-950/30 focus:text-white focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

