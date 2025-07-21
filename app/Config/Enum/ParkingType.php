<?php

namespace App\Config\Enum;

class ParkingType
{
    public const PARKIRAN_BP            = 'Parkiran BP';
    public const PARKIRAN_GR            = 'Parkiran GR';
    public const PARKIRAN_BAYANGAN_GR   = 'Parkiran Bayangan GR';
    public const PARKIRAN_BAYANGAN_BP   = 'Parkiran Bayangan BP';
    public const STALL_GR               = 'Stall GR';
    public const STALL_BP               = 'Stall BP';
    public const PARKIRAN_AKM           = 'Parkiran AKM';
    public const PARKIRAN_BAYANGAN_AKM  = 'Parkiran Bayangan AKM';

    public static function all(): array
    {
        return [
            self::PARKIRAN_BP,
            self::PARKIRAN_GR,
            self::PARKIRAN_BAYANGAN_GR,
            self::PARKIRAN_BAYANGAN_BP,
            self::STALL_GR,
            self::STALL_BP,
            self::PARKIRAN_AKM,
            self::PARKIRAN_BAYANGAN_AKM,
        ];
    }
}
