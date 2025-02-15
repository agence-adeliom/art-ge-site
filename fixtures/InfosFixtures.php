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
        $typologies = [
            'hotel' => 'Un hôtel',
            'location' => 'Une location de vacances (gîte et meublé)',
            'chambre' => 'Une chambre d\'hôtes',
            'camping' => 'Un camping',
            'insolite' => 'Hébergement insolite (bulles, cabanes, tiny house...)',
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
