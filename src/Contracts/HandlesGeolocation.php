<?php

declare(strict_types=1);

namespace DiogoGPinto\GeolocateMe\Contracts;

use DiogoGPinto\GeolocateMe\Data\Coordinates;

interface HandlesGeolocation
{
    public function getCoordinates(): ?Coordinates;

    public function setCoordinates(?Coordinates $coordinates): void;

    public function setPendingAction(?string $action): void;

    public function getPendingAction(): ?string;

    public function isWaitingForGeolocation(): bool;

    public function executePendingAction(): void;
}
