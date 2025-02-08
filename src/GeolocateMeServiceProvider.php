<?php

namespace DiogoGPinto\GeolocateMe;

use DiogoGPinto\GeolocateMe\Data\Coordinates;
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
            $beforeCallback = $this->before;
            $actionCallback = $this->action;
            $afterCallback = $this->after;

            return $this
                ->before(function (Action $action, $livewire) use ($beforeCallback) {
                    if (is_null($livewire->geolocateMePendingAction)) {
                        $action->icon(function () {
                            return view('filament::components.loading-indicator', [
                                'attributes' => new \Illuminate\View\ComponentAttributeBag,
                            ]);
                        });
                        $livewire->geolocateMePendingAction = $action->getName();
                        $livewire->waitingForAction = true;
                        $livewire->dispatch('getLocationFromAlpine');
                        $action->halt();
                    }

                    return $action->evaluate($beforeCallback, [], [
                        Coordinates::class => $livewire->getCoords(),
                    ]);
                })
                ->action(function (Action $action, $livewire) use ($actionCallback) {
                    if ($livewire->waitingForAction) {
                        return null;
                    }

                    return $action->evaluate($actionCallback, [], [
                        Coordinates::class => $livewire->getCoords(),
                    ]);
                })
                ->after(function (Action $action, $livewire) use ($afterCallback) {
                    if ($livewire->waitingForAction) {
                        return null;
                    }

                    return $action->evaluate($afterCallback, [], [
                        Coordinates::class => $livewire->getCoords(),
                    ]);
                })
                ->extraAttributes([
                    'x-data' => 'geolocateMe()',
                    'x-ignore' => '',
                    'ax-load' => '',
                    'ax-load-src' => FilamentAsset::getAlpineComponentSrc('filament-geolocate-me', 'diogogpinto/filament-geolocate-me'),
                ], true);
        });
    }

    public function boot(): void
    {
        parent::boot();

        Action::configureUsing(function (Action $action): void {
                $action->extraAttributes(fn ($livewire) => [
                    'disabled' => property_exists($livewire, 'waitingForAction') && $livewire->waitingForAction === true,
                ], true);;
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
