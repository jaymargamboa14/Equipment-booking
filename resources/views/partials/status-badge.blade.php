@php
    $classes = match ($status) {
        'confirmed', 'available' => 'bg-emerald-500/15 text-emerald-300 ring-emerald-500/30',
        'completed' => 'bg-sky-500/15 text-sky-300 ring-sky-500/30',
        'cancelled', 'retired' => 'bg-rose-500/15 text-rose-300 ring-rose-500/30',
        'maintenance' => 'bg-amber-500/15 text-amber-300 ring-amber-500/30',
        default => 'bg-zinc-500/15 text-zinc-300 ring-zinc-500/30',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize ring-1 ring-inset {{ $classes }}">
    {{ str_replace('_', ' ', $status) }}
</span>
