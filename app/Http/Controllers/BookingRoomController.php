<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class BookingRoomController extends Controller
{
    public function show($id)
    {
        // Pastikan ID kamar ada di database
        $room = Room::findOrFail($id);

        // Mengembalikan tampilan dan mengirimkan data kamar
        return view('booking-room-show', compact('room'));
    }

}

