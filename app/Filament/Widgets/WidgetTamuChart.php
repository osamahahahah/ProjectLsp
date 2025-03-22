<?php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\reservation;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class WidgetTamuChart extends ChartWidget
{
    protected static ?string $heading = 'Tamu';
    use InteractsWithPageFilters;


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
        ->sum(column: 'qty_person');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Tamu',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
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
