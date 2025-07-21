<?php

namespace App\Config\Enum;

class ServiceCategory
{
    public const GR     = 'GR';
    public const BP     = 'BP';
    public const AKM    = 'AKM';
    public const NONE   = 'none';

    public static function all(): array
    {
        return [
            self::GR,
            self::BP,
            self::AKM,
            self::NONE,
        ];
    }
}
