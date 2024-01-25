<?php

declare(strict_types=1);

namespace App\Enum;

enum TerritoireAreaEnum: string
{
    case REGION = 'region';

    case DEPARTEMENT = 'departement';

    case OT = 'territoire';

    case TOURISME = 'tourisme';
}
