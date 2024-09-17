<?php

namespace Elegantly\FilamentKpi\Widgets;

use Brick\Money\Money;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Elegantly\Kpi\Contracts\HasDifference;
use Elegantly\Kpi\Enums\KpiInterval;
use Elegantly\Kpi\KpiDefinition;
use Elegantly\Kpi\KpiValue;
use Elegantly\Kpi\Models\Kpi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection as SupportCollection;

abstract class KpiChart extends ChartWidget
{
    /**
     * @var class-string<KpiDefinition>
     */
    protected static string $kpi;

    public ?string $filter = 'year';

    protected function getType(): string
    {
        return 'bar';
    }

    public function getHeading(): ?string
    {
        return static::$kpi::getLabel();
    }

    /**
     * @return SupportCollection<string, null|Kpi>
     */
    public function getKpis(): SupportCollection
    {
        $interval = $this->getInterval();
        $period = $this->getPeriod();

        return static::$kpi::getPeriod(
            start: $period->getIncludedStartDate(),
            end: $period->getEndDate(),
            interval: $interval
        );
    }

    /**
     * @return SupportCollection<string, KpiValue>
     */
    public function getDiffKpis(): SupportCollection
    {
        $interval = $this->getInterval();
        $period = $this->getPeriod();

        return static::$kpi::getDiffPeriod(
            start: $period->getIncludedStartDate(),
            end: $period->getEndDate(),
            interval: $interval
        );
    }

    protected function getDatasets(SupportCollection $kpis): array
    {
        return [
            [
                'label' => $this->getHeading(),
                'data' => $kpis->pluck('value')->map(fn ($value) => static::formatValue($value))->toArray(),
                'type' => 'line',
            ],
        ];
    }

    public function getDiffDatasets(SupportCollection $kpis): array
    {
        return [
            [
                'label' => $this->getHeading() . ' - difference',
                'data' => $kpis->pluck('value')->map(fn ($value) => static::formatValue($value))->toArray(),
                'pointRadius' => 0,
                'yAxisID' => 'y_diff',
            ],
        ];
    }

    public static function formatValue(mixed $value): null | float | int | string
    {
        if (is_int($value) || is_float($value) || is_string($value)) {
            return $value;
        }
        if ($value instanceof Money) {
            return $value->getAmount()->toFloat();
        }

        return null;
    }

    protected function getData(): array
    {
        $interval = $this->getInterval();

        $kpis = $this->getKpis();

        $datasets = collect($this->getDatasets($kpis))
            ->when(
                is_subclass_of(static::$kpi, HasDifference::class),
                fn ($datasets) => $datasets->push(...$this->getDiffDatasets($this->getDiffKpis()))
            );

        $labels = $kpis->keys()->map(function (string $time) use ($interval) {
            $date = Carbon::createFromFormat($interval->toDateFormat(), $time);

            return $date->isoFormat($this->getLabelFormat());
        });

        return [
            'datasets' => $datasets->toArray(),
            'labels' => $labels->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
                'y' => [
                    'display' => 'auto',
                    'beginAtZero' => true,
                ],
                'y_diff' => [
                    'display' => 'auto',
                    'type' => 'linear',
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false, // only want the grid lines for one axis to show up
                    ],
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Last 24h',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'Last year',
            '5 years' => 'Last 5 years',
        ];
    }

    public function getInterval(): KpiInterval
    {
        return match ($this->filter) {
            'day' => KpiInterval::Hour,
            'week' => KpiInterval::Day,
            'month' => KpiInterval::Day,
            'year' => KpiInterval::Month,
            '5 years' => KpiInterval::Month,
            default => KpiInterval::Day,
        };
    }

    public function getLabelFormat(): string
    {
        return match ($this->filter) {
            'day' => 'HH[h]mm',
            'week' => 'L',
            'month' => 'L',
            'year' => 'MMM YYYY',
            '5 years' => 'MMM YYYY',
            default => 'HH[h]mm',
        };
    }

    public function getPeriod(): CarbonPeriod
    {
        $interval = $this->getInterval();

        return match ($this->filter) {
            'day' => $interval->toPeriod(
                start: now()->subDay(),
                end: now()
            )->excludeStartDate(),
            'week' => $interval->toPeriod(
                start: now()->subWeek(),
                end: now()
            )->excludeStartDate(),
            'month' => $interval->toPeriod(
                start: now()->subMonth(),
                end: now()
            )->excludeStartDate(),
            'year' => $interval->toPeriod(
                start: now()->subYear(),
                end: now()
            )->excludeStartDate(),
            '5 years' => $interval->toPeriod(
                start: now()->subYears(5),
                end: now()
            )->excludeStartDate(),
            default => $interval->toPeriod(
                start: now()->subWeek(),
                end: now()
            )->excludeStartDate()
        };
    }
}
