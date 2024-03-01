<?php

declare(strict_types=1);

namespace App\Enum;

enum DepartementEnum: string
{
    case ALSACE = 'alsace';
    case ARDENNES = 'ardennes';
    case AUBE = 'aube';
    case MARNE = 'marne';
    case HAUTE_MARNE = 'haute-marne';
    case MEURTHE_ET_MOSELLE = 'meurthe-et-moselle';
    case MEUSE = 'meuse';
    case MOSELLE = 'moselle';
    case VOSGES = 'vosges';

    public static function getLabel(DepartementEnum $value): string
    {
        return match ($value) {
            self::ALSACE => 'Alsace',
            self::ARDENNES => 'Ardennes',
            self::AUBE => 'Aube',
            self::MARNE => 'Marne',
            self::HAUTE_MARNE => 'Haute-Marne',
            self::MEURTHE_ET_MOSELLE => 'Meurthe-et-Moselle',
            self::MEUSE => 'Meuse',
            self::MOSELLE => 'Moselle',
            self::VOSGES => 'Vosges',
        };
    }

    public static function getCode(DepartementEnum $value): string
    {
        return match ($value) {
            self::ALSACE => '67|68',
            self::ARDENNES => '08',
            self::AUBE => '10',
            self::MARNE => '51',
            self::HAUTE_MARNE => '52',
            self::MEURTHE_ET_MOSELLE => '54',
            self::MEUSE => '55',
            self::MOSELLE => '57',
            self::VOSGES => '88',
        };
    }
}
