<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handleCallback(Request $request)
    {
        $notif = $request->all();
        $transaction_status = $notif['transaction_status'];
        $order_id = $notif['order_id'];
        Log::info('Midtrans Callback Data:', $request->all()); 


        // Cari reservasi berdasarkan order_id
        $reservation = Reservation::where('id', str_replace('BOOK-', '', $order_id))->first();

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Update status berdasarkan response dari Midtrans
        if ($transaction_status == 'settlement' || $transaction_status == 'capture') {
            $reservation->update(['status' => 'confirmed']);
        } elseif ($transaction_status == 'pending') {
            $reservation->update(['status' => 'pending']);
        } elseif (in_array($transaction_status, ['cancel', 'deny', 'expire', 'failure'])) {
            $reservation->update(['status' => 'canceled']);
        }

        return response()->json(['message' => 'Transaction updated successfully'], 200);
    }
}
