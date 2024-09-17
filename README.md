# Plugin for elegantly/laravel-kpi

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/filament-kpi.svg?style=flat-square)](https://packagist.org/packages/elegantly/filament-kpi)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/filament-kpi/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elegantengineeringtech/filament-kpi/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/filament-kpi/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elegantengineeringtech/filament-kpi/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/filament-kpi.svg?style=flat-square)](https://packagist.org/packages/elegantly/filament-kpi)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require elegantengineeringtech/filament-kpi
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-kpi-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-kpi-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-kpi-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentKpi = new Elegantly\FilamentKpi();
echo $filamentKpi->echoPhrase('Hello, Elegantly!');
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
