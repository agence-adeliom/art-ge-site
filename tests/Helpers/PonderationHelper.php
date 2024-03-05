<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

class PonderationHelper
{
    public static function getExpectedPonderationsByThematiquesAndTypologiesAndRestauration(): array
    {
        return [
            'biodiversite-et-conservation-de-la-nature-sur-site' => [
                'hotel' => ['no' => 13, 'yes' => 15],
                'camping' => ['no' => 13, 'yes' => 15],
                'visite' => ['no' => 13, 'yes' => 15],
                'activite' => ['no' => 13, 'yes' => 15],
                'restaurant' => ['yes' => 14]
            ],
            'gestion-des-dechets' => [
                'hotel' => ['no' => 15, 'yes' => 22],
                'camping' => ['no' => 13, 'yes' => 21],
                'visite' => ['no' => 14, 'yes' => 22],
                'activite' => ['no' => 14, 'yes' => 22],
                'restaurant' => ['yes' => 16]
            ],
            'gestion-de-l-eau-et-de-l-erosion' => [
                'hotel' => ['no' => 12, 'yes' => 12],
                'camping' => ['no' => 14, 'yes' => 14],
                'visite' => ['no' => 14, 'yes' => 14],
                'activite' => ['no' => 14, 'yes' => 14],
                'restaurant' => ['yes' => 12]
            ],
            'eco-construction' => [
                'hotel' => ['no' => 13, 'yes' => 13],
                'camping' => ['no' => 13, 'yes' => 13],
                'visite' => ['no' => 13, 'yes' => 13],
                'activite' => ['no' => 13, 'yes' => 13],
                'restaurant' => ['yes' => 13]
            ],
            'gestion-de-l-energie' => [
                'hotel' => ['no' => 20, 'yes' => 22],
                'camping' => ['no' => 18, 'yes' => 20],
                'visite' => ['no' => 20, 'yes' => 22],
                'activite' => ['no' => 20, 'yes' => 21],
                'restaurant' => ['yes' => 19]
            ],
            'entretien-et-proprete' => [
                'hotel' => ['no' => 11, 'yes' => 11],
                'camping' => ['no' => 11, 'yes' => 11],
                'visite' => ['no' => 11, 'yes' => 11],
                'activite' => ['no' => 11, 'yes' => 11],
                'restaurant' => ['yes' => 11]
            ],
            'transport-et-mobilite' => [
                'hotel' => ['no' => 10, 'yes' => 10],
                'camping' => ['no' => 10, 'yes' => 10],
                'visite' => ['no' => 10, 'yes' => 10],
                'activite' => ['no' => 10, 'yes' => 10],
                'restaurant' => ['yes' => 10]
            ],
            'acces-aux-personnes-en-situation-de-handicap' => [
                'hotel' => ['no' => 9, 'yes' => 9],
                'camping' => ['no' => 9, 'yes' => 9],
                'visite' => ['no' => 9, 'yes' => 9],
                'activite' => ['no' => 9, 'yes' => 9],
                'restaurant' => ['yes' => 9]
            ],
            'inclusivite-sociale' => [
                'hotel' => ['no' => 3, 'yes' => 3],
                'camping' => ['no' => 3, 'yes' => 3],
                'visite' => ['no' => 3, 'yes' => 3],
                'activite' => ['no' => 3, 'yes' => 3],
                'restaurant' => ['yes' => 3]
            ],
            'sensibilisation-des-acteurs' => [
                'hotel' => ['no' => 9, 'yes' => 12],
                'camping' => ['no' => 9, 'yes' => 12],
                'visite' => ['no' => 9, 'yes' => 12],
                'activite' => ['no' => 9, 'yes' => 12],
                'restaurant' => ['yes' => 13]
            ],
            'bien-etre-de-l-equipe' => [
                'hotel' => ['no' => 3, 'yes' => 3],
                'camping' => ['no' => 3, 'yes' => 3],
                'visite' => ['no' => 3, 'yes' => 3],
                'activite' => ['no' => 3, 'yes' => 3],
                'restaurant' => ['yes' => 3]
            ],
            'developpement-economique-local' => [
                'hotel' => ['no' => 6, 'yes' => 7],
                'camping' => ['no' => 5, 'yes' => 7],
                'visite' => ['no' => 5, 'yes' => 7],
                'activite' => ['no' => 5, 'yes' => 7],
                'restaurant' => ['yes' => 8]
            ],
            'cooperation-locale-et-liens-avec-les-habitants' => [
                'hotel' => ['no' => 4, 'yes' => 4],
                'camping' => ['no' => 4, 'yes' => 4],
                'visite' => ['no' => 4, 'yes' => 4],
                'activite' => ['no' => 4, 'yes' => 4],
                'restaurant' => ['yes' => 4]
            ],
            'culture-et-patrimoine' => [
                'hotel' => ['no' => 5, 'yes' => 5],
                'camping' => ['no' => 4, 'yes' => 5],
                'visite' => ['no' => 4, 'yes' => 5],
                'activite' => ['no' => 4, 'yes' => 5],
                'restaurant' => ['yes' => 6]
            ],
        ];
    }
}
