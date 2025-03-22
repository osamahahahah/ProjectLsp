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
                Forms\Components\Toggle::make('status')
                ->label('Status')
                ->onColor('success')
                ->offColor('danger')
                ->dehydrateStateUsing(fn ($state) => [$state ? 'true' : 'false'])
                ->afterStateHydrated(fn ($state, Set $set) =>
                    $set('status', is_array($state) ? $state[0] === 'true' : json_decode($state, true)[0] === 'true')
                )
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
