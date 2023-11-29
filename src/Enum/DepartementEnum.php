<?php

declare(strict_types=1);

namespace App\Enum;

enum DepartementEnum: string
{
    case ARDENNES = 'ardennes';
    case AUBE = 'aube';
    case MARNE = 'marne';
    case HAUTE_MARNE = 'haute-marne';
    case MEURTHE_ET_MOSELLE = 'meurthe-et-moselle';
    case MEUSE = 'meuse';
    case MOSELLE = 'moselle';
    case BAS_RHIN = 'bas-rhin';
    case HAUT_RHIN = 'haut-rhin';
    case VOSGES = 'vosges';

    public static function getLabel(DepartementEnum $value): string
    {
        return match ($value) {
            self::ARDENNES => 'Ardennes',
            self::AUBE => 'Aube',
            self::MARNE => 'Marne',
            self::HAUTE_MARNE => 'Haute-Marne',
            self::MEURTHE_ET_MOSELLE => 'Meurthe-et-Moselle',
            self::MEUSE => 'Meuse',
            self::MOSELLE => 'Moselle',
            self::BAS_RHIN => 'Bas-Rhin',
            self::HAUT_RHIN => 'Haut-Rhin',
            self::VOSGES => 'Vosges',
        };
    }

    public static function getCode(DepartementEnum $value): string
    {
        return match ($value) {
            self::ARDENNES => "08",
            self::AUBE => "10",
            self::MARNE => "51",
            self::HAUTE_MARNE => "52",
            self::MEURTHE_ET_MOSELLE => "54",
            self::MEUSE => "55",
            self::MOSELLE => "57",
            self::BAS_RHIN => "67",
            self::HAUT_RHIN => "68",
            self::VOSGES => "88",
        };
    }
}
