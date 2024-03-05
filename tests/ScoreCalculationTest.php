<?php

namespace App\Tests;

use App\Entity\Score;
use App\Enum\ThematiqueSlugEnum;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\DepartmentRepository;
use App\Repository\QuestionRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use App\Services\ChoiceIgnorer\GreenSpaceChoiceIgnorer;
use App\Services\ChoiceIgnorer\RestaurationAndGreenSpaceChoiceIgnorer;
use App\Services\ChoiceIgnorer\RestaurationChoiceIgnorer;
use App\Services\ReponseScoreGeneration;
use App\Tests\Helpers\EntityFactoryHelper;
use App\Tests\Helpers\RepondantTest;
use App\ValueObject\RepondantTypologie;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

/**
 * @internal
 *
 * @coversNothing
 */
class ScoreCalculationTest extends KernelTestCase
{
    /**
     * @dataProvider scoreProvider
     */
    public function testSomething(int $thematiqueId, int $typologieId, bool $greenSpace, bool $restauration, $expectedTotal): void
    {
        $restaurationChoiceIgnorer = static::getContainer()->get(RestaurationChoiceIgnorer::class);
        $greenSpaceChoiceIgnorer = static::getContainer()->get(GreenSpaceChoiceIgnorer::class);
        $restaurationAndGreenSpaceChoiceIgnorer = static::getContainer()->get(RestaurationAndGreenSpaceChoiceIgnorer::class);
        /** @var \App\Repository\QuestionRepository $questionRepository */
        $questionRepository = static::getContainer()->get(QuestionRepository::class);
        /** @var ChoiceTypologieRepository $choiceTypologieRepository */
        $choiceTypologieRepository = static::getContainer()->get(ChoiceTypologieRepository::class);

        $question = $questionRepository->findOneBy(['thematique' => $thematiqueId]);
        $questionChoices = [];
        if (false === $restauration && false === $greenSpace) {
            $questionChoices = $restaurationAndGreenSpaceChoiceIgnorer->onlyNotIgnored($question);
        } else {
            if (false === $greenSpace) {
                $questionChoices = $greenSpaceChoiceIgnorer->onlyNotIgnored($question);
            }
            if (false === $restauration) {
                $questionChoices = array_merge($questionChoices ?? [], $restaurationChoiceIgnorer->onlyNotIgnored($question) ?? []);
            }
        }

        $ponderation = $choiceTypologieRepository->getPonderationByQuestionAndTypologie($thematiqueId, RepondantTypologie::from($typologieId, $restauration, $greenSpace, 'ardennes'), $questionChoices ?? []);

        /**
         * VERIFICATION DE CHAQUE VALEUR
         **/
        assertEquals($expectedTotal, $ponderation);
    }

    public function scoreProvider(): iterable
    {

        $pointsExpected = [
            'hotel' => [
                ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                    [true, true, 15],
                    [true, false, 13],
                    [false, true, 6],
                    [false, false, 5],
                ],
                ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                    [true, true, 22],
                    [true, false, 16],
                    [false, true, 21],
                    [false, false, 15],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                    [true, true, 12],
                    [true, false, 12],
                    [false, true, 9],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                    [true, true, 13],
                    [true, false, 13],
                    [false, true, 13],
                    [false, false, 13],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                    [true, true, 22],
                    [true, false, 20],
                    [false, true, 22],
                    [false, false, 20],
                ],
                ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                    [true, true, 11],
                    [true, false, 11],
                    [false, true, 11],
                    [false, false, 11],
                ],
                ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                    [true, true, 10],
                    [true, false, 10],
                    [false, true, 10],
                    [false, false, 10],
                ],
                ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                    [true, true, 9],
                    [true, false, 9],
                    [false, true, 9],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                    [true, true, 3],
                    [true, false, 3],
                    [false, true, 3],
                    [false, false, 3],
                ],
                ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                    [true, true, 12],
                    [true, false, 9],
                    [false, true, 12],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                    [true, true, 3],
                    [true, false, 3],
                    [false, true, 3],
                    [false, false, 3],
                ],
                ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                    [true, true, 7],
                    [true, false, 6],
                    [false, true, 7],
                    [false, false, 6],
                ],
                ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                    [true, true, 4],
                    [true, false, 4],
                    [false, true, 4],
                    [false, false, 4],
                ],
                ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                    [true, true, 5],
                    [true, false, 5],
                    [false, true, 5],
                    [false, false, 5],
                ],
            ],
            'camping' => [
                ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                    [true, true, 15],
                    [true, false, 13],
                    [false, true, 6],
                    [false, false, 5],
                ],
                ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                    [true, true, 22],
                    [true, false, 15],
                    [false, true, 21],
                    [false, false, 14],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                    [true, true, 14],
                    [true, false, 14],
                    [false, true, 9],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                    [true, true, 13],
                    [true, false, 13],
                    [false, true, 13],
                    [false, false, 13],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                    [true, true, 20],
                    [true, false, 18],
                    [false, true, 20],
                    [false, false, 18],
                ],
                ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                    [true, true, 11],
                    [true, false, 11],
                    [false, true, 11],
                    [false, false, 11],
                ],
                ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                    [true, true, 10],
                    [true, false, 10],
                    [false, true, 10],
                    [false, false, 10],
                ],
                ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                    [true, true, 9],
                    [true, false, 9],
                    [false, true, 9],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                    [true, true, 3],
                    [true, false, 3],
                    [false, true, 3],
                    [false, false, 3],
                ],
                ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                    [true, true, 12],
                    [true, false, 9],
                    [false, true, 12],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                    [true, true, 3],
                    [true, false, 3],
                    [false, true, 3],
                    [false, false, 3],
                ],
                ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                    [true, true, 7],
                    [true, false, 6],
                    [false, true, 7],
                    [false, false, 6],
                ],
                ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                    [true, true, 4],
                    [true, false, 4],
                    [false, true, 4],
                    [false, false, 4],
                ],
                ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                    [true, true, 5],
                    [true, false, 5],
                    [false, true, 5],
                    [false, false, 5],
                ],
            ],
            'activite' => [
                ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                    [true, true, 15],
                    [true, false, 13],
                    [false, true, 6],
                    [false, false, 5],
                ],
                ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                    [true, true, 22],
                    [true, false, 15],
                    [false, true, 21],
                    [false, false, 14],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                    [true, true, 14],
                    [true, false, 14],
                    [false, true, 10],
                    [false, false, 10],
                ],
                ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                    [true, true, 13],
                    [true, false, 13],
                    [false, true, 13],
                    [false, false, 13],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                    [true, true, 22],
                    [true, false, 20],
                    [false, true, 22],
                    [false, false, 20],
                ],
                ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                    [true, true, 11],
                    [true, false, 11],
                    [false, true, 11],
                    [false, false, 11],
                ],
                ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                    [true, true, 10],
                    [true, false, 10],
                    [false, true, 10],
                    [false, false, 10],
                ],
                ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                    [true, true, 9],
                    [true, false, 9],
                    [false, true, 9],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                    [true, true, 3],
                    [true, false, 3],
                    [false, true, 3],
                    [false, false, 3],
                ],
                ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                    [true, true, 12],
                    [true, false, 9],
                    [false, true, 12],
                    [false, false, 9],
                ],
                ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                    [true, true, 3],
                    [true, false, 3],
                    [false, true, 3],
                    [false, false, 3],
                ],
                ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                    [true, true, 7],
                    [true, false, 6],
                    [false, true, 7],
                    [false, false, 6],
                ],
                ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                    [true, true, 4],
                    [true, false, 4],
                    [false, true, 4],
                    [false, false, 4],
                ],
                ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                    [true, true, 5],
                    [true, false, 5],
                    [false, true, 5],
                    [false, false, 5],
                ],
            ],
            'restaurant' => [
                ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                    [true, true, 14],
                    [false, true, 6],
                ],
                ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                    [true, true, 19],
                    [false, true, 18],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                    [true, true, 11],
                    [false, true, 8],
                ],
                ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                    [true, true, 13],
                    [false, true, 13],
                ],
                ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                    [true, true, 20],
                    [false, true, 20],
                ],
                ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                    [true, true, 8],
                    [false, true, 8],
                ],
                ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                    [true, true, 10],
                    [false, true, 10],
                ],
                ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                    [true, true, 9],
                    [false, true, 9],
                ],
                ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                    [true, true, 3],
                    [false, true, 3],
                ],
                ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                    [true, true, 13],
                    [false, true, 13],
                ],
                ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                    [true, true, 3],
                    [false, true, 3],
                ],
                ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                    [true, true, 8],
                    [false, true, 8],
                ],
                ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                    [true, true, 4],
                    [false, true, 4],
                ],
                ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                    [true, true, 6],
                    [false, true, 6],
                ],
            ],
        ];

        $typologies = [
            'hotel' => 1,
            'camping' => 4,
            'activite' => 6,
            'restaurant' => 8,
        ];

        foreach ($pointsExpected as $typologie => $thematiques) {
            $thematiqueId = 0;
            foreach ($thematiques as $thematiqueSlug => $thematique) {
                $thematiqueId++;
                foreach ($thematique as $pointExpected) {
                    yield $typologie . ' ' . $thematiqueSlug . ($pointExpected[0] ? ' avec espace vert' : ' sans espace vert') . ($pointExpected[1] ? ' avec restauration' : ' sans restauration') => [$thematiqueId, $typologies[$typologie], ...$pointExpected];
                }
            }
        }
    }
}
