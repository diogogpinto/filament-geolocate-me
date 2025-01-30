<?php

namespace DiogoGPinto\GeolocateMe\Traits;

use Livewire\Attributes\On;

trait HandlesGeolocation
{
    public ?array $coords;

    public ?string $geolocationError;

    #[On('geolocationSuccess')]
    public function geolocationSuccess($coords)
    {
        dd($coords);
        $this->coords = $coords;
        $this->geolocationError = null;
    }

    #[On('geolocationError')]
    public function geolocationError($message)
    {
        dd($message);
        $this->geolocationError = $message;
        $this->coords = null;
    }
}
