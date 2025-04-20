<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Reservation;
use Carbon\Carbon;

class ZeservationWidget extends BaseWidget
{
    protected function getTableHeading(): string
    {
        return 'Reservation Aktif';
    }

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
        ->query(
            Reservation::where('check_in', '<=', Carbon::now())
                ->where('check_out', '>=', Carbon::now())
                ->where('status', 'confirmed')
        )
        ->defaultSort('check_in', 'asc')
        ->columns([
            Tables\Columns\TextColumn::make('user.name')->label('User'),
            Tables\Columns\TextColumn::make('room.room_number')->label('Room'),
            Tables\Columns\TextColumn::make('check_in')->label('Check-In')->date(),
            Tables\Columns\TextColumn::make('check_out')->label('Check-Out')->date(),
        ]);
    }
}
