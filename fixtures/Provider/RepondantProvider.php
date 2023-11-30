<?php

declare(strict_types=1);

namespace DataFixtures\Provider;

use Faker\Provider\Base;

class RepondantProvider extends Base
{
    protected static $departements = [
        'Ardennes',
        'Aube',
        'Marne',
        'Haute-Marne',
        'Meurthe-et-Moselle',
        'Meuse',
        'Moselle',
        'Bas-Rhin',
        'Haut-Rhin',
    ];

    public static function departement(): string
    {
        return static::randomElement(static::$departements);
    }
    protected static $typologies = [
        'hotel',
        'location',
        'chambre',
        'camping',
        'insolite',
        'visite',
        'activite',
        'restaurant',
    ];

    public static function typologie(): string
    {
        return static::randomElement(static::$typologies);
    }
}
