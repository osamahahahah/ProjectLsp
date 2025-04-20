<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Set;
use Filament\Forms\Components\FileUpload;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Management';

 public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('Rooms'))
        return true;
       else
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_number')
                ->required()
                ->unique(column: 'room_number', ignoreRecord: true),
            Forms\Components\Textarea::make('facilities'),
            Forms\Components\Radio::make('room_type')
                ->options([
                    'deluxe' => 'Deluxe',
                    'suite' => 'Suite',
                    'standard' => 'Standard',
                ])
                ->columns(3),
            Forms\Components\TextInput::make('price')
                ->prefix('Rp.')
                ->numeric(),

                FileUpload::make('image')
                ->multiple()
                ->disk('public')
                ->directory('room-images') // direktori penyimpanan
                ->imagePreviewHeight('100')
                ->acceptedFileTypes(['image/*'])
                ->loadingIndicatorPosition('left')
                ->panelAspectRatio('2:1')
                ->panelLayout('grid')
                ->removeUploadedFileButtonPosition('right')
                ->uploadButtonPosition('left')
                ->uploadProgressIndicatorPosition('left')
                ->saveRelationshipsUsing(function ($record, $state) {
                    if (!empty($state)) {
                        $record->image = $state;
                        $record->save();
                    }
                }),

                Forms\Components\Toggle::make('status')
                ->label('Status')
                ->onColor('success')
                ->offColor('danger')
                ->dehydrateStateUsing(fn ($state) => [$state ? 'true' : 'false'])
                ->afterStateHydrated(function ($state, Set $set) {
                    if (is_array($state)) {
                        $set('status', isset($state[0]) && $state[0] === 'true');
                    } else {
                        $decoded = json_decode($state, true);
                        $set('status', is_array($decoded) && isset($decoded[0]) && $decoded[0] === 'true');
                    }
                })
                ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('room_number')->label('Room Number'),
            TextColumn::make('price')->label('Price')->money('idr', true),
            TextColumn::make('room_type')
            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
            ->label('Room Type'),

        IconColumn::make('status')
            ->label('Status')
            ->boolean()
            ->trueIcon('heroicon-o-check-circle')
            ->falseIcon('heroicon-o-x-circle')
            ->trueColor('success')
            ->falseColor('danger')
            ->getStateUsing(fn ($record) =>
        is_array($record->status) ? ($record->status[0] === 'true') : (json_decode($record->status, true)[0] === 'true')
    ),
    ImageColumn::make('image')
    ->label('Room Image')
    ->getStateUsing(fn ($record) => 
    // Pastikan $record->image bukan null dan merupakan array
    is_array($record->image) && !empty($record->image) 
        ? asset('storage/' . (Arr::first(array_values($record->image)) ?? ''))
        : null // Jika kosong, kembalikan null
)
    ->width(100)
    ->height(100),
    ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
