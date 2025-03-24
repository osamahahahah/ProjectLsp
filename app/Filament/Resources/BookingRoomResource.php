<?php


namespace App\Filament\Resources;

use App\Filament\Resources\BookingRoomResource\Pages;
use App\Models\Room;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Midtrans\Snap;
use Midtrans\Config;


class BookingRoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationLabel = 'Booking Rooms';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return 'Booking Room';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Booking Room';
    }



    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Room::whereJsonContains('status', 'true'))
            ->columns([
                Tables\Columns\TextColumn::make('room_number')->label('Nomor Kamar')->searchable(),
                Tables\Columns\TextColumn::make('price')->label('Harga Kamar')->money('IDR')->searchable(),
                Tables\Columns\TextColumn::make('room_type')->label('Tipe Kamar')
            ])
            ->filters([
                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\Fieldset::make('Rentang Harga (IDR)')
                            ->schema([
                                Forms\Components\TextInput::make('min_price')
                                    ->label('Harga Minimum')
                                    ->numeric()
                                    ->placeholder('Masukkan harga minimal'),

                                Forms\Components\TextInput::make('max_price')
                                    ->label('Harga Maksimum')
                                    ->numeric()
                                    ->placeholder('Masukkan harga maksimal'),
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['min_price'], fn ($q) => $q->where('price', '>=', $data['min_price']))
                            ->when($data['max_price'], fn ($q) => $q->where('price', '<=', $data['max_price']));
                    }),
            ])

            ->actions([
                Action::make('view_details')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Room Details')
                    ->modalSubmitActionLabel('Booking Now')
                    ->form(fn ($record) => [
                        Section::make('Room Information')->schema([
                            TextInput::make('room_number')->label('Room Number')->default($record->room_number)->disabled(),
                            TextInput::make('room_type')->label('Room Type')->default($record->room_type)->disabled(),
                            TextInput::make('price')->label('Price (IDR)')->default(number_format($record->price, 0, ',', '.'))->disabled(),
                            Textarea::make('facilities')->label('Facilities')->default($record->facilities)->disabled(),
                        ]),

                        Section::make('Booking')->schema([
                            DatePicker::make('checkin_date')
                                ->label('Check-in Date')
                                ->required()
                                ->minDate(today())
                                ->native(false)
                                ->placeholder('Pilih tanggal check-in')
                                ->suffixIcon('heroicon-o-calendar')
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set, $state) => $set('checkout_date', null))
                                ->disabledDates(fn ($record) => static::getDisabledDates($record)),

                            DatePicker::make('checkout_date')
                                ->label('Check-out Date')
                                ->required()
                                ->after('checkin_date')
                                ->reactive()
                                ->minDate(fn (callable $get) => $get('checkin_date'))
                                ->native(false)
                                ->placeholder('Pilih tanggal check-in')
                                ->suffixIcon('heroicon-o-calendar')
                                ->disabledDates(fn ($record) => static::getDisabledDates($record)),

                            TextInput::make('phone_number')
                                ->label('Nomor Telepon')
                                ->required()
                                ->mask('999999999999999')
                                ->rules(['digits_between:10,15'])
                                ->maxLength(15)
                                ->formatStateUsing(fn ($state) => str_starts_with($state, '628') ? '08' . substr($state, 2) : $state)
                                ->dehydrateStateUsing(fn ($state) => str_starts_with($state, '08') ? '628' . substr($state, 1) : $state),

                            TextInput::make('qty_person')
                                ->label('Jumlah Orang')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                        ]),
                    ])
                    ->action(fn (array $data, Room $record) => static::bookRoom($data, $record))
                    ->hidden(fn ($record) => $record->status === 'tidak tersedia'),
            ]);
    }

    public static function bookRoom(array $data, Room $record)
    {
        $checkIn = Carbon::parse($data['checkin_date']);
        $checkOut = Carbon::parse($data['checkout_date']);
        $nights = max($checkIn->diffInDays($checkOut), 1);
        $totalPrice = $record->price * $nights;

       $reservation = Reservation::create([
            'room_id' => $record->id,
            'user_id' => auth()->id(),
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
            'phone_number' => str_starts_with($data['phone_number'], '08')
                ? '628' . substr($data['phone_number'], 1)
                : $data['phone_number'],
            'qty_person' => $data['qty_person'],
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);
            $midtransService = new \App\Services\MidtransService();
            $snapToken = $midtransService->createTransaction($reservation);

            $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}";

            return redirect()->away($paymentUrl);
    }

    public static function getDisabledDates(Room $record)
    {
        $reservations = Reservation::where('room_id', $record->id)
        ->where('status', 'confirmed')
        ->get(['check_in', 'check_out']);


        return $reservations->flatMap(fn ($reservation) =>
            collect(CarbonPeriod::create(
                Carbon::parse($reservation->check_in),
                Carbon::parse($reservation->check_out)
            ))->map(fn ($date) => $date->format('Y-m-d'))
        )->toArray();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingRooms::route('/'),
            'edit' => Pages\EditBookingRoom::route('/{record}/edit'),
        ];
    }


}
