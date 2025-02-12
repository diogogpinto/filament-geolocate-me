<?php

namespace DiogoGPinto\GeolocateMe\Actions;

use DiogoGPinto\GeolocateMe\Data\Address;
use DiogoGPinto\GeolocateMe\Data\Coordinates;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GeocodeAddress
{
    public function execute(?Coordinates $coordinates): ?Address
    {
        if (is_null($coordinates)) {
            return null;
        }

        $response = $this->reverseGeocode($coordinates);

        $address = $response['address'];

        return new Address(
            displayName: $response['display_name'] ?? null,
            road: $address['road'] ?? null,
            neighbourhood: $address['neighbourhood'] ?? null,
            village: $address['village'] ?? null,
            municipality: $address['municipality'] ?? null,
            county: $address['county'] ?? null,
            isoCode: $address['ISO3166-2-lvl6'] ?? null,
            postcode: $address['postcode'] ?? null,
            country: $address['country'] ?? null,
            country_code: $address['country_code'] ?? null,
        );
    }

    private function reverseGeocode(Coordinates $coordinates): ?Response
    {
        $response = Http::withHeaders([
            'User-Agent' => config('app.name'),
        ])
            ->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $coordinates->latitude,
                'lon' => $coordinates->longitude,
                'zoom' => 18,
                'addressdetails' => 1,
            ]);

        if (! $response->successful()) {
            return null;
        }

        return $response;
    }
}
