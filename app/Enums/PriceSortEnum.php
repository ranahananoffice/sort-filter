<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static HIGHTOLOW()
 * @method static static LOWTOHIGH()
 */
final class PriceSortEnum extends Enum
{

    const highToLow = "highToLow";
    const lowToHigh = "lowToHigh";
}
