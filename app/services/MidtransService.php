<?php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Reservation;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false; // Ubah ke true jika sudah live
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Reservation $reservation)
    {
        $transaction = [
            'transaction_details' => [
                'order_id' => 'BOOK-' . $reservation->id,
                'gross_amount' => $reservation->total_price,
            ],
            'customer_details' => [
                'first_name' => $reservation->user->name,
                'email' => $reservation->user->email,
                'phone' => $reservation->phone_number,
            ],
            'callbacks' => [
                'finish' => route('midtrans.redirect')
            ],
            'notification_url' => route('midtrans.callback') // Menambahkan URL callback Midtrans
        ];

        $snapTransaction = Snap::createTransaction($transaction);

        return $snapTransaction->token;
    }
}
