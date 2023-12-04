<?php

declare(strict_types=1);

namespace App\Enum;

enum PilierEnum: string
{
    case ENVIRONNEMENT = 'environnement';

    case ECONOMIE = 'economie';
    case SOCIAL = 'social';
}
