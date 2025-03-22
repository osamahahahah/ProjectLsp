<?php
namespace App\Http\Controllers;

use App\Http\Resources\BookingRoomResource;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



class ReservationController extends Controller
{

    public function index()
    {
        $reservations = Reservation::with('room')
            ->where('user_id', Auth::id())
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    // Menyimpan reservasi baru
    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'room_id' => 'required|exists:rooms,id',
    //         'checkin_date' => 'required|date|after_or_equal:today',
    //         'checkout_date' => 'required|date|after:checkin_date',
    //         'phone_number' => 'required|digits_between:10,15',
    //         'qty_person' => 'required|integer|min:1',
    //     ]);

    //     $checkIn = Carbon::parse($request->checkin_date);
    //     $checkOut = Carbon::parse($request->checkout_date);
    //     $nights = $checkIn->diffInDays($checkOut);
    //     $room = Room::findOrFail($request->room_id);
    //     $totalPrice = $room->price * max($nights, 1);



    //     $reservation = Reservation::create([
    //         'room_id' => $room->id,
    //         'user_id' => Auth::id(),
    //         'check_in' => $request->checkin_date,
    //         'check_out' => $request->checkout_date,
    //         'phone_number' => str_starts_with($request->phone_number, '08')
    //             ? '628' . substr($request->phone_number, 1)
    //             : $request->phone_number,
    //         'qty_person' => $request->qty_person,
    //         'total_price' => $totalPrice,
    //         'status' => 'pending',
    //     ]);

    //     return redirect()->back()->with('success', 'Reservasi berhasil dibuat!');


    // }
     // $reservation = new Reservation();
        // $reservation->user_id = Auth::id();
        // $reservation->room_id = $room->id;
        // $reservation->check_in = $request->check_in;
        // $reservation->check_out = $request->check_out;
        // $reservation->qty_person = $request->qty_person;
        // $reservation->total_price = $room->price * $diffInDays * $request->qty_person; // Hitung harga otomatis
        // $reservation->status = "pending";
        // $reservation->save();

    // Mengubah status reservasi (hanya admin yang boleh)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,canceled',
        ]);

        $reservation = Reservation::findOrFail($id);




        $reservation->status = $request->input('status');
        $reservation->save();

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui!');
    }
}
