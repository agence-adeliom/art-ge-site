<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Reponse;

readonly class ReponseConfirmationMessage
{
    public function __construct(
        private Reponse $reponse,
    ) {}

    public function getReponse(): Reponse
    {
        return $this->reponse;
    }
}
