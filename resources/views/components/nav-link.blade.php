@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-olive-500 px-1 pt-1 text-sm font-medium leading-5 text-white focus:border-olive-400 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-zinc-400 hover:border-darkgreen-800 hover:text-white focus:border-olive-700 focus:text-white focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

