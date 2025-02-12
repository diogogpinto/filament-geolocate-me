<?php

declare(strict_types=1);

namespace DiogoGPinto\GeolocateMe;

use DiogoGPinto\GeolocateMe\Actions\GetGeolocation;
use Filament\Actions\Action;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class GeolocateMeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-geolocate-me')
            ->hasAssets();

        $this->registerActionMacros();
    }

    public function packageBooted(): void
    {
        $this->registerAssets();
    }

    public function boot(): void
    {
        parent::boot();
        $this->configureActionDefaults();
    }

    private function registerActionMacros(): void
    {
        Action::macro('withGeolocation', function (bool $shouldGeocode = false) {
            /** @var Action $this */
            /** @phpstan-ignore-next-line */
            $beforeCallback = $this->before;
            /** @phpstan-ignore-next-line */
            $actionCallback = $this->action;
            /** @phpstan-ignore-next-line */
            $afterCallback = $this->after;

            return (new GetGeolocation)->execute(
                $this,
                $beforeCallback,
                $actionCallback,
                $afterCallback,
                $shouldGeocode
            );
        });
    }

    private function registerAssets(): void
    {
        FilamentAsset::register(
            assets: [
                AlpineComponent::make(
                    'filament-geolocate-me',
                    __DIR__ . '/../resources/dist/filament-geolocate-me.js'
                ),
            ],
            package: 'diogogpinto/filament-geolocate-me'
        );
    }

    private function configureActionDefaults(): void
    {
        Action::configureUsing(function (Action $action): void {
            $action->extraAttributes(fn ($livewire) => [
                'disabled' => property_exists($livewire, 'waitingForGeolocation') && $livewire->waitingForGeolocation === true,
            ], true);
        });
    }
}
