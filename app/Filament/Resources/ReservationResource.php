<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use App\Models\User;
use Filament\Forms;
use App\Models\Room;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{

    // protected int | string | array $columnSpan = 'full';
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'Management';

    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('Reservations'))
        return true;
       else
        return false;
    }

    public static function getTitle(): string
    {
        return 'Reservation Management';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('user_id')
                ->label('User')
                ->disabled()
                ->default(fn ($record) => $record ? $record->user?->name : null)
                ->formatStateUsing(fn ($state) => User::find($state)?->name ?? 'Unknown'),

             TextInput::make('room_id')
            ->label('Room')
            ->disabled()
            ->default(fn ($record) => $record ? $record->room?->room_number : null)
            ->formatStateUsing(fn ($state) => Room::find($state)?->room_number ?? 'Unknown'),
                Forms\Components\DatePicker::make('check_in')->label('Check-In'),
                Forms\Components\DatePicker::make('check_out')->label('Check-Out'),
                Forms\Components\TextInput::make('qty_person')->label('Qty Person'),
                Forms\Components\TextInput::make('total_price')->label('Total Price')
                ->disabled(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'canceled' => 'Canceled',
                    ])
                    ->required(),

            ]);

    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
            Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
            Tables\Columns\TextColumn::make('room.room_number')->label('Room'),
            Tables\Columns\TextColumn::make('check_in')->label('Check-In')->date(),
            Tables\Columns\TextColumn::make('check_out')->label('Check-Out')->date(),
            Tables\Columns\TextColumn::make('total_price')->label('Total Price')->money('IDR'),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
               ->icon(fn ($state) => match ($state) {
                'pending' => 'heroicon-o-clock',
                 'confirmed' => 'heroicon-o-check-circle',
                'canceled' => 'heroicon-o-x-circle',
            })
             ->color(fn ($state) => match ($state) {
                 'pending' => 'warning',
                 'confirmed' => 'success',
                 'canceled' => 'danger',
             }),
        ])

        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ]),
        ])
        ->actions([
            Tables\Actions\ViewAction::make()
            ->mutateRecordDataUsing(fn ($record) => [
                'user' => $record->user->name,
                'room' => $record->room->room_number,
                'check_in' => $record->check_in,
                'check_out' => $record->check_out,
                'qty_person' => $record->qty_person,
                'total_price' => 'IDR ' . number_format($record->total_price, 0, ',', '.'),
                'phone_number' => Str::replaceFirst('62', '0', $record->phone_number ?? 'N/A'),
                'email' => $record->user->email ?? 'N/A',
            ])
            ->form([
                Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('user')->label('User')->disabled(),
                    Forms\Components\TextInput::make('room')->label('Room')->disabled(),
                    Forms\Components\DatePicker::make('check_in')->label('Check-In')->disabled(),
                    Forms\Components\DatePicker::make('check_out')->label('Check-Out')->disabled(),
                    Forms\Components\TextInput::make('qty_person')->label('Qty Person')->disabled(),
                    Forms\Components\TextInput::make('total_price')->label('Total Price')->disabled(),
                    Forms\Components\TextInput::make('phone_number')->label('Phone Number')->disabled(),
                    Forms\Components\TextInput::make('email')->label('Email')->disabled(),
                ]),
            ]),
            Tables\Actions\EditAction::make(),

        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
