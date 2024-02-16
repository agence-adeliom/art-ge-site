<?php

declare(strict_types=1);

namespace App\Services\ChoiceIgnorer;

use App\Enum\ThematiqueSlugEnum;

class RestaurationAndGreenSpaceChoiceIgnorer extends AbstractChoiceIgnorer
{
    /** @var array<string, array<string>> */
    protected array $slugsToIgnore = [
        ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
            'je-composte-paille-broie-pour-les-disposer-au-pied-des-plantations-si-espaces-verts',
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
        ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
            'j-ai-amenage-un-jardin-avec-differentes-herbes-aromatiques-sur-au-moins-1-du-terrain',
            'j-ai-au-moins-un-hotel-a-insectes-nichoir-a-oiseaux-ou-chauve-souris-abris-a-herissons-avec-occupation-constatee-pour-chaque-500-m2-de-terrain',
            'j-ai-7-especes-de-haies-differentes-gerees-de-maniere-responsable-taille-hors-periode-de-nidification-j-ai-plante-des-essences-locales-et-resilientes-au-changement-climatique-pas-d-especes-invasives-et-exotiques',
            'je-dispose-d-une-mare-ou-d-un-plan-d-eau-vegetalise-sur-au-moins-1-du-terrain',
            'je-procede-a-un-fauchage-tardif-des-espaces-verts-apres-l-ete',
            'je-conserve-des-arbres-morts-ou-du-bois-mort-au-sol',
            'j-ai-cree-des-passages-pour-la-petite-faune-au-pied-des-clotures',
            'j-ai-un-potager-en-permaculture-0-phyto-sur-au-moins-2-de-mon-terrain-et-je-valorise-sa-production-sur-ma-carte',
        ],
        ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
            'j-evite-l-artificialisation-des-parkings-je-privilegie-le-revetement-poreux-les-espaces-bitumes-sont-limites-aux-voies-de-circulation-de-lourds-vehicules-et-pour-les-places-pmr',
            'j-ai-installe-un-systeme-de-phyto-epuration-traitement-de-l-eau-d-assainissement-par-les-plantes',
            'j-ai-mis-en-place-un-systeme-de-toilettes-seches',
            'je-limite-les-pertes-d-eau-lors-de-l-arrosage-des-espaces-verts-horaire-goutte-a-goutte-paillage-oyas',
            'j-arrose-les-vegetaux-en-pleine-terre-uniquement-lors-de-leur-premiere-annee-et-je-n-arrose-pas-le-gazon',
        ],
    ];

    /** @return array<string, array<string>> */
    protected function getSlugsToIgnore(): array
    {
        return $this->slugsToIgnore;
    }
}
