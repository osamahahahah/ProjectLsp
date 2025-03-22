<?php

namespace App\Filament\Widgets;

use App\Models\reservation;
use App\Models\Room;
use App\Models\User;
use Doctrine\DBAL\Exception\InvalidColumnType\ColumnPrecisionRequired;
use Doctrine\DBAL\Schema\Column;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class StatsDashboard extends BaseWidget

{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subMonth(6);
        $endDate = $this->filters['endDate'] ?? now()->endOfYear();


        $totalRevenue = Reservation::whereBetween('check_in', [$startDate, $endDate])->sum('total_price');

        $countroom = Room::count();
        $countbooking = reservation::count();

        $popularRoom = Reservation::select('room_id', DB::raw('count(*) as total'))
        ->groupBy('room_id')
        ->orderByDesc('total')
        ->first();


    if ($popularRoom) {
        $room = Room::find($popularRoom->room_id);
        $roomType = $room ? ucfirst($room->room_type) : 'Tidak ada data';
        $totalBookings = $popularRoom->total;
    } else {
        $roomType = 'Tidak ada data';
        $totalBookings = 0;
    }

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))

            ->description('increase')
            ->descriptionIcon('heroicon-o-arrow-trending-up')
            ->chart([5,15,10,8,20])
            ->color('success'),
            Stat::make('Jumlah Kamar', $countroom . ' Kamar'),
            Stat::make('Jumlah Booking', $countbooking . ' Booking'),
            Stat::make('Tipe Kamar Terpopuler', $roomType)
            ->description("Dipesan sebanyak {$totalBookings} kali")
            ->descriptionIcon('heroicon-o-arrow-trending-up')
            ->chart([5,15,10,8,20])
            ->color('success'),



        ];

    }

    protected function getColumns(): int
    {
        return 2;
    }
}
