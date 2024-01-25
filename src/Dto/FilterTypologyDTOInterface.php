<?php

declare(strict_types=1);

namespace App\Dto;

interface FilterTypologyDTOInterface
{
    /** @return array<string> */
    public function getTypologies(): array;
}
