<?php

declare(strict_types=1);

namespace App\Message;

class ReponseConfirmationMessage
{
    public function __construct(
        private readonly int $reponseId
    ) {
    }

    public function getReponseId(): int
    {
        return $this->reponseId;
    }
}
