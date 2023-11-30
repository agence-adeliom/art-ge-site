<?php

declare(strict_types=1);

namespace App\Enum;

enum TypologieEnum: string
{
    case HOTEL = 'hotel';

    case LOCATION = 'location';
    case CHAMBRE = 'chambre';
    case CAMPING = 'camping';
    case INSOLITE = 'insolite';
    case VISITE = 'visite';
    case ACTIVITE = 'activite';
    case RESTAURANT = 'restaurant';
}
