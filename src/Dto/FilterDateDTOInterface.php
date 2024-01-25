<?php

declare(strict_types=1);

namespace App\Dto;

interface FilterDateDTOInterface
{
    public function getFrom(): ?\DateTimeImmutable;

    public function getTo(): ?\DateTimeImmutable;

    public function hasDateRange(): bool;
}
