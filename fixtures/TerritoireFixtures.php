<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Territoire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

class TerritoireFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $territoire = new Territoire();
        $territoire->setUuid(new Ulid());
        $territoire->setName('Alsace');
        $territoire->setSlug('alsace');
        $territoire->setUseSlug(true);
        $territoire->setCode('alsace');
        $territoire->setPassword($this->userPasswordHasher->hashPassword($territoire, 'alsace'));
        $territoire->setZips([
            '67000',
            '67500',
        ]);
        $manager->persist($territoire);
        $manager->flush();
    }
}
