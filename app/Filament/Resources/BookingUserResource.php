<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingUserResource\Pages;
use App\Filament\Resources\BookingUserResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Badge;

class BookingUserResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return 'My Booking';
    }

    public static function getPluralModelLabel(): string
    {
        return 'My Bookings';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    // public static function query(Builder $query): Builder
    // {
    //     dd(auth()->id()); // Ini akan menampilkan ID user yang sedang login dan menghentikan eksekusi
    //     die;

    //     return $query->where('user_id', auth()->id() ?? 0);
    // }


    public static function table(Table $table): Table
    {

        return $table
        ->query(Reservation::where('user_id', auth()->id()))
        ->columns([
            Tables\Columns\TextColumn::make('room.room_number')
                ->label('Room')
                ->searchable()
                ->weight('bold')
                ->color('primary')
                ->alignCenter()
                ->size('lg'),

            Tables\Columns\TextColumn::make('check_in')
                ->label('Check-In')
                ->date(),


            Tables\Columns\TextColumn::make('check_out')
                ->label('Check-Out')
                ->date(),


            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'canceled' => 'danger',
                })
                ->badge()
                

        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->mutateRecordDataUsing(fn ($record) => [
                    'room' => $record->room->room_number,
                    'check_in' => $record->check_in,
                    'check_out' => $record->check_out,
                    'qty_person' => $record->qty_person,
                    'total_price' => 'IDR ' . number_format($record->total_price, 0, ',', '.'),
                    'phone_number' => Str::replaceFirst('62', '0', $record->phone_number ?? 'N/A'),
                    'email' => $record->user->email ?? 'N/A',
                    'status' => $record->status,
                ])
                ->form([
                    Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('room')->label('Room')->disabled(),
                        Forms\Components\TextInput::make('qty_person')->label('Qty Person')->disabled(),
                        Forms\Components\DatePicker::make('check_in')->label('Check-In')->disabled(),
                        Forms\Components\DatePicker::make('check_out')->label('Check-Out')->disabled(),
                        Forms\Components\TextInput::make('total_price')->label('Total Price')->disabled(),
                        Forms\Components\Select::make('status')
                         ->label('Status')
                         ->options([
                          'pending' => ' Pending',
                          'confirmed' => 'Confirmed',
                          'canceled' => ' Canceled',
                     ])
            ->disabled(),
                    ]),
                ]),


            ])
            ->bulkActions([]);
    }




    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingUsers::route('/'),
            'create' => Pages\CreateBookingUser::route('/create'),
            'edit' => Pages\EditBookingUser::route('/{record}/edit'),
        ];
    }
}
