<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Choice;
use App\Entity\Question;
use App\Enum\ThematiqueSlugEnum;

class GreenSpaceChoiceIgnorer
{
    /** @var array<string, array<string>> */
    private array $slugsToKeep = [
        ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
            'je-n-utilise-jamais-d-insecticides',
            'je-n-utilise-jamais-de-produits-de-traitements-fongiques-chimiques',
            'je-limite-drastiquement-l-eclairage-nocturne-les-lumieres-exterieures-sont-eteintes-au-plus-tard-2h-apres-le-coucher-du-soleil-sans-passage',
            'j-ai-des-partenariats-avec-des-organismes-locaux-ou-nationaux-pour-la-valorisation-de-la-connaissance-sur-les-especes-locales-et-leur-observation',
            'je-n-ai-rien-entrepris-en-ce-sens',
        ],
        ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
            'j-ai-installe-un-systeme-de-recuperation-d-eau-de-pluie-qui-me-permet-de-couvrir-au-moins-10-de-ma-consommation-d-eau',
            'je-recycle-l-eau-de-certains-usages-rincage-eau-de-cuisson-carafes-d-eau-pour-l-arrosage-exterieur',
            'tous-les-points-d-eau-sont-dotes-de-reducteurs-de-debits-wc-double-debits-mousseurs-detecteurs-sous-robinets',
            'j-optimise-quotidiennement-grace-a-la-filtration-le-renouvellement-d-eau-de-ma-piscine-moins-de-3-du-volume-de-ma-piscine-ou-moins-de-50-litres-renouveles-baignade',
            'je-n-ai-rien-entrepris-en-ce-sens',
        ],
    ];

    /**
     * Exclude choices from a question for all the slugs that don't match
     * the predefined slugs.
     *
     * @param Question $question the question to exclude choices from
     *
     * @return Question the question with excluded choices
     */
    public function excludeChoices(Question $question): Question
    {
        if (!$this->shoudExclude($question)) {
            return $question;
        }

        $choices = $question->getChoices()->filter(function (Choice $choice) use ($question) {
            if (!in_array($choice->getSlug(), $this->slugsToKeep[$question->getThematique()->getSlug()])) {
                return true;
            }
        });

        foreach ($choices as $choice) {
            $question->removeChoice($choice);
        }

        return $question;
    }

    public function onlyChoices(Question $question): ?array
    {
        if (!$this->shoudExclude($question)) {
            return null;
        }

        $choices = $question->getChoices()->filter(function (Choice $choice) use ($question) {
            if (in_array($choice->getSlug(), $this->slugsToKeep[$question->getThematique()->getSlug()])) {
                return true;
            }
        });

        return array_values($choices->map(fn (Choice $choice): int => (int) $choice->getId())->toArray());
    }

    public function shoudExclude(Question $question): bool
    {
        return in_array($question->getThematique()->getSlug(), [
            ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value,
            ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value,
        ]);
    }
}
