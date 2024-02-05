<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Territoire;

class DashboardFilterDTO implements FilterTypologyDTOInterface, FilterDateDTOInterface
{
    private function __construct(
        private readonly Territoire $territoire,
        /** @var \App\Entity\Territoire[] */
        private readonly array $territoires = [],
        /** @var array<string> */
        private readonly array $typologies = [],
        private readonly ?\DateTimeImmutable $from = null,
        private readonly ?\DateTimeImmutable $to = null,
    ) {
    }

    public static function from(array $datas = []): self
    {
        if (!empty($datas['from'])) {
            $datas['from'] = \DateTimeImmutable::createFromFormat('Y-m-d', $datas['from']);
        }
        if (!empty($datas['to'])) {
            $datas['to'] = \DateTimeImmutable::createFromFormat('Y-m-d', $datas['to']);
        }

        return new DashboardFilterDTO(
            $datas['territoire'],
            $datas['territoires'] ?? [],
        $datas['typologies'] ?? [],
            $datas['from'] ?? null,
            $datas['to'] ?? null,
        );
    }

    public function getTerritoire(): Territoire
    {
        return $this->territoire;
    }

    /** @return \App\Entity\Territoire[] */
    public function getTerritoires(): array
    {
        return $this->territoires;
    }

    /** @return array<string>  */
    public function getTypologies(): array
    {
        return $this->typologies;
    }

    public function getFrom(): ?\DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): ?\DateTimeImmutable
    {
        return $this->to;
    }

    public function hasDateRange(): bool
    {
        if (null !== $this->getFrom()) {
            return true;
        }

        return null !== $this->getTo();
    }
}
