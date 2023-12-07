<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Territoire;

class TerritoireFilterDTO
{
    private function __construct(
        private Territoire $territoire,
        /** @var array<string> */
        private array $thematiques = [],
        /** @var array<string> */
        private array $typologies = [],
        private ?bool $restauration = null,
        private ?bool $greenSpace = null,
        private ?\DateTimeImmutable $from = null,
        private ?\DateTimeImmutable $to = null,
    ) {}

    public static function from(array $datas = []): self
    {
        if (!($datas['territoire'] instanceof Territoire)) {
            throw new \Error('Territoire do not exist');
        }

        $datas['from'] = \DateTimeImmutable::createFromFormat('!Y-m-d', (string) $datas['from']) ?: null;
        $datas['to'] = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $datas['to'] . ' 23:59:59') ?: null;

        return new TerritoireFilterDTO($datas['territoire'], $datas['thematiques'] ?? [], $datas['typologies'] ?? [], $datas['restauration'], $datas['greenSpace'], $datas['from'], $datas['to']);
    }

    public function getTerritoire(): Territoire
    {
        return $this->territoire;
    }

    public function getThematiques(): ?array
    {
        return $this->thematiques;
    }

    public function getTypologies(): ?array
    {
        return $this->typologies;
    }

    public function getRestauration(): ?bool
    {
        return $this->restauration;
    }

    public function getGreenSpace(): ?bool
    {
        return $this->greenSpace;
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
        return null !== $this->getFrom() || null !== $this->getFrom();
    }
}
