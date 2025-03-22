<?php

namespace App\Http\Controllers;

abstract class Controller
{
    
    public function show($roomId)
    {
        // Ambil data room berdasarkan roomId yang diberikan
        $room = \App\Models\Room::findOrFail($roomId);

        // Kirim data room ke view
        return view('booking-room-show', compact('room'));
    }
}
