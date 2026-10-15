<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-md border border-transparent bg-olive-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm shadow-darkgreen-950/50 transition duration-150 ease-in-out hover:bg-olive-500 focus:outline-none focus:ring-2 focus:ring-olive-500 focus:ring-offset-2 focus:ring-offset-zinc-950 active:bg-olive-700 disabled:opacity-50']) }}>
    {{ $slot }}
</button>

