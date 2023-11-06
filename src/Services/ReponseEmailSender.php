<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Reponse;

readonly class ReponseEmailSender
{
    public function __construct(
    ) {}

    public function sendReponseSubmittedEmail(Reponse $reponse): void {}
}
