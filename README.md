# Plugin for elegantly/laravel-kpi

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/filament-kpi.svg?style=flat-square)](https://packagist.org/packages/elegantly/filament-kpi)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/filament-kpi/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elegantengineeringtech/filament-kpi/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/filament-kpi/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elegantengineeringtech/filament-kpi/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/filament-kpi.svg?style=flat-square)](https://packagist.org/packages/elegantly/filament-kpi)

This plugin allows you to create charts and stats using [`elegantly/laravel-kpi`](https://github.com/ElegantEngineeringTech/laravel-kpi) like this:

```php
use Elegantly\FilamentKpi\Widgets\KpiStat;
use App\Kpis\Users\UsersCountKpi;

KpiStat::kpi(UsersCountKpi::class);
```

```php
class UsersChart extends KpiChart
{
    protected static string $kpi = UsersCountKpi::class;
}

```

## Installation

You can install the package via composer:

```bash
composer require elegantly/filament-kpi
```

## Usage

### Display a Kpi Stat

```php
namespace App\Filament\Resources\UserResource\Widgets;

use Elegantly\FilamentKpi\Widgets\KpiStat;
use App\Kpis\Users\UsersCountKpi;
use Filament\Widgets\StatsOverviewWidget;

class UsersStatsOverview extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        return [
            KpiStat::kpi(
                definition: UsersCountKpi::class,
                interval: KpiInterval::Day, // (optional) default to UsersCountKpi::getSnapshotInterval()
                diff: true, // (optional) default to is_subclass_of(UsersCountKpi::class, HasDifference::class)
            ),
        ];
    }
}
```

### Display a Kpi Chart

```php
namespace App\Filament\Resources\UserResource\Widgets;

use Elegantly\FilamentKpi\Widgets\KpiChart;
use App\Kpis\Users\UsersCountKpi;

class UsersChart extends KpiChart
{
    protected static string $kpi = UsersCountKpi::class;
}

```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Quentin Gabriele](https://github.com/QuentinGab)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
