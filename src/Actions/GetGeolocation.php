<?php

declare(strict_types=1);

namespace DiogoGPinto\GeolocateMe\Actions;

use Closure;
use DiogoGPinto\GeolocateMe\Data\Address;
use DiogoGPinto\GeolocateMe\Data\Coordinates;
use Filament\Actions\Action;
use Filament\Support\Facades\FilamentAsset;

class GetGeolocation
{
    public function execute(Action $action, ?Closure $beforeCallback, ?Closure $actionCallback, ?Closure $afterCallback, bool $shouldGeocode): Action
    {
        return $action
            ->before(function (Action $action, $livewire) use ($beforeCallback, $shouldGeocode) {
                if (is_null($livewire->getPendingAction())) {
                    $action->icon(function () {
                        return view('filament::components.loading-indicator', [
                            'attributes' => new \Illuminate\View\ComponentAttributeBag,
                        ]);
                    });
                    $action->action(null);
                    $action->disabled();
                    $livewire->shouldGeocode = $shouldGeocode;
                    $livewire->setPendingAction($action->getName());
                    $livewire->startWaitingForAction();
                    $livewire->dispatch('getLocationFromAlpine');
                    $action->halt();
                }

                $dependencies = [
                    Coordinates::class => $livewire->getCoordinates(),
                ];

                if ($livewire->shouldGeocode) {
                    $dependencies[Address::class] = $livewire->getAddress();
                }

                return $action->evaluate($beforeCallback, [], $dependencies);
            })
            ->action(function (Action $action, $livewire) use ($actionCallback) {
                if ($livewire->isWaitingForGeolocation()) {
                    return null;
                }

                $dependencies = [
                    Coordinates::class => $livewire->getCoordinates(),
                ];

                if ($livewire->shouldGeocode) {
                    $dependencies[Address::class] = $livewire->getAddress();
                }

                return $action->evaluate($actionCallback, [], $dependencies);
            })
            ->after(function (Action $action, $livewire) use ($afterCallback) {
                if ($livewire->isWaitingForGeolocation()) {
                    return null;
                }

                $dependencies = [
                    Coordinates::class => $livewire->getCoordinates(),
                ];

                if ($livewire->shouldGeocode) {
                    $dependencies[Address::class] = $livewire->getAddress();
                }

                return $action->evaluate($afterCallback, [], $dependencies);
            })
            ->extraAttributes([
                'x-data' => 'geolocateMe()',
                'x-ignore' => '',
                'ax-load' => '',
                'ax-load-src' => FilamentAsset::getAlpineComponentSrc(
                    'filament-geolocate-me',
                    'diogogpinto/filament-geolocate-me'
                ),
            ]);
    }
}
