<?php

namespace DiogoGPinto\GeolocateMe;

use Filament\Actions\Action;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * @method static \Filament\Actions\Action withGeolocation()
 */
class GeolocateMeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-geolocate-me')
            ->hasAssets();

        Action::macro('withGeolocation', function () {
            /** @var Action $this */
            return $this
                ->before(function (Action $action, $livewire) {
                    if (! is_null($livewire->geolocateMePendingAction)) {
                        return;
                    }
                    $action->action(null);
                    $action->icon(function () {
                        return view('filament::components.loading-indicator', [
                            'attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'animate-spin']),
                        ]);
                    });
                    $livewire->geolocateMePendingAction = $action->getName();
                    $livewire->dispatch('getLocationFromAlpine');
                })
                ->disabled(fn ($livewire): bool => ! is_null($livewire->geolocateMePendingAction))
                ->extraAttributes([
                    'x-data' => 'geolocateMe()',
                    'x-ignore' => '',
                    'ax-load' => '',
                    'ax-load-src' => FilamentAsset::getAlpineComponentSrc('filament-geolocate-me', 'diogogpinto/filament-geolocate-me'),
                ], true);
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
