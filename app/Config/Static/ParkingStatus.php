<?php

namespace App\Config\Static;

class ParkingStatus
{
    // Waiting statuses
    public const WAITING_FOR_HANDOVER             = 'TUNGGU PENYERAHAN';
    public const WAITING_FOR_SPARE_PART           = 'TUNGGU SPARE PART';
    public const WAITING_FOR_SPK                  = 'TUNGGU SPK';
    public const WAITING_FOR_INSURANCE            = 'TUNGGU ASURANSI';
    public const WAITING_FOR_REPAIR               = 'TUNGGU PERBAIKAN';
    public const WAITING_FOR_OPL                  = 'TUNGGU OPL';
    public const WAITING_FOR_PBT                  = 'TUNGGU PBT';
    public const WAITING_FOR_CUSTOMER_CONFIRM     = 'TUNGGU KONFIRMASI CUSTOMER';

    // Other statuses
    public const INTERNAL                          = 'INTERNAL';
    public const GUEST                             = 'TAMU';

    // Work progress steps
    public const BODY_REPAIR                       = 'Body Repair';
    public const PREPARATION                       = 'Preperation';
    public const PAINTING                          = 'Painting';
    public const POLISHING                         = 'Polishing';
    public const WASHING                           = 'Washing';
    public const FINAL_INSPECTION                  = 'Final Inspection';
    public const READY_FOR_DELIVERY                = 'Siap Delivery';

    public static function all(): array
    {
        return [
            self::WAITING_FOR_HANDOVER,
            self::WAITING_FOR_SPARE_PART,
            self::WAITING_FOR_SPK,
            self::WAITING_FOR_INSURANCE,
            self::WAITING_FOR_REPAIR,
            self::WAITING_FOR_OPL,
            self::WAITING_FOR_PBT,
            self::WAITING_FOR_CUSTOMER_CONFIRM,
            self::INTERNAL,
            self::GUEST,

            self::BODY_REPAIR,
            self::PREPARATION,
            self::PAINTING,
            self::POLISHING,
            self::WASHING,
            self::FINAL_INSPECTION,
            self::READY_FOR_DELIVERY,
        ];
    }
}
