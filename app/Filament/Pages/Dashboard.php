<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use Psy\Readline\Hoa\Protocol;
use Symfony\Component\Routing\Loader\ProtectedPhpFileLoader;




class Dashboard extends BaseDashboard
{

    use BaseDashboard\Concerns\HasFiltersForm;


    public function filtersForm(Form $form): Form
{
    return $form
        ->schema([
            Section::make()
                ->schema([
                    DatePicker::make('startDate')
                        ->label('Tanggal Mulai')
                        ->maxDate(fn (Get $get) => $get('endDate')),

                    DatePicker::make('endDate')
                        ->label('Tanggal Akhir')
                        ->minDate(fn (Get $get) => $get('startDate'))
                        ->maxDate(now()),
                ])
                ->columns(2),
        ]);
}


}
