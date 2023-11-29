<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Department;
use App\Entity\Typologie;
use App\Enum\DepartementEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class InfosFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        $typologies = [
            'hotel' => 'Un hôtel, meublé, résidence, chambre d\'hôtes',
            'camping' => 'Un camping',
            'visite' => 'Un lieu de visite',
            'activite' => 'Une activité de loisirs',
            'restaurant' => 'Un restaurant',
        ];
        foreach ($typologies as $k => $t) {
            $typology = new Typologie();
            $typology->setName($t);
            $typology->setSlug($k);
            $manager->persist($typology);
        }

        foreach (DepartementEnum::cases() as $departementEnum) {
            $department = new Department();
            $department->setName(DepartementEnum::getLabel($departementEnum));
            $department->setSlug($departementEnum->value);
            $department->setCode(DepartementEnum::getCode($departementEnum));
            $manager->persist($department);
        }

        $manager->flush();
    }
}
