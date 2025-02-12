<?php

namespace DiogoGPinto\GeolocateMe\Concerns;

use DiogoGPinto\GeolocateMe\Actions\GeocodeAddress;
use DiogoGPinto\GeolocateMe\Data\Address;
use DiogoGPinto\GeolocateMe\Data\Coordinates;
use Livewire\Attributes\On;

trait HasGeolocation
{
    protected ?Coordinates $coordinates = null;

    protected ?Address $address = null;

    public ?string $pendingGeolocationAction = null;

    public bool $waitingForGeolocation = false;

    public bool $shouldGeocode = false;

    public function getCoordinates(): ?Coordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(?Coordinates $coordinates): void
    {
        $this->coordinates = $coordinates;
        // Reset address when coordinates change
        $this->address = null;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function startGeocoding(): void
    {
        if ($this->address === null && $this->coordinates !== null && ! $this->coordinates->hasError()) {
            $this->address = (new GeocodeAddress)->execute($this->coordinates);
        }
    }

    public function setPendingAction(?string $action): void
    {
        $this->pendingGeolocationAction = $action;
        $this->waitingForGeolocation = true;
    }

    public function getPendingAction(): ?string
    {
        return $this->pendingGeolocationAction;
    }

    public function isWaitingForGeolocation(): bool
    {
        return $this->waitingForGeolocation;
    }

    public function startWaitingForAction(): void
    {
        $this->waitingForGeolocation = true;
    }

    #[On('geolocationFromAlpine')]
    public function handleGeolocationResponse(array $coordinatesData): void
    {
        $this->setCoordinates(Coordinates::fromArray($coordinatesData));

        if ($this->shouldGeocode) {
            $this->startGeocoding();
        }
        $this->executePendingAction();
    }

    public function executePendingAction(): void
    {
        $this->waitingForGeolocation = false;
        $this->callMountedAction();

        $this->resetGeolocationState();
    }

    protected function resetGeolocationState(): void
    {
        $this->coordinates = null;
        $this->address = null;
        $this->pendingGeolocationAction = null;
        $this->waitingForGeolocation = false;
    }
}
