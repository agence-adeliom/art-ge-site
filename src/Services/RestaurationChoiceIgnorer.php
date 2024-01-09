<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\ThematiqueSlugEnum;

class RestaurationChoiceIgnorer extends AbstractChoiceIgnorer
{
    /** @var array<string, array<string>> */
    protected array $slugsToIgnore = [
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

    /** @return array<string, array<string>> */
    protected function getSlugsToIgnore(): array
    {
        return $this->slugsToIgnore;
    }
}
