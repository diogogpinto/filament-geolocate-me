# Filament Geolocate Me

[![Latest Version on Packagist](https://img.shields.io/packagist/v/diogogpinto/filament-geolocate-me.svg?style=flat-square)](https://packagist.org/packages/diogogpinto/filament-geolocate-me)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/diogogpinto/filament-geolocate-me/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/diogogpinto/filament-geolocate-me/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/diogogpinto/filament-geolocate-me/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/diogogpinto/filament-geolocate-me/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/diogogpinto/filament-geolocate-me.svg?style=flat-square)](https://packagist.org/packages/diogogpinto/filament-geolocate-me)



Add geolocation capabilities to any Filament v3 action with a single line of code. This plugin seamlessly integrates browser geolocation with your Filament panels, making it perfect for check-in systems, location-based verification, and geographic data collection.

## Features

🌍 One-line integration with any Filament action
📍 Automatic browser geolocation handling
⚡ Real-time Livewire integration
🛡️ Built-in error handling
🔄 Promise-based architecture
📱 Mobile-friendly
🎯 High-accuracy coordinates

## Installation

You can install the package via composer:

```bash
composer require diogogpinto/filament-geolocate-me
```

## Usage

```php
$geolocateMe = new DiogoGPinto\GeolocateMe();
echo $geolocateMe->echoPhrase('Hello, DiogoGPinto!');
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

- [Diogo Pinto](https://github.com/diogogpinto)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
