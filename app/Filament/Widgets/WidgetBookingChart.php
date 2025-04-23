<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\reservation;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Testing\Fluent\Concerns\Interaction;

class WidgetBookingChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Booking';
    protected static bool $isCollapsible = false;



    protected function getData(): array
    {

        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

        $data = Trend::model(reservation::class)
            ->between(
                start: $start ? Carbon::parse($start) : now()->subMonth(6),
                end: $end ? Carbon::parse($end) : now()->endOfYear(),
            )
            ->perDay()
            ->count(column: 'check_in');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Booking',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#2196F3',
                    'borderWidth' => 2,
                    'pointBackgroundColor' => '#2196F3',
                    'pointBorderColor' => '#ffffff',
                    'pointHoverBackgroundColor' => '#ffffff',
                    'pointHoverBorderColor' => '#2196F3',
                    'tension' => 0.3,
                    'fill' => false,
                ],
                
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }



    protected function getType(): string
    {
        return 'line';
    }
}
