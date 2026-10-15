<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-md border border-darkgreen-900/60 bg-zinc-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-zinc-200 shadow-sm transition hover:bg-zinc-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-olive-500 focus:ring-offset-2 focus:ring-offset-zinc-950 disabled:opacity-50']) }}>
    {{ $slot }}
</button>

