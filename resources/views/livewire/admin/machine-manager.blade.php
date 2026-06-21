<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Daftar Mesin</h2>
        <button class="neumorphic-btn px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-600 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah
        </button>
    </div>

    <div class="space-y-4">
        @forelse($machines as $machine)
        <div class="neumorphic-flat p-4 rounded-3xl">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">{{ $machine->name }}</h3>
                    <p class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $machine->unique_code }}</p>
                </div>
                <div class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $machine->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                    {{ $machine->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
            </div>
            <p class="text-xs text-slate-500 mb-4">{{ $machine->location ?? 'Lokasi belum diset' }}</p>
            
            <div class="flex gap-2">
                <button class="neumorphic-btn flex-1 py-2 rounded-xl text-xs font-bold text-indigo-600">Slots</button>
                <button class="neumorphic-btn flex-1 py-2 rounded-xl text-xs font-bold text-slate-600">Edit</button>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-xs font-medium text-slate-400">Belum ada mesin</div>
        @endforelse
    </div>
</div>
