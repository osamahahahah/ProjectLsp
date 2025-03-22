<?php

namespace App\Filament\Resources\BookingUserResource\Pages;

use App\Filament\Resources\BookingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookingUser extends EditRecord
{
    protected static string $resource = BookingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
