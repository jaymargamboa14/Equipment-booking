@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-darkgreen-900/60 bg-zinc-950/60 text-zinc-100 placeholder-zinc-500 shadow-sm focus:border-olive-500 focus:ring-olive-500']) }}>

