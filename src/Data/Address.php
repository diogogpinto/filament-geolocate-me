<?php

namespace DiogoGPinto\GeolocateMe\Data;

final readonly class Address
{
    public function __construct(
        public ?string $displayName = null,
        public ?string $road = null,
        public ?string $neighbourhood = null,
        public ?string $village = null,
        public ?string $municipality = null,
        public ?string $county = null,
        public ?string $isoCode = null,
        public ?string $postcode = null,
        public ?string $country = null,
        public ?string $country_code = null,
    ) {}

}
