<div class="flex flex-col h-screen">
    <!-- Header -->
    <header class="px-6 pt-6 pb-4 shrink-0 relative z-10">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-medium tracking-wide">Selamat Datang!</p>
                <h1 class="text-xl font-bold text-slate-800">{{ $machine->name }}</h1>
            </div>
            <div class="neumorphic-flat px-3 py-1.5 rounded-full flex items-center gap-1.5 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Connected
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5">
            <div class="neumorphic-inset p-3 rounded-2xl text-center">
                <span class="text-[10px] text-slate-400 block mb-0.5 font-semibold">LOKASI</span>
                <span class="text-sm font-bold text-slate-800 truncate block">{{ $machine->location ?? 'Unknown' }}</span>
            </div>
            <div class="neumorphic-inset p-3 rounded-2xl text-center">
                <span class="text-[10px] text-slate-400 block mb-0.5 font-semibold">METODE BAYAR</span>
                <span class="text-sm font-bold text-indigo-600">QRIS / e-Wallet</span>
            </div>
        </div>
        
        @if (session()->has('message'))
            <div class="mt-4 p-3 rounded-2xl bg-emerald-100 text-emerald-800 text-xs font-bold text-center border border-emerald-200">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mt-4 p-3 rounded-2xl bg-red-100 text-red-800 text-xs font-bold text-center border border-red-200">
                {{ session('error') }}
            </div>
        @endif
    </header>

    <!-- Product Grid -->
    <main class="px-6 flex-1 overflow-y-auto pb-28 pt-2">
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Pilih Produk Anda</h2>
        
        <div class="grid grid-cols-2 gap-4">
            @forelse($machineSlots as $slot)
            <div class="neumorphic-flat p-3.5 rounded-3xl flex flex-col justify-between">
                
                <div class="w-full aspect-square bg-slate-200 rounded-2xl neumorphic-inset mb-3 flex flex-col items-center justify-center text-slate-400 text-xs overflow-hidden relative">
                    @if($slot->product->image)
                        <img src="{{ $slot->product->image }}" alt="{{ $slot->product->name }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[10px] text-center px-2">{{ $slot->product->name }}</span>
                    @endif
                    <div class="absolute top-2 right-2 bg-white/80 backdrop-blur px-1.5 py-0.5 rounded-md text-[10px] font-bold text-slate-700 shadow-sm">
                        Slot {{ $slot->slot_number }}
                    </div>
                </div>
                
                <div>
                    <h3 class="text-sm font-bold text-slate-800 truncate" title="{{ $slot->product->name }}">{{ $slot->product->name }}</h3>
                    <p class="text-xs font-bold gradient-text mt-0.5">Rp {{ number_format($slot->price, 0, ',', '.') }}</p>
                    
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-[10px] text-slate-400 font-medium">Sisa: <b class="text-slate-600">{{ $slot->stock }}</b></span>
                        
                        <div class="flex items-center gap-1.5">
                            @if(isset($this->cart[$slot->id]))
                                <button wire:click="removeFromCart({{ $slot->id }})" class="neumorphic-btn w-7 h-7 rounded-lg flex items-center justify-center text-slate-600 font-bold text-lg leading-none">-</button>
                                <span class="text-xs font-bold text-slate-800 w-3 text-center">{{ $this->cart[$slot->id] }}</span>
                            @endif
                            <button wire:click="addToCart({{ $slot->id }})" class="neumorphic-btn w-8 h-8 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-lg leading-none">+</button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 neumorphic-inset p-6 rounded-3xl text-center">
                <p class="text-xs font-bold text-slate-500">Maaf, semua produk sedang kosong.</p>
            </div>
            @endforelse
        </div>
    </main>

    @if($isCartOpen && $this->totalItems > 0)
    <!-- Cart Overlay / Slide up -->
    <div class="fixed bottom-[85px] left-1/2 -translate-x-1/2 w-full max-w-md p-4 z-40 animate-fade-in-up">
        <div class="neumorphic-flat p-5 rounded-3xl border border-white/50 bg-[#E2E8F0]/95 backdrop-blur-xl">
            <div class="flex justify-between items-center mb-4 border-b border-slate-200 pb-3">
                <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Detail Pesanan</h3>
                <button wire:click="toggleCart" class="neumorphic-btn w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </div>
            
            <div class="space-y-4 max-h-48 overflow-y-auto mb-4 pr-2">
                @foreach($this->cart as $slotId => $qty)
                    @php $cSlot = $machineSlots->firstWhere('id', $slotId); @endphp
                    @if($cSlot)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $cSlot->product->name }}</p>
                            <p class="text-[10px] font-bold text-indigo-500">{{ $qty }}x @ Rp {{ number_format($cSlot->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <p class="text-sm font-bold text-slate-700">Rp {{ number_format($cSlot->price * $qty, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-1.5 bg-slate-200 rounded-lg p-1 neumorphic-inset">
                                <button wire:click="removeFromCart({{ $slotId }})" class="w-6 h-6 rounded flex items-center justify-center text-slate-800 font-bold text-lg leading-none">-</button>
                                <span class="text-xs font-bold w-4 text-center">{{ $qty }}</span>
                                <button wire:click="addToCart({{ $slotId }})" class="w-6 h-6 rounded flex items-center justify-center text-emerald-600 font-bold text-lg leading-none">+</button>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            
            <div class="flex items-center justify-between mt-2 pt-4 border-t border-slate-200">
                <span class="text-xs font-bold text-slate-500">Total Pembayaran</span>
                <span class="text-xl font-black gradient-text">Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
            </div>
            
            <button wire:click="checkout" class="w-full mt-5 gradient-btn py-3.5 rounded-2xl font-bold text-sm tracking-wide flex justify-center items-center gap-2 shadow-lg">
                BAYAR SEKARANG
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Bottom Navigation Bar (Always visible) -->
    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md p-4 bg-[#E2E8F0]/90 backdrop-blur-md border-t border-slate-200/50 z-50">
        <div class="neumorphic-flat p-2 rounded-2xl flex items-center justify-around">
            
            <!-- Home -->
            <button wire:click="$set('isCartOpen', false)" class="neumorphic-inset p-3 rounded-xl text-emerald-600 flex flex-col items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </button>

            <!-- Cart Toggle -->
            <button wire:click="toggleCart" class="neumorphic-btn p-3 rounded-xl {{ $isCartOpen ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }} flex flex-col items-center gap-1 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                @if($this->totalItems > 0)
                <span class="absolute -top-1 -right-1 bg-emerald-500 text-white text-[9px] font-extrabold w-4 h-4 rounded-full flex items-center justify-center shadow">{{ $this->totalItems }}</span>
                @endif
            </button>

            <!-- Status / History -->
            <button class="neumorphic-btn p-3 rounded-xl text-slate-400 hover:text-slate-600 flex flex-col items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </button>
        </div>
    </nav>

    @php
        $snapUrl = config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapUrl }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

    @script
    <script>
        $wire.on('snap-pay', (data) => {
            let token = Array.isArray(data) ? data[0].token : data.token;
            if(!token) {
                // Livewire 3 sometimes wraps data in an array or an object
                token = data[0]; 
            }
            if(typeof data === 'object' && data.token) token = data.token;
            
            window.snap.pay(token, {
                onSuccess: function(result){
                    alert("Pembayaran berhasil!");
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    // user closed popup
                }
            });
        });
    </script>
    @endscript
</div>
