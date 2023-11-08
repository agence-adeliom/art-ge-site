<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Reponse;

class ReponseConfirmationMessage
{
    public function __construct(
        private readonly Reponse $reponse,
    ) {}

    public function getReponse(): Reponse
    {
        return $this->reponse;
    }
}
