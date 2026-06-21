<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }

        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $order = Order::where('midtrans_order_id', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Midtrans statuses
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->update(['status' => 'pending']);
            } else if ($fraud == 'accept') {
                $order->update(['status' => 'paid']);
            }
        } else if ($transaction == 'settlement') {
            $order->update(['status' => 'paid']);
        } else if ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
            $order->update(['status' => 'failed']);
            // Return stock back
            foreach ($order->items as $item) {
                if ($item->machineSlot) {
                    $item->machineSlot->increment('stock', $item->qty);
                }
            }
        } else if ($transaction == 'pending') {
            $order->update(['status' => 'pending']);
        }

        // If paid, create dispense logs for the ESP32 to pull
        if ($order->status === 'paid' && $order->wasChanged('status')) {
            foreach ($order->items as $item) {
                for ($i = 0; $i < $item->qty; $i++) {
                    \App\Models\DispenseLog::create([
                        'order_id' => $order->id,
                        'machine_id' => $order->machine_id,
                        'machine_slot_id' => $item->machine_slot_id,
                        'status' => 'pending' // ESP32 will pick this up
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Notification handled successfully']);
    }
}
