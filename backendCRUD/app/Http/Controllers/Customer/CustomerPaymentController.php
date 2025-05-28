<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class CustomerPaymentController extends Controller
{
    /**
     * Endpoint webhook dari Midtrans
     * Update status payment dan booking sesuai status transaksi
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // TODO: Validasi signature Midtrans untuk keamanan

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (!$orderId || !$transactionStatus) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $payment = Payment::where('payment_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->payment_status = $transactionStatus;
        $payment->save();

        // Update status booking jika perlu
        $booking = $payment->booking;
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $booking->status = 'confirmed';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
            $booking->status = 'failed';
        }
        $booking->save();

        return response()->json(['message' => 'Payment status updated']);
    }
}
