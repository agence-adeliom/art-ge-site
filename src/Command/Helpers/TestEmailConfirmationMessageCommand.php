<?php

declare(strict_types=1);

namespace App\Command\Helpers;

use App\Message\ReponseConfirmationMessage;
use App\Repository\ReponseRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'test:email:send-confirmation',
    description: 'Send a confirmation email for reponse 2',
)]
class TestEmailConfirmationMessageCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly ReponseRepository $reponseRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @phpstan-ignore-next-line */
        $this->messageBus->dispatch(new ReponseConfirmationMessage($this->reponseRepository->find(9)->getId()));

        return Command::SUCCESS;
    }
}
