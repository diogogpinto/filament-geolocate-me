<?php

namespace DiogoGPinto\GeolocateMe\Data;

use InvalidArgumentException;
use JsonSerializable;

class Coordinates implements JsonSerializable
{
    public function __construct(
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly ?float $accuracy = null,
        public readonly ?string $error = null,
    ) {}

    public static function fromArray(array $data): self
    {
        if (isset($data['error'])) {
            return new self(error: $data['error']);
        }

        try {
            self::validate($data);

            return new self(
                $data['latitude'],
                $data['longitude'],
                $data['accuracy'] ?? null
            );
        } catch (InvalidArgumentException $e) {
            return new self(error: $e->getMessage());
        }
    }

    public function hasError(): bool
    {
        return $this->error !== null;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'accuracy' => $this->accuracy,
            'error' => $this->error,
        ];
    }

    private static function validate(array $data): void
    {
        if (! self::isValid($data)) {
            throw new InvalidArgumentException('Invalid coordinates provided');
        }
    }

    private static function isValid(array $data): bool
    {
        return isset($data['latitude'], $data['longitude'])
            && is_numeric($data['latitude'])
            && is_numeric($data['longitude'])
            && $data['latitude'] >= -90
            && $data['latitude'] <= 90
            && $data['longitude'] >= -180
            && $data['longitude'] <= 180;
    }
}
