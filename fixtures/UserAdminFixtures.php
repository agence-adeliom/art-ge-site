<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\EasyAdmin\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAdminFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('contact@adeliom.com');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setFirstname('Admin');
        $user->setLastname('Adeliom');

        $hashedPassword = $this->passwordHasher->hashPassword($user, '123456789');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('contact@art-grandest.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstname('Contact');
        $user->setLastname('ART-GE');

        $hashedPassword = $this->passwordHasher->hashPassword($user, '123456789');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
