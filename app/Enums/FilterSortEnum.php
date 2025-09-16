<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static discounted()
 * @method static static topSell()
 * @method static static orignalPrice()
 */
final class FilterSortEnum extends Enum
{

    const orignalPrice = "orignalPrice";
    const discounted = "discounted";
    const topSell = "topSell";
}
