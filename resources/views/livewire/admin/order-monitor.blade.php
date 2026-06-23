<div wire:poll.5s>
    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Transaksi Terakhir</h2>

    <div class="space-y-3">
        @forelse($orders as $order)
        <div class="neumorphic-flat p-4 rounded-3xl flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-xs">#{{ $order->midtrans_order_id }}</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $order->machine->name ?? 'Unknown Machine' }} &bull; {{ $order->created_at->format('d M H:i') }}</p>
                <p class="text-xs font-bold text-emerald-600 mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
            <div>
                @if($order->status === 'paid' || $order->status === 'completed')
                <span class="inline-block px-2 py-1 rounded-lg text-[9px] font-bold bg-emerald-100 text-emerald-600 uppercase">{{ $order->status }}</span>
                @elseif($order->status === 'failed' || $order->status === 'stuck')
                <span class="inline-block px-2 py-1 rounded-lg text-[9px] font-bold bg-red-100 text-red-600 uppercase">{{ $order->status }}</span>
                @else
                <span class="inline-block px-2 py-1 rounded-lg text-[9px] font-bold bg-amber-100 text-amber-600 uppercase">{{ $order->status }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-xs font-medium text-slate-400">Belum ada transaksi</div>
        @endforelse
    </div>
</div>