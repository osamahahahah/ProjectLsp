<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Room;
use App\Models\Reservation;

// class PopulerRoomsChart extends ChartWidget
// {
//     // protected static ?string $heading = 'Kamar Terpopuler';

//     // protected function getData(): array
//     // {
//     //     // Ambil jumlah reservasi untuk setiap kamar
//     //     $roomReservations = Reservation::selectRaw('room_id, COUNT(*) as total')
//     //         ->groupBy('room_id')
//     //         ->orderByDesc('total')
//     //         ->limit(5) // Batasi hanya 5 kamar terpopuler
//     //         ->get();

//     //     // Data untuk chart
//     //     $labels = $roomReservations->map(fn ($res) => Room::find($res->room_id)?->room_number ?? 'Unknown');
//     //     $data = $roomReservations->pluck('total');

//     //     return [
//     //         'labels' => $labels->toArray(),
//     //         'datasets' => [
//     //             [
//     //                 'label' => 'Jumlah Reservasi',
//     //                 'data' => $data->toArray(),
//     //                 'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
//     //             ],
//     //         ],
//     //     ];
//     // }

//     // protected function getType(): string
//     // {
//     //     return 'pie';
//     // }
// }

