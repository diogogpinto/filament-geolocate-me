<?php

namespace DiogoGPinto\GeolocateMe\Traits;

use DiogoGPinto\GeolocateMe\Data\Coordinates;
use Filament\Actions\Concerns\InteractsWithActions;
use InvalidArgumentException;
use Livewire\Attributes\On;

trait HandlesGeolocation
{
    use InteractsWithActions;

    protected ?Coordinates $coords = null;

    public ?string $geolocateMePendingAction = null;

    public function getCoords(): ?Coordinates
    {
        return $this->coords;
    }

    #[On('geolocationFromAlpine')]
    public function geolocationFromAlpine(array $data): void
    {
        $this->executePendingAction(Coordinates::fromArray($data));
    }

    private function executePendingAction(Coordinates $location): void
    {
        if ($this->geolocateMePendingAction) {
            $this->getAction($this->geolocateMePendingAction)->call([
                'location' => $location,
            ]);
            $this->getAction($this->geolocateMePendingAction)->callAfter();
            $this->geolocateMePendingAction = null;
        }
    }
}
