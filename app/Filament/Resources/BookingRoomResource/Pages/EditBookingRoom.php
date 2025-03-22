<?php

namespace App\Filament\Resources\BookingRoomResource\Pages;

use App\Filament\Resources\BookingRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookingRoom extends EditRecord
{
    protected static string $resource = BookingRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
