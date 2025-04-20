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
        // Buat custom order ID yang dimulai dari 0
        $bookingNumber = $this->generateBookingNumber($reservation->id);
        
        $transaction = [
            'transaction_details' => [
                'order_id' => 'BOOK-' . $bookingNumber,
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
    
    /**
     * Generate booking number yang dimulai dari 0
     * 
     * @param int $reservationId
     * @return int
     */
    private function generateBookingNumber($reservationId)
    {
        // Cari reservasi dengan ID terkecil untuk offset
        $firstReservation = Reservation::orderBy('id', 'asc')->first();
        
        if (!$firstReservation) {
            return 0; // Jika tidak ada reservasi, mulai dari 0
        }
        
        // Kurangi ID reservasi saat ini dengan ID reservasi pertama
        // lalu tambahkan 0 agar nomor booking dimulai dari 0
        $bookingNumber = $reservationId - $firstReservation->id;
        
        return $bookingNumber;
    }
}
