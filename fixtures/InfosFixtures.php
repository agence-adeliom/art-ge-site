<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\EasyAdmin\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InfosFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $questions = [
            'Dans quel département du Grand Est se situe votre votre établissement, votre activité ? : Cette question est requise*' => [
                'Ardennes (08)',
                'Aube (10)',
                'Marne (51)',
                'Haute-Marne (52)',
                'Meurthe-et-Moselle (54)',
                'Meuse (55)',
                'Moselle (57)',
                'Bas-Rhin (67)',
                'Haut-Rhin (68)',
                'Vosges (88)',
            ],
            'Vous êtes : Cette question est requise*' => [
                'Un hôtel, meublé, résidence, chambre d\'hôtes',
                'Un camping',
                'Un lieu de visite / une activité de loisirs',
                'Un restaurant',
            ],
            'Votre établissement propose-t-il une offre de restauration (panier, pique-nique, restaurant...) ? Cette question est requise*' => [
                'Oui',
                'Non',
            ],
            'Votre établissement dispose-t-il d\'un espace vert, d\'un espace extérieur de plus de 100m² ?Cette question est requise*' => [
                'Oui',
                'Non',
            ],
        ];
    }
}
