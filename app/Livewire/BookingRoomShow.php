<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\Reservation;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;


class BookingRoomShow extends Component
{
    public $room;

    // Menyimpan informasi ID kamar yang akan ditampilkan
    public function mount($id)
    {
        // Ambil data kamar berdasarkan ID yang diterima
        $this->room = Room::findOrFail($id);
    }

    // Fungsi untuk memproses pemesanan kamar
    public function bookRoom()
    {
        if (!Auth::check()) {
            // Jika pengguna belum login
            return redirect()->route('login');
        }

        // Membuat reservasi untuk kamar yang dipilih
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'room_id' => $this->room->id,
            'check_in' => now(),
            'check_out' => now()->addDays(1),
            'qty_person' => 1, // Misalnya 1 orang
            'total_price' => $this->room->price,
            'status' => json_encode(['booked']),
        ]);

        // Menampilkan notifikasi keberhasilan pemesanan
        Notification::make()
            ->title('Booking Berhasil!')
            ->body('Kamar berhasil dipesan.')
            ->success()
            ->send();

        // Redirect ke halaman daftar kamar
        return redirect()->route('booking-room.index');
    }

    public function render()
    {
        return view('livewire.bookingroom-show');
    }


}

