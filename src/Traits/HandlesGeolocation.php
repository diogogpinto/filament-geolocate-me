<?php

namespace DiogoGPinto\GeolocateMe\Traits;

use DiogoGPinto\GeolocateMe\Data\Coordinates;
use Livewire\Attributes\On;

trait HandlesGeolocation
{
    protected ?Coordinates $coords = null;

    public ?string $geolocateMePendingAction = null;

    public ?bool $waitingForAction = false;

    public function getCoords(): ?Coordinates
    {
        return $this->coords;
    }

    #[On('geolocationFromAlpine')]
    public function geolocationFromAlpine(array $coordinatesData): void
    {
        $this->coords = Coordinates::fromArray($coordinatesData);
        $this->executePendingAction();
    }

    private function executePendingAction(): void
    {
        $this->waitingForAction = false;
        if (! is_null($this->geolocateMePendingAction)) {
            $this->callMountedAction();
        }
        $this->resetGeolocationData();
    }

    public function resetGeolocationData(): void
    {
        $this->coords = null;
        $this->geolocateMePendingAction = null;
        $this->waitingForAction = false;
    }
}
