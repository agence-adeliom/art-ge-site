<?php

declare(strict_types=1);

namespace App\Services;

function sumArrayOfIntegers(array $points): int
{
    return array_reduce($points, fn (int $carry, int $item): int => $carry + $item, 0);
}
