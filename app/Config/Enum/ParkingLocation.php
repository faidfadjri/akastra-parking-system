<?php

namespace App\Config\Enum;

class ParkingLocation
{
    public const DEPAN      = 'DEPAN';
    public const STALL_BP   = 'STALL_BP';
    public const STALL_GR   = 'STALL_GR';
    public const AKM        = 'AKM';

    public static function all(): array
    {
        return [
            self::DEPAN,
            self::STALL_BP,
            self::STALL_GR,
            self::AKM,
        ];
    }
}
