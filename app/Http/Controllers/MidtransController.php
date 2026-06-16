<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $notif = new Notification($request->all());

        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $grossAmount = $notif->gross_amount;
        $statusCode = $notif->status_code;

        $signatureKey = hash(
            'sha512',
            $orderId.$statusCode.$grossAmount.config('midtrans.server_key')
        );

        if ($request->signature_key !== $signatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaksi = TransaksiPenjualan::where('no_invoice', $orderId)->first();

        if (! $transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $transaksi->update(['status_pembayaran' => 'paid']);
        } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
            $transaksi->update(['status_pembayaran' => 'failed']);
        } else {
            $transaksi->update(['status_pembayaran' => 'pending']);
        }

        return response()->json(['message' => 'OK']);
    }
}
