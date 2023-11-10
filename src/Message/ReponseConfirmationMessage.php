<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Reponse;

class ReponseConfirmationMessage
{
    private readonly int $reponseId;

    public function __construct(
        readonly Reponse $reponse
    ) {
        $this->reponseId = (int) $reponse->getId();
    }

    public function getReponseId(): int
    {
        return $this->reponseId;
    }
}
