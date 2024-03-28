<?php

declare(strict_types=1);

namespace App\EventListener\Form;

use App\Entity\Reponse;
use App\Repository\RepondantRepository;
use Symfony\Component\Form\Event\PostSubmitEvent;

class EmailToRepondantListener
{
    public function __construct(
        private readonly RepondantRepository $repondantRepository,
    ) {
    }

    public function __invoke(PostSubmitEvent $event): void
    {
        $reponse = $event->getData();
        if ($reponse instanceof Reponse) {
            $email = $event->getData()->getRepondant()->getEmail();
            $repondant = $this->repondantRepository->getOneByEmail($email);
            if ($repondant) {
                // TODO make dynamic
                $repondant->setFirstname((string) $reponse->getRepondant()->getFirstname());
                $repondant->setLastname((string) $reponse->getRepondant()->getLastname());
                $repondant->setPhone($reponse->getRepondant()->getPhone());
                $repondant->setCompany($reponse->getRepondant()->getCompany());
                $repondant->setAddress($reponse->getRepondant()->getAddress());
                $repondant->setCity($reponse->getRepondant()->getCity());
                $repondant->setZip((string) $reponse->getRepondant()->getZip());
                $repondant->setInsee((string) $reponse->getRepondant()->getInsee());
                $repondant->setCountry((string) $reponse->getRepondant()->getCountry());
                $repondant->setRestauration($reponse->getRepondant()->isRestauration());
                $repondant->setGreenSpace($reponse->getRepondant()->isGreenSpace());
                $repondant->setDepartment($reponse->getRepondant()->getDepartment());
                $repondant->setTypologie($reponse->getRepondant()->getTypologie());

                $reponse->setRepondant($repondant);
            }
        }
        $event->setData($reponse);
    }
}
