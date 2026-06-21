<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Kelola Produk</h2>
        <button class="neumorphic-btn px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-600 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah
        </button>
    </div>

    <div class="space-y-4">
        @forelse($products as $product)
        <div class="neumorphic-flat p-4 rounded-3xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-slate-200 rounded-xl neumorphic-inset flex items-center justify-center text-xs text-slate-400">Pic</div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">{{ $product->name }}</h3>
                    <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <button class="neumorphic-btn w-8 h-8 rounded-lg flex items-center justify-center text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </button>
                <button class="neumorphic-btn w-8 h-8 rounded-lg flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-xs font-medium text-slate-400">Belum ada produk</div>
        @endforelse
    </div>
</div>
