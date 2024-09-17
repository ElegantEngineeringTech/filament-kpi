<?php

namespace Elegantly\FilamentKpi\Widgets;

use Brick\Money\Money;
use Elegantly\Kpi\Contracts\HasDifference;
use Elegantly\Kpi\Enums\KpiInterval;
use Elegantly\Kpi\KpiDefinition;
use Elegantly\Kpi\KpiValue;
use Elegantly\Kpi\Models\Kpi;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\App;

class KpiStat extends Stat
{
    /**
     * @param  class-string<KpiDefinition>  $definition
     */
    public static function kpi(
        string $definition,
        ?KpiInterval $interval = null,
        ?bool $diff = null,
    ): static {
        $label = $definition::getLabel();

        $interval ??= $definition::getSnapshotInterval();

        $diff ??= is_subclass_of($definition, HasDifference::class);

        if ($diff) {
            $kpis = $definition::getDiffPeriod(
                start: now()->sub($interval->toUnit(), 6),
                end: now(),
                interval: $interval,
            );
        } else {
            $kpis = $definition::getPeriod(
                start: now()->sub($interval->toUnit(), 6),
                end: now(),
                interval: $interval,
            );
        }

        $chart = $kpis->map(function (Kpi | KpiValue $kpi) {
            if ($kpi->value instanceof Money) {
                return $kpi->value->getAmount()->toFloat();
            }
            if (is_float($kpi->value) || is_int($kpi->value)) {
                return $kpi->value;
            }

            return null;
        });

        $last = $definition::query()
            ->latest('date')
            ->first();

        return static::make(
            $label,
            static::formatValue($last?->value ?? '-')
        )->chart($chart->toArray());
    }

    public static function formatValue(mixed $value)
    {
        if ($value instanceof Money) {
            return $value->formatTo(App::getLocale());
        }

        if (is_int($value) || is_float($value) || is_string($value)) {
            return $value;
        }

        return null;
    }
}
