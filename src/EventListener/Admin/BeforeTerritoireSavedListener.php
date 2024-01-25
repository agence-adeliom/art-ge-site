<?php

declare(strict_types=1);

namespace App\EventListener\Admin;

use App\Entity\Territoire;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEventListener(event: BeforeEntityUpdatedEvent::class)]
#[AsEventListener(event: BeforeEntityPersistedEvent::class)]
class BeforeTerritoireSavedListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function __invoke(BeforeEntityPersistedEvent | BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if (!$entity instanceof Territoire || null === $entity->getCode()) {
            return;
        }

        $password = $this->userPasswordHasher->hashPassword($entity, $entity->getCode());
        $entity->setPassword($password);
    }
}
