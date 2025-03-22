@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kamar Info -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Kamar: {{ $room->room_number }}</h2>

                    <div class="mt-4 space-y-2">
                        <p class="text-lg text-gray-700"><strong>Harga:</strong> IDR {{ number_format($room->price, 2) }}</p>
                        <p class="text-lg text-gray-700"><strong>Tipe Kamar:</strong> {{ is_array($room->room_type) ? implode(', ', array_map('ucfirst', $room->room_type)) : ucfirst($room->room_type) }}</p>
                        <p class="text-lg text-gray-700"><strong>Fasilitas:</strong> {{ $room->facilities ?? 'Tidak ada fasilitas yang ditentukan.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Pemesanan -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800">Pesan Kamar</h3>
                <p class="text-gray-600 mt-2">Isi formulir di bawah untuk melakukan reservasi.</p>

                <form action="{{ route('reservations.store') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">

                    <!-- Input Tanggal Check-in -->
                    <div class="mt-4">
                        <label for="check_in" class="block text-sm font-medium text-gray-700">Tanggal Check-in</label>
                        <input type="date" id="check_in" name="check_in" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Input Tanggal Check-out -->
                    <div class="mt-4">
                        <label for="check_out" class="block text-sm font-medium text-gray-700">Tanggal Check-out</label>
                        <input type="date" id="check_out" name="check_out" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Input Jumlah Orang -->
                    <div class="mt-4">
                        <label for="qty_person" class="block text-sm font-medium text-gray-700">Jumlah Orang</label>
                        <input type="number" id="qty_person" name="qty_person" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="mt-6 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition w-full">
                        Booking Kamar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
