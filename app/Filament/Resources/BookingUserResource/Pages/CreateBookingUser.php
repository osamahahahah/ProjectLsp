<?php

namespace App\Filament\Resources\BookingUserResource\Pages;

use App\Filament\Resources\BookingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingUser extends CreateRecord
{
    protected static string $resource = BookingUserResource::class;
}
