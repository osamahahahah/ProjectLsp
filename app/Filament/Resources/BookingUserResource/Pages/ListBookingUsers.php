<?php

namespace App\Filament\Resources\BookingUserResource\Pages;

use App\Filament\Resources\BookingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingUsers extends ListRecords
{
    protected static string $resource = BookingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
