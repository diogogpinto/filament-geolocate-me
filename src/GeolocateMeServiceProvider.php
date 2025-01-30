<?php

namespace DiogoGPinto\GeolocateMe;

use Filament\Actions\Action;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GeolocateMeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-geolocate-me')
            ->hasAssets();

        Action::macro('withGeolocation', function (): static {
            /** @var Action $this */
            $this->extraAttributes([
                'x-ignore' => '',
                'ax-load' => 'true',
                'x-on:click' => 'getLocation',
                'ax-load-src' => FilamentAsset::getAlpineComponentSrc('filament-geolocate-me', 'diogogpinto/filament-geolocate-me'),
                'x-data' => 'filamentGeolocateMe',
            ]);

            return $this;
        });

    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            assets: [
                AlpineComponent::make('filament-geolocate-me', __DIR__ . '/../resources/dist/filament-geolocate-me.js'),
            ],
            package: 'diogogpinto/filament-geolocate-me'
        );
    }
}
