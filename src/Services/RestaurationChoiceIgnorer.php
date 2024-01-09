<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Choice;
use App\Entity\Question;
use App\Enum\ThematiqueSlugEnum;

class RestaurationChoiceIgnorer
{
    /** @var array<string, array<string>> */
    private array $slugsToIgnore = [
        ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
            'j-ai-recours-a-un-systeme-de-consigne-pour-au-moins-la-moitie-de-mes-boissons',
            'si-restauration-a-emporter-je-propose-un-systeme-de-boites-consignees-et-la-possibilite-d-utiliser-les-boites-des-clients',
            'j-ai-forme-mes-equipes-ou-me-suis-forme-e-si-je-travaille-seul-e-a-la-reduction-du-gaspillage-alimentaire',
        ],
        ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
            'j-affiche-un-plan-d-allumage-pour-les-salaries-pour-les-equipements-de-cuisson',
            'j-affiche-un-plan-d-entretien-pour-les-equipements-de-froid-refrigerateurs-nettoyes-regulierement-suivi-des-temperatures',
        ],
        ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
            'je-propose-des-plats-vegetariens-et-ou-vegans-que-je-mets-en-avant',
        ],
    ];

    /**
     * Ignore choices from a question for all the slugs that match
     * the predefined slugs.
     *
     * @param Question $question the question to ignore choices from
     *
     * @return Question the question with ignored choices
     */
    public function ignoreChoices(Question $question): Question
    {
        if (!$this->shoudIgnore($question)) {
            return $question;
        }

        $choices = $question->getChoices()->filter(function (Choice $choice) use ($question) {
            if (in_array($choice->getSlug(), $this->slugsToIgnore[$question->getThematique()->getSlug()])) {
                return true;
            }
        });

        foreach ($choices as $choice) {
            $question->removeChoice($choice);
        }

        return $question;
    }

    public function onlyNotIgnored(Question $question): ?array
    {
        if (!$this->shoudIgnore($question)) {
            return null;
        }

        $choices = $question->getChoices()->filter(function (Choice $choice) use ($question) {
            if (!in_array($choice->getSlug(), $this->slugsToIgnore[$question->getThematique()->getSlug()])) {
                return true;
            }
        });

        return array_values($choices->map(fn (Choice $choice): int => (int) $choice->getId())->toArray());
    }

    public function shoudIgnore(Question $question): bool
    {
        return in_array($question->getThematique()->getSlug(), array_keys($this->slugsToIgnore));
    }
}
