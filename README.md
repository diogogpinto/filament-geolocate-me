# Filament Geolocate Me

[![Latest Version on Packagist](https://img.shields.io/packagist/v/diogogpinto/filament-geolocate-me.svg?style=flat-square)](https://packagist.org/packages/diogogpinto/filament-geolocate-me)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/diogogpinto/filament-geolocate-me/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/diogogpinto/filament-geolocate-me/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/diogogpinto/filament-geolocate-me/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/diogogpinto/filament-geolocate-me/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/diogogpinto/filament-geolocate-me.svg?style=flat-square)](https://packagist.org/packages/diogogpinto/filament-geolocate-me)

## Want to get a user's location before an Action is performed on Filament?

This Filament plugin adds geolocation capabilities to your Filament Actions, allowing you to easily capture and use a user's geographic location. Also, it's plug and play! 🚀

* 🎯 Gets real-time user location with browser's Geolocation API  
* ⚡ Halts action execution until location is captured  
* 🔄 Automatically disables other actions while waiting  
* 🛠 Injects location data into `before()`, `action()`, and `after()` methods  
* 🔒 Built-in validation and error handling  
* 📦 Zero configuration required - works out of the box
* 🎨 Filament-Native Experience

![](art/screencast.gif)

## Navigation
* [Filament Geolocate Me](#filament-geolocate-me)
  * [Installation](#installation)
  * [Usage](#usage)
    * [Basic Usage](#basic-usage)
    * [Error Handling](#error-handling)
    * [Action Lifecycle](#action-lifecycle)
    * [Coordinates Data Structure](#coordinates-data-structure)
    * [Validation](#validation)
    * [Custom Styling](#custom-styling)
  * [Requirements](#requirements)
  * [Browser Support](#browser-support)
  * [Security](#security)
  * [Production Usage Warning](#production-usage-warning)
  * [Examples](#examples)
    * [Basic Check-in System](#basic-check-in-system)
  * [Credits](#credits)
  * [License](#license)

## Installation

You can install the package via composer:

```bash
composer require diogogpinto/filament-geolocate-me
```

## Usage

### Basic Usage

Import the HasGeolocation trait in the Livewire component where your Filament Action is and add geolocation to any Filament action using the `withGeolocation()` method:

```php
use Filament\Actions\Action;
use DiogoGPinto\GeolocateMe\Data\Coordinates;

//Import the HasGeolocation trait
use HasGeolocation;

Action::make('checkIn')
    ->withGeolocation()
    ->action(function (Coordinates $coordinates) {
        // Handle the location data in your action
        $latitude = $coordinates->latitude;
        $longitude = $coordinates->longitude;
        $accuracy = $coordinates->accuracy; // in meters, optional
    });
    ->before(function (Coordinates $coordinates) {
        // Do something with location data before running the action
    });
    ->after(function (Coordinates $coordinates) {
        // Do something with location data after running the action
    });
```

> [!CAUTION]
> This plugin only works with custom Filament Actions. It is NOT compatible with Filament's prebuilt actions (like EditAction, CreateAction, etc.)


> [!TIP]
> Location data (Coordinates) can be accessed in three action phases: before, action and after.

### Error Handling

The plugin automatically handles geolocation errors. You can check for errors in your action:

```php
Action::make('checkIn')
    ->withGeolocation()
    ->action(function (Coordinates $coordinates) {
        if ($coordinates->hasError()) {
            // Handle error case
            $errorMessage = $coordinates->error;
            return;
        }
        
        // Process location data
    });
```

### Action Lifecycle

The geolocation process follows this sequence:
1. Action is triggered and immediately halted
2. All other actions are temporarily disabled until the location process finishes
3. Browser requests location permission
4. Filament waits for location from Alpine
5. Loading state is shown
6. Location is captured and the action is fired
7. Action is executed with location data injected

### Coordinates Data Structure

The `Coordinates` class provides the following properties:

```php
$coordinates->latitude;   // float: -90 to 90
$coordinates->longitude;  // float: -180 to 180
$coordinates->accuracy;   // float|null: accuracy in meters
$coordinates->error;      // string|null: error message if something went wrong
```

### Validation

The plugin automatically validates coordinates to ensure they are within valid ranges:
- Latitude: -90 to 90 degrees
- Longitude: -180 to 180 degrees

### Custom Styling

The plugin uses Filament's default loading indicators. You can customize the appearance through Filament's theming system.

## Examples

### Basic Check-in System

```php
use DiogoGPinto\GeolocateMe\Data\Coordinates;

Action::make('checkIn')
    ->withGeolocation()
    ->action(function (Coordinates $coordinates) {
        //Handle Error - if you can't locate the user, do nothing
        if ($location->hasError()) {
            Notification::make()
                ->danger()
                ->title('Error retrieving location')
                ->body($location->error)
                ->send();

            return;
        }

        //Create a checkin
        CheckIn::create([
            'latitude' => $coordinates->latitude,
            'longitude' => $coordinates->longitude,
            'accuracy' => $coordinates->accuracy,
            'user_id' => auth()->id(),
        ]);
        
        Notification::make()
            ->success()
            ->title('Checked in successfully')
            ->send();
    });
```

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- FilamentPHP 3.0 or higher
- Browser with Geolocation API support

## Browser Support

This plugin relies on the [Geolocation API](https://developer.mozilla.org/en-US/docs/Web/API/Geolocation_API), which is supported by all modern browsers. Users will need to grant location permissions for the feature to work.

## Security

The plugin handles location data on the client side only when explicitly requested through an action. All coordinate validation is performed server-side to ensure data integrity.

## Production Usage Warning

> [!CAUTION]
> This plugin is currently in its early stages (v0.1) and should be used with caution in production environments. While it has been tested in basic scenarios, it may contain bugs or unexpected behaviors. We recommend:

1. Thoroughly testing the plugin in your specific use case
2. Having proper error handling in place
3. Testing across different browsers and devices
4. Having a fallback mechanism in case of geolocation failures
5. Monitoring for any issues in production
6. Keeping the plugin updated for security patches and improvements

Please report any issues or suggestions on our [GitHub Issues](https://github.com/diogogpinto/filament-geolocate-me/issues) page.

## Credits

- [Diogo Pinto](https://github.com/diogogpinto)
- [Geridoc](https://www.geridoc.pt) & [Geribox](https://www.geribox.pt) for allowing me to release our packages with Open Source licenses
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
