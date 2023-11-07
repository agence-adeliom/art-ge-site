<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Department;
use App\Entity\Typologie;
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

        $departments = [
            '08' => 'Ardennes',
            '10' => 'Aube',
            '51' => 'Marne',
            '52' => 'Haute-Marne',
            '54' => 'Meurthe-et-Moselle',
            '55' => 'Meuse',
            '57' => 'Moselle',
            '67' => 'Bas-Rhin',
            '68' => 'Haut-Rhin',
            '88' => 'Vosges',
        ];
        foreach ($departments as $code => $d) {
            $department = new Department();
            $department->setName($d);
            $department->setSlug($slugger->slug(strtolower($d))->toString());
            $department->setCode((string) $code);
            $manager->persist($department);
        }

        $manager->flush();
    }
}
