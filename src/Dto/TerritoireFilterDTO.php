<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Territoire;

class TerritoireFilterDTO implements FilterTypologyDTOInterface, FilterDateDTOInterface
{
    private function __construct(
        private readonly Territoire $territoire,
        /** @var array<string> */
        private readonly array $typologies = [],
        private readonly ?\DateTimeImmutable $from = null,
        private readonly ?\DateTimeImmutable $to = null,
    ) {
    }

    public static function from(array $datas = []): self
    {
        if (!($datas['territoire'] instanceof Territoire)) {
            throw new \Error('Territoire do not exist');
        }

        $datas['from'] = \DateTimeImmutable::createFromFormat('!Y-m-d', (string) ($datas['from'] ?? '')) ?: null;
        /* @phpstan-ignore-next-line */
        $datas['to'] = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', ($datas['from'] ?? date('Y-m-d')) . ' 23:59:59') ?: null;

        return new TerritoireFilterDTO($datas['territoire'], $datas['typologies'] ?? [], $datas['from'], $datas['to']);
    }

    public function getTerritoire(): Territoire
    {
        return $this->territoire;
    }

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
