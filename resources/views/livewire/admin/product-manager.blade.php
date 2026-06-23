<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Kelola Produk</h2>
        <button wire:click="openModal" class="neumorphic-btn px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-600 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah
        </button>
    </div>

    <div class="space-y-4">
        @forelse($products as $product)
        <div class="neumorphic-flat p-4 rounded-3xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-slate-200 rounded-xl neumorphic-inset flex items-center justify-center text-xs text-slate-400 overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        Pic
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">{{ $product->name }}</h3>
                    <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                    <div class="mt-1">
                        <span class="text-[10px] font-bold text-slate-500">Stok Total: {{ $product->stock }}</span>
                        @if($product->slots->isNotEmpty())
                            <div class="mt-0.5 space-y-0.5">
                                @foreach($product->slots as $slot)
                                    <div class="text-[9px] text-slate-400">
                                        {{ $slot->machine->name ?? 'Mesin' }} (Slot {{ $slot->slot_number }}): <span class="font-bold">{{ $slot->stock }}</span> pcs
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-[9px] text-slate-400 mt-0.5">Belum dialokasikan ke mesin</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <button wire:click="edit({{ $product->id }})" class="neumorphic-btn w-8 h-8 rounded-lg flex items-center justify-center text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </button>
                <button wire:click="delete({{ $product->id }})" wire:confirm="Yakin ingin menghapus produk ini?" class="neumorphic-btn w-8 h-8 rounded-lg flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-xs font-medium text-slate-400">Belum ada produk</div>
        @endforelse
    </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-[#e0e5ec] p-6 rounded-[2rem] shadow-[20px_20px_60px_#bec3c9,-20px_-20px_60px_#ffffff] w-full max-w-md max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-slate-700 mb-4">{{ $isEditMode ? 'Edit Produk' : 'Tambah Produk' }}</h3>
            
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Produk</label>
                    <input type="text" wire:model="name" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="Masukkan nama produk">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi</label>
                    <textarea wire:model="description" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="Deskripsi singkat" rows="3"></textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Harga Dasar (Rp)</label>
                    <input type="number" wire:model="base_price" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="0">
                    @error('base_price') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Stok Total</label>
                    <input type="number" wire:model="stock" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="0">
                    @error('stock') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Gambar (Opsional)</label>
                    <input type="file" wire:model="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                    
                    <div wire:loading wire:target="image" class="text-xs text-emerald-600 mt-1">Mengunggah...</div>
                    
                    @error('image') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    
                    @if ($image)
                        <div class="mt-2 text-xs text-slate-500">Pratinjau:</div>
                        <img src="{{ $image->temporaryUrl() }}" class="mt-1 w-20 h-20 object-cover rounded-xl shadow-sm">
                    @elseif ($isEditMode && $oldImage)
                        <div class="mt-2 text-xs text-slate-500">Gambar saat ini:</div>
                        <img src="{{ asset('storage/' . $oldImage) }}" class="mt-1 w-20 h-20 object-cover rounded-xl shadow-sm">
                    @endif
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="closeModal" class="flex-1 neumorphic-btn py-2 rounded-xl text-sm font-bold text-slate-500">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 neumorphic-btn py-2 rounded-xl text-sm font-bold text-emerald-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
