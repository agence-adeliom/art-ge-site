<?php

declare(strict_types=1);

namespace DataFixtures;

use App\DataTransformer\Form\ProcessedFormReponseDataTransformer;
use App\Entity\Reponse;
use App\Enum\ThematiqueSlugEnum;
use App\Repository\ChoiceRepository;
use App\Repository\ChoiceTypologieRepository;
use App\Repository\DepartmentRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TypologieRepository;
use App\Services\ReponseScoreGeneration;
use DataFixtures\Provider\RepondantProvider;
use App\Entity\Repondant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Ulid;

class RepondantsFixtures extends Fixture implements DependentFixtureInterface
{
    private readonly Generator $faker;

    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly ThematiqueRepository $thematiqueRepository,
        private readonly TypologieRepository $typologieRepository,
        private readonly ChoiceTypologieRepository $choiceTypologieRepository,
        private readonly ChoiceRepository $choiceRepository,
        private readonly ReponseScoreGeneration $reponseScoreGeneration,
    ) {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed('artge');
        $this->faker->addProvider(new RepondantProvider($this->faker));
    }

    public function load(ObjectManager $manager): void
    {
        $repondantDatas = [
            ['NATALIE', 'RECEPTION', '0389426476', 'reception@camping-mulhouse.com', "CAMPING DE L'ILL OTC", '1 rue Pierre de Coubertin', '68100', 'MULHOUSE', 'camping', false, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        'je-n-utilise-jamais-de-produits-de-traitements-fongiques-chimiques',
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        'j-ai-supprime-les-plastiques-a-usage-unique-et-tous-mes-emballages-plastiques-sont-en-grands-formats-superieur-a-5l',
                        'les-visiteurs-les-clients-peuvent-trier-leurs-dechets-et-j-ai-des-filieres-de-valorisation-en-place-pour-les-emballages',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        'j-evite-l-artificialisation-des-parkings-je-privilegie-le-revetement-poreux-les-espaces-bitumes-sont-limites-aux-voies-de-circulation-de-lourds-vehicules-et-pour-les-places-pmr',
                        'je-recycle-l-eau-de-certains-usages-rincage-eau-de-cuisson-carafes-d-eau-pour-l-arrosage-exterieur',
                        'j-arrose-les-vegetaux-en-pleine-terre-uniquement-lors-de-leur-premiere-annee-et-je-n-arrose-pas-le-gazon',
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        'je-n-ai-pas-de-climatisation',
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        'j-ai-des-consignes-claires-sur-l-utilisation-des-produits-d-entretien-ex-quantite-de-produit-par-rapport-a-la-durete-de-l-eau',
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        'je-communique-des-informations-claires-sur-les-transports-collectifs-le-covoiturage-les-transports-publics-pour-encourager-a-venir-sans-voiture',
                        'je-communique-des-instructions-claires-pour-realiser-des-activites-autour-de-mon-site-sans-voiture',
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        'je-propose-des-offres-accessibles-a-tout-public-avec-une-tarification-ou-des-prestations-adaptees',
                        'j-accepte-les-cheques-vacances-ancv',
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        'je-mets-en-avant-les-produits-locaux-de-saison-issus-de-l-agriculture-bio-ainsi-que-la-gastronomie-regionale',
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        'au-moins-80-de-mes-fournisseurs-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km',
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        'je-suis-partenaire-au-moins-une-fois-par-an-a-un-evenement-en-lien-avec-les-habitants-ou-associations-de-la-commune',
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'accueil-velo',
                    ],
                ]
            ],
            ['Julien', 'LUTZ', '03750616237', 'H0627-GM@accor.com', "CLR hotels Hotels Ibis", '34 ALLEE NATHAN KATZ, RUE DES CEVENNES', '68100', 'SAUSHEIM MULHOUSE', 'hotel', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "je-n-utilise-jamais-d-insecticides",
                        "je-procede-a-un-fauchage-tardif-des-espaces-verts-apres-l-ete",
                        "je-conserve-des-arbres-morts-ou-du-bois-mort-au-sol",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        "j-ai-supprime-les-plastiques-a-usage-unique-et-tous-mes-emballages-plastiques-sont-en-grands-formats-superieur-a-5l",
                        "j-ai-recours-a-un-systeme-de-consigne-pour-au-moins-la-moitie-de-mes-boissons",
                        "j-ai-forme-mes-equipes-ou-me-suis-forme-e-si-je-travaille-seul-e-a-la-reduction-des-dechets",
                        "j-ai-forme-mes-equipes-ou-me-suis-forme-e-si-je-travaille-seul-e-a-la-reduction-du-gaspillage-alimentaire",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        "j-evite-l-artificialisation-des-parkings-je-privilegie-le-revetement-poreux-les-espaces-bitumes-sont-limites-aux-voies-de-circulation-de-lourds-vehicules-et-pour-les-places-pmr",
                        "tous-les-points-d-eau-sont-dotes-de-reducteurs-de-debits-wc-double-debits-mousseurs-detecteurs-sous-robinets",
                        "je-limite-les-pertes-d-eau-lors-de-l-arrosage-des-espaces-verts-horaire-goutte-a-goutte-paillage-oyas",
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        "j-affiche-des-consignes-sur-les-reductions-d-energie-pour-moi-meme-ainsi-que-les-salaries",
                        "j-affiche-un-plan-d-entretien-pour-les-equipements-de-froid-refrigerateurs-nettoyes-regulierement-suivi-des-temperatures",
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        "mes-produits-d-entretien-sont-sans-produits-chimiques-eau-ozonee-nettoyage-vapeur-seche-et-ou-plus-de-80-de-mes-produits-sont-ecolabellises-ecocert-ecolabel-europeen-ou-equivalent-ou-faits-maison",
                        "j-ai-des-consignes-claires-sur-l-utilisation-des-produits-d-entretien-ex-quantite-de-produit-par-rapport-a-la-durete-de-l-eau",
                        "j-utilise-des-pompes-de-dosage-pour-diluer-les-produits-concentres-ou-des-doseurs",
                        "j-ai-des-criteres-environnementaux-stricts-dans-mon-contrat-de-prestation-de-nettoyage-des-locaux-produits-labellises-ou-techniques-de-nettoyages-alternatives",
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        "j-ai-au-moins-20-des-places-de-stationnement-dediees-a-un-parking-velo",
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        "j-ai-forme-l-ensemble-de-mon-equipe-ainsi-que-moi-meme-a-l-accueil-des-personnes-en-situation-de-handicap",
                        "j-accueille-les-chiens-guides",
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "j-accepte-les-cheques-vacances-ancv",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        "je-mets-en-avant-les-produits-locaux-de-saison-issus-de-l-agriculture-bio-ainsi-que-la-gastronomie-regionale",
                        "je-propose-des-plats-vegetariens-et-ou-vegans-que-je-mets-en-avant",
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        "j-ai-mis-en-place-un-management-facilitant-la-remontee-d-information-au-dela-du-cadre-legal-entretiens-reguliers-entretiens-croises-boite-a-idee",
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        "au-moins-80-de-mes-fournisseurs-non-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km",
                        "au-moins-80-de-mes-fournisseurs-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km",
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        "je-participe-au-moins-deux-fois-par-an-a-des-reunions-de-travail-avec-mon-ot-le-cdt-le-crt-ou-les-collectivites-locales",
                        "je-suis-partenaire-au-moins-une-fois-par-an-a-un-evenement-en-lien-avec-les-habitants-ou-associations-de-la-commune",
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "je-propose-un-plat-traditionnel-local-dans-mon-menu-et-ou-je-le-mets-en-avant-dans-mon-offre-boutique",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                ]
            ],
            ['Farid', 'Sellemet', '0389562362', 'reception@aubergejeunesse-mulhouse.com', "Auberge de Jeunesse", "37 rue de l'Illberg", '68200', 'MULHOUSE', 'hotel', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "je-n-utilise-jamais-d-insecticides",
                        "je-procede-a-un-fauchage-tardif-des-espaces-verts-apres-l-ete",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        'j-ai-forme-mes-equipes-ou-me-suis-forme-e-si-je-travaille-seul-e-a-la-reduction-des-dechets',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        "tous-les-points-d-eau-sont-dotes-de-reducteurs-de-debits-wc-double-debits-mousseurs-detecteurs-sous-robinets",
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        "j-ai-un-abonnement-d-electricite-avec-des-garanties-d-origine-renouvelable-de-l-energie-dans-le-batiment",
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        "mes-produits-d-entretien-sont-sans-produits-chimiques-eau-ozonee-nettoyage-vapeur-seche-et-ou-plus-de-80-de-mes-produits-sont-ecolabellises-ecocert-ecolabel-europeen-ou-equivalent-ou-faits-maison",
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        "j-ai-une-offre-dediee-aux-voyageurs-a-pied-en-train-ou-a-velo",
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        "j-ai-forme-l-ensemble-de-mon-equipe-ainsi-que-moi-meme-a-l-accueil-des-personnes-en-situation-de-handicap",
                        "je-dispose-de-la-marque-d-Etat-tourisme-et-handicap-moteur",
                        "je-dispose-de-la-marque-d-Etat-tourisme-et-handicap-mental",
                        "je-dispose-de-la-marque-d-Etat-tourisme-et-handicap-visuel",
                        "je-dispose-de-la-marque-d-Etat-tourisme-et-handicap-auditif",
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "je-propose-des-offres-accessibles-a-tout-public-avec-une-tarification-ou-des-prestations-adaptees",
                        "j-accepte-les-cheques-vacances-ancv",
                        "l-entreprise-dispose-d-un-partenariat-avec-une-association-pour-accueillir-des-publics-jeunes-seniors-defavorises",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        "j-ai-mis-en-place-un-management-facilitant-la-remontee-d-information-au-dela-du-cadre-legal-entretiens-reguliers-entretiens-croises-boite-a-idee",
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        "au-moins-80-de-mes-fournisseurs-non-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km",
                        "au-moins-80-de-mes-fournisseurs-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km",
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        "je-participe-au-moins-deux-fois-par-an-a-des-reunions-de-travail-avec-mon-ot-le-cdt-le-crt-ou-les-collectivites-locales",
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "je-propose-un-plat-traditionnel-local-dans-mon-menu-et-ou-je-le-mets-en-avant-dans-mon-offre-boutique",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'accueil-velo',
                    ],
                ]
            ],
            ['SYLVAIN', 'VERNEREY', '0688745404', 'vernerey@citedutrain.com', "CITE DU TRAIN/PATRIMOINE SNCF", '2 RUE ALFRED DE GLEHN', '68200', 'MULHOUSE', 'visite', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "je-n-utilise-jamais-d-insecticides",
                        "je-n-utilise-jamais-de-produits-de-traitements-fongiques-chimiques",
                        "je-limite-drastiquement-l-eclairage-nocturne-les-lumieres-exterieures-sont-eteintes-au-plus-tard-2h-apres-le-coucher-du-soleil-sans-passage",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        "je-composte-paille-broie-pour-les-disposer-au-pied-des-plantations-si-espaces-verts",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        "j-ai-un-compteur-connecte-ou-un-logiciel-de-gestion-pour-piloter-suivre-les-consommations-d-energie-chauffage-climatisation-equipements",
                        "j-ai-un-plan-de-gestion-des-temperatures-du-batiment-en-fonction-des-horaires-des-jours-des-pieces-et-des-usages",
                        "j-ai-un-abonnement-d-electricite-avec-des-garanties-d-origine-renouvelable-de-l-energie-dans-le-batiment",
                        "j-affiche-des-consignes-sur-les-reductions-d-energie-pour-moi-meme-ainsi-que-les-salaries",
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        "j-ai-forme-l-ensemble-de-mon-equipe-ainsi-que-moi-meme-a-l-accueil-des-personnes-en-situation-de-handicap",
                        "j-accueille-les-chiens-guides",
                        "j-ai-a-disposition-des-fauteuils-ou-des-cannes-sieges",
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "je-propose-des-offres-accessibles-a-tout-public-avec-une-tarification-ou-des-prestations-adaptees",
                        "j-accepte-les-cheques-vacances-ancv",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        "j-ai-mis-en-place-un-management-facilitant-la-remontee-d-information-au-dela-du-cadre-legal-entretiens-reguliers-entretiens-croises-boite-a-idee",
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        "j-ai-developpe-des-prestations-en-synergie-avec-d-autres-entreprises-de-mon-territoire-ma-commune-ou-communes-voisines",
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        "je-participe-au-moins-deux-fois-par-an-a-des-reunions-de-travail-avec-mon-ot-le-cdt-le-crt-ou-les-collectivites-locales",
                        "je-suis-partenaire-au-moins-une-fois-par-an-a-un-evenement-en-lien-avec-les-habitants-ou-associations-de-la-commune",
                        "l-un-de-nos-salaries-dirigeant-est-elu-ou-benevole-au-sein-d-une-instance-publique-ou-professionnelle-et-du-temps-lui-est-mis-a-disposition-pour-s-impliquer",
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "nous-participons-a-au-moins-un-evenement-culturel-chaque-annee",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        "accueil-velo",
                        "etoile-verte-michelin",
                    ],
                ]
            ],
            ['Marie', 'Basenach', '0634315287', 'marie.basenach@mulhouse-alsace.fr', "Parc zoologique et botanique de Mulhouse", '111 avenue de la 1ère Division Blindée', '68100', 'MULHOUSE', 'visite', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "j-ai-amenage-un-jardin-avec-differentes-herbes-aromatiques-sur-au-moins-1-du-terrain",
                        "j-ai-au-moins-un-hotel-a-insectes-nichoir-a-oiseaux-ou-chauve-souris-abris-a-herissons-avec-occupation-constatee-pour-chaque-500-m2-de-terrain",
                        "je-n-utilise-jamais-d-insecticides",
                        "je-n-utilise-jamais-de-produits-de-traitements-fongiques-chimiques",
                        "je-limite-drastiquement-l-eclairage-nocturne-les-lumieres-exterieures-sont-eteintes-au-plus-tard-2h-apres-le-coucher-du-soleil-sans-passage",
                        "j-ai-7-especes-de-haies-differentes-gerees-de-maniere-responsable-taille-hors-periode-de-nidification-j-ai-plante-des-essences-locales-et-resilientes-au-changement-climatique-pas-d-especes-invasives-et-exotiques",
                        "je-dispose-d-une-mare-ou-d-un-plan-d-eau-vegetalise-sur-au-moins-1-du-terrain",
                        "je-conserve-des-arbres-morts-ou-du-bois-mort-au-sol",
                        "j-ai-des-partenariats-avec-des-organismes-locaux-ou-nationaux-pour-la-valorisation-de-la-connaissance-sur-les-especes-locales-et-leur-observation",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        "je-composte-paille-broie-pour-les-disposer-au-pied-des-plantations-si-espaces-verts",
                        "si-restauration-a-emporter-je-propose-un-systeme-de-boites-consignees-et-la-possibilite-d-utiliser-les-boites-des-clients",
                        "les-visiteurs-les-clients-peuvent-trier-leurs-dechets-et-j-ai-des-filieres-de-valorisation-en-place-pour-les-emballages",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        "j-ai-applique-le-bioclimatisme-valoriser-l-orientation-du-batiment-avec-de-grandes-ouvertures-casquettes-solaires-vegetalisation-coupe-vent",
                        "meubles-et-decoration-sont-majoritairement-en-bois-non-agglomere-non-exotique-a-base-de-vegetal-en-metal-ou-a-partir-d-objets-recycles-ou-de-seconde-vie",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        "j-ai-un-plan-de-gestion-des-temperatures-du-batiment-en-fonction-des-horaires-des-jours-des-pieces-et-des-usages",
                        "j-affiche-des-consignes-sur-les-reductions-d-energie-pour-moi-meme-ainsi-que-les-salaries",
                        "j-affiche-un-plan-d-entretien-pour-les-equipements-de-froid-refrigerateurs-nettoyes-regulierement-suivi-des-temperatures",
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        "j-ai-des-consignes-claires-sur-l-utilisation-des-produits-d-entretien-ex-quantite-de-produit-par-rapport-a-la-durete-de-l-eau",
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        "je-communique-des-informations-claires-sur-les-transports-collectifs-le-covoiturage-les-transports-publics-pour-encourager-a-venir-sans-voiture",
                        "j-ai-une-offre-dediee-aux-voyageurs-a-pied-en-train-ou-a-velo",
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        "j-accueille-les-chiens-guides",
                        "j-ai-a-disposition-des-fauteuils-ou-des-cannes-sieges",
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "je-propose-des-offres-accessibles-a-tout-public-avec-une-tarification-ou-des-prestations-adaptees",
                        "j-accepte-les-cheques-vacances-ancv",
                        "l-entreprise-dispose-d-un-partenariat-avec-une-association-pour-accueillir-des-publics-jeunes-seniors-defavorises",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        "je-mets-en-avant-les-produits-locaux-de-saison-issus-de-l-agriculture-bio-ainsi-que-la-gastronomie-regionale",
                        "je-propose-des-plats-vegetariens-et-ou-vegans-que-je-mets-en-avant",
                        "j-organise-des-animations-sur-l-observation-de-la-faune-et-de-la-flore-je-sensibilise-a-la-biodiversite-et-l-environnement",
                        "je-mets-en-place-des-mesures-incitatives-pour-l-adoption-de-comportements-vertueux-a-l-attention-de-mes-clients-affichettes-pictos-infographie",
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        "des-actions-sont-mises-en-oeuvre-pour-prevenir-et-limiter-les-tms-troubles-musculo-squelettiques-et-psychologiques",
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        "j-ai-un-partenariat-avec-une-entreprise-de-reinsertion-locale",
                        "j-ai-developpe-des-prestations-en-synergie-avec-d-autres-entreprises-de-mon-territoire-ma-commune-ou-communes-voisines",
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        "je-participe-au-moins-deux-fois-par-an-a-des-reunions-de-travail-avec-mon-ot-le-cdt-le-crt-ou-les-collectivites-locales",
                        "je-suis-partenaire-au-moins-une-fois-par-an-a-un-evenement-en-lien-avec-les-habitants-ou-associations-de-la-commune",
                        "l-un-de-nos-salaries-dirigeant-est-elu-ou-benevole-au-sein-d-une-instance-publique-ou-professionnelle-et-du-temps-lui-est-mis-a-disposition-pour-s-impliquer",
                        "la-structure-soutient-une-association-locale-par-du-don-financier-du-temps-offert-du-pret-de-materiel-ou-toute-forme-d-aide-significative",
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "nous-participons-a-au-moins-un-evenement-culturel-chaque-annee",
                        "je-propose-un-plat-traditionnel-local-dans-mon-menu-et-ou-je-le-mets-en-avant-dans-mon-offre-boutique",
                        "j-emploie-je-mets-en-avant-des-artistes-locaux",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                ]
            ],
            ['Guillaume', 'GASSER', '0664981214', 'g.gasser@museedelauto.org', "Musee national automobile", '192, avenue de colmar', '68100', 'MULHOUSE', 'visite', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "je-n-utilise-jamais-d-insecticides",
                        "je-n-utilise-jamais-de-produits-de-traitements-fongiques-chimiques",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        "j-arrose-les-vegetaux-en-pleine-terre-uniquement-lors-de-leur-premiere-annee-et-je-n-arrose-pas-le-gazon",
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        "j-ai-un-compteur-connecte-ou-un-logiciel-de-gestion-pour-piloter-suivre-les-consommations-d-energie-chauffage-climatisation-equipements",
                        "j-ai-un-plan-de-gestion-des-temperatures-du-batiment-en-fonction-des-horaires-des-jours-des-pieces-et-des-usages",
                        "je-produis-de-l-electricite-photovoltaique-ou-eolienne",
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        "j-ai-forme-l-ensemble-de-mon-equipe-ainsi-que-moi-meme-a-l-accueil-des-personnes-en-situation-de-handicap",
                        "je-dispose-de-la-marque-d-Etat-tourisme-et-handicap-moteur",
                        "j-accueille-les-chiens-guides",
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "j-accepte-les-cheques-vacances-ancv",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        "j-ai-mis-en-place-un-management-facilitant-la-remontee-d-information-au-dela-du-cadre-legal-entretiens-reguliers-entretiens-croises-boite-a-idee",
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "notre-structure-soutient-une-association-culturelle-locale-ou-non",
                        "nous-participons-a-au-moins-un-evenement-culturel-chaque-annee",
                        "je-propose-un-plat-traditionnel-local-dans-mon-menu-et-ou-je-le-mets-en-avant-dans-mon-offre-boutique",
                        "j-emploie-je-mets-en-avant-des-artistes-locaux",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                ]
            ],
            ['gaetan', 'loeb', '0777737468', 'loeb.gaetan@gmail.com', "BEST WESTERN PLUS Hôtel **** Au Cheval Blanc", '27 rue principale', '68390', 'MULHOUSE', 'hotel', true, true, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "j-ai-amenage-un-jardin-avec-differentes-herbes-aromatiques-sur-au-moins-1-du-terrain",
                        "j-ai-au-moins-un-hotel-a-insectes-nichoir-a-oiseaux-ou-chauve-souris-abris-a-herissons-avec-occupation-constatee-pour-chaque-500-m2-de-terrain",
                        "je-limite-drastiquement-l-eclairage-nocturne-les-lumieres-exterieures-sont-eteintes-au-plus-tard-2h-apres-le-coucher-du-soleil-sans-passage",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        "les-visiteurs-les-clients-peuvent-trier-leurs-dechets-et-j-ai-des-filieres-de-valorisation-en-place-pour-les-emballages",
                        "j-ai-mis-en-place-la-collecte-et-la-valorisation-des-biodechets-meme-si-je-ne-suis-pas-concerne-par-la-reglementation",
                        "je-donne-mes-equipements-en-fin-de-vie-et-en-bon-etat-de-fonctionnement-a-des-associations",
                        "j-ai-forme-mes-equipes-ou-me-suis-forme-e-si-je-travaille-seul-e-a-la-reduction-des-dechets",
                        "j-ai-forme-mes-equipes-ou-me-suis-forme-e-si-je-travaille-seul-e-a-la-reduction-du-gaspillage-alimentaire",
                        "je-n-imprime-plus-de-flyers-de-guides-ou-de-publications-papier",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        "j-evite-l-artificialisation-des-parkings-je-privilegie-le-revetement-poreux-les-espaces-bitumes-sont-limites-aux-voies-de-circulation-de-lourds-vehicules-et-pour-les-places-pmr",
                        "tous-les-points-d-eau-sont-dotes-de-reducteurs-de-debits-wc-double-debits-mousseurs-detecteurs-sous-robinets",
                        "je-limite-les-pertes-d-eau-lors-de-l-arrosage-des-espaces-verts-horaire-goutte-a-goutte-paillage-oyas",
                        "j-arrose-les-vegetaux-en-pleine-terre-uniquement-lors-de-leur-premiere-annee-et-je-n-arrose-pas-le-gazon",
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        "je-n-ai-que-du-double-ou-du-triple-vitrage",
                        "meubles-et-decoration-sont-majoritairement-en-bois-non-agglomere-non-exotique-a-base-de-vegetal-en-metal-ou-a-partir-d-objets-recycles-ou-de-seconde-vie",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        "j-ai-un-plan-de-gestion-des-temperatures-du-batiment-en-fonction-des-horaires-des-jours-des-pieces-et-des-usages",
                        "j-affiche-des-consignes-sur-les-reductions-d-energie-pour-moi-meme-ainsi-que-les-salaries",
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        "j-ai-des-consignes-claires-sur-l-utilisation-des-produits-d-entretien-ex-quantite-de-produit-par-rapport-a-la-durete-de-l-eau",
                        "j-utilise-des-pompes-de-dosage-pour-diluer-les-produits-concentres-ou-des-doseurs",
                        "pour-ma-piscine-j-ai-une-technique-de-traitement-limitant-le-chlore-voire-j-ai-cree-une-zone-de-baignade-naturelle-traitement-par-les-plantes-ce-qui-permet-de-limiter-l-usage-de-produits-chimiques-et-favorise-la-biodiversite",
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        "j-ai-au-moins-2-des-places-de-stationnement-equipees-de-recharges-pour-voitures-electriques",
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        "j-ai-forme-l-ensemble-de-mon-equipe-ainsi-que-moi-meme-a-l-accueil-des-personnes-en-situation-de-handicap",
                        "j-ai-a-disposition-des-fauteuils-ou-des-cannes-sieges",
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "j-accepte-les-cheques-vacances-ancv",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        "chaque-annee-l-ensemble-de-l-entreprise-est-sensibilise-sur-le-sujet-du-tourisme-durable-webinaire-formation-fresque-conference-projection",
                        "j-ai-fait-un-bilan-carbone-un-bilan-environnemental-ou-obtenu-un-label-responsable-depuis-moins-de-3-ans",
                        "j-ai-produit-une-charte-ou-un-engagement-de-developpement-durable-a-l-attention-de-mes-fournisseurs-partenaires",
                        "je-mets-en-avant-les-produits-locaux-de-saison-issus-de-l-agriculture-bio-ainsi-que-la-gastronomie-regionale",
                        "je-propose-des-plats-vegetariens-et-ou-vegans-que-je-mets-en-avant",
                        "je-mets-en-place-des-mesures-incitatives-pour-l-adoption-de-comportements-vertueux-a-l-attention-de-mes-clients-affichettes-pictos-infographie",
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        "j-ai-mis-en-place-un-management-facilitant-la-remontee-d-information-au-dela-du-cadre-legal-entretiens-reguliers-entretiens-croises-boite-a-idee",
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        "au-moins-80-de-mes-fournisseurs-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km",
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        "je-participe-au-moins-deux-fois-par-an-a-des-reunions-de-travail-avec-mon-ot-le-cdt-le-crt-ou-les-collectivites-locales",
                        "je-suis-partenaire-au-moins-une-fois-par-an-a-un-evenement-en-lien-avec-les-habitants-ou-associations-de-la-commune",
                        "l-un-de-nos-salaries-dirigeant-est-elu-ou-benevole-au-sein-d-une-instance-publique-ou-professionnelle-et-du-temps-lui-est-mis-a-disposition-pour-s-impliquer",
                        "la-structure-soutient-une-association-locale-par-du-don-financier-du-temps-offert-du-pret-de-materiel-ou-toute-forme-d-aide-significative",
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "notre-etablissement-restaure-ou-developpe-le-patrimoine-architectural-en-respectant-les-techniques-et-savoir-faire-traditionnels-locaux",
                        "je-propose-un-plat-traditionnel-local-dans-mon-menu-et-ou-je-le-mets-en-avant-dans-mon-offre-boutique",
                        "j-emploie-je-mets-en-avant-des-artistes-locaux",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'clef-verte',
                        'accueil-velo',
                        'maitre-restaurateur',
                    ],
                ]
            ],
            ['MARIE', 'GUTZWILLER', '0675666838', 'info@hotelbristol.com', "HOTEL BRISTOL", '18 AVENUE DE COLMAR', '68390', 'MULHOUSE', 'hotel', true, false, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "je-limite-drastiquement-l-eclairage-nocturne-les-lumieres-exterieures-sont-eteintes-au-plus-tard-2h-apres-le-coucher-du-soleil-sans-passage",
                        "j-ai-des-partenariats-avec-des-organismes-locaux-ou-nationaux-pour-la-valorisation-de-la-connaissance-sur-les-especes-locales-et-leur-observation",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        "j-ai-recours-a-un-systeme-de-consigne-pour-au-moins-la-moitie-de-mes-boissons",
                        "je-donne-mes-equipements-en-fin-de-vie-et-en-bon-etat-de-fonctionnement-a-des-associations",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        "tous-les-points-d-eau-sont-dotes-de-reducteurs-de-debits-wc-double-debits-mousseurs-detecteurs-sous-robinets",
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        "maximum-25-de-la-surface-du-batiment-est-age-de-moins-de-30-ans-je-favorise-l-ancien-et-la-renovation",
                        "je-n-ai-que-du-double-ou-du-triple-vitrage",
                        "tous-mes-murs-toits-sols-sont-isolees-en-plus-des-elements-porteurs",
                        "meubles-et-decoratio   n-sont-majoritairement-en-bois-non-agglomere-non-exotique-a-base-de-vegetal-en-metal-ou-a-partir-d-objets-recycles-ou-de-seconde-vie",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        "j-ai-des-consignes-claires-sur-l-utilisation-des-produits-d-entretien-ex-quantite-de-produit-par-rapport-a-la-durete-de-l-eau",
                        "j-utilise-des-pompes-de-dosage-pour-diluer-les-produits-concentres-ou-des-doseurs",
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        "j-ai-au-moins-2-des-places-de-stationnement-equipees-de-recharges-pour-voitures-electriques",
                        "j-ai-une-offre-dediee-aux-voyageurs-a-pied-en-train-ou-a-velo",
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "j-accepte-les-cheques-vacances-ancv",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        "chaque-annee-l-ensemble-de-l-entreprise-est-sensibilise-sur-le-sujet-du-tourisme-durable-webinaire-formation-fresque-conference-projection",
                        "j-ai-fait-un-bilan-carbone-un-bilan-environnemental-ou-obtenu-un-label-responsable-depuis-moins-de-3-ans",
                        "je-mets-en-avant-les-produits-locaux-de-saison-issus-de-l-agriculture-bio-ainsi-que-la-gastronomie-regionale",
                        "je-propose-des-plats-vegetariens-et-ou-vegans-que-je-mets-en-avant",
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        "je-participe-au-moins-deux-fois-par-an-a-des-reunions-de-travail-avec-mon-ot-le-cdt-le-crt-ou-les-collectivites-locales",
                        "je-suis-partenaire-au-moins-une-fois-par-an-a-un-evenement-en-lien-avec-les-habitants-ou-associations-de-la-commune",
                        "l-un-de-nos-salaries-dirigeant-est-elu-ou-benevole-au-sein-d-une-instance-publique-ou-professionnelle-et-du-temps-lui-est-mis-a-disposition-pour-s-impliquer",
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "je-propose-un-plat-traditionnel-local-dans-mon-menu-et-ou-je-le-mets-en-avant-dans-mon-offre-boutique",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                ]
            ],
            ['EMMA', 'ARTGE', '0123456789', 'test@test.com', "TEST EMMA", '18 AVENUE DE COLMAR', '68390', 'MULHOUSE', 'hotel', false, false, '2023-04-21 14:29:56', '2023-04-21 14:43:34',
                [
                    ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value => [
                        "je-limite-drastiquement-l-eclairage-nocturne-les-lumieres-exterieures-sont-eteintes-au-plus-tard-2h-apres-le-coucher-du-soleil-sans-passage",
                        "j-ai-des-partenariats-avec-des-organismes-locaux-ou-nationaux-pour-la-valorisation-de-la-connaissance-sur-les-especes-locales-et-leur-observation",
                    ],
                    ThematiqueSlugEnum::GESTION_DES_DECHETS->value => [
                        "je-privilegie-le-materiel-d-occasion-au-moins-20-notamment-pour-l-ameublement-et-le-materiel-electronique",
                        "je-donne-mes-equipements-en-fin-de-vie-et-en-bon-etat-de-fonctionnement-a-des-associations",
                        "j-ai-forme-mes-equipes-ou-me-suis-forme-e-si-je-travaille-seul-e-a-la-reduction-des-dechets",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value => [
                        "je-recycle-l-eau-de-certains-usages-rincage-eau-de-cuisson-carafes-d-eau-pour-l-arrosage-exterieur",
                        "tous-les-points-d-eau-sont-dotes-de-reducteurs-de-debits-wc-double-debits-mousseurs-detecteurs-sous-robinets",
                    ],
                    ThematiqueSlugEnum::ECO_CONSTRUCTION->value => [
                        "j-ai-utilise-au-moins-3-materiaux-biosources-pour-la-construction-ou-l-isolation-bois-paille-chanvre-liege-ouate-de-cellulose",
                        "meubles-et-decoratio   n-sont-majoritairement-en-bois-non-agglomere-non-exotique-a-base-de-vegetal-en-metal-ou-a-partir-d-objets-recycles-ou-de-seconde-vie",
                    ],
                    ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value => [
                        'j-affiche-des-consignes-sur-les-reductions-d-energie-pour-moi-meme-ainsi-que-les-salaries',
                        'une-ventilation-double-flux-et-ou-bouches-d-aeration-hygroreglables-sont-installees'
                    ],
                    ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE->value => [
                        "j-ai-des-consignes-claires-sur-l-utilisation-des-produits-d-entretien-ex-quantite-de-produit-par-rapport-a-la-durete-de-l-eau",
                        "j-utilise-des-pompes-de-dosage-pour-diluer-les-produits-concentres-ou-des-doseurs",
                    ],
                    ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE->value => [
                        "j-ai-au-moins-2-des-places-de-stationnement-equipees-de-recharges-pour-voitures-electriques",
                        "j-ai-une-offre-dediee-aux-voyageurs-a-pied-en-train-ou-a-velo",
                        "je-communique-des-instructions-claires-pour-realiser-des-activites-autour-de-mon-site-sans-voiture"
                    ],
                    ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP->value => [
                        'je-dispose-de-la-marque-d-Etat-tourisme-et-handicap-visuel',
                        'je-dispose-de-la-marque-d-Etat-tourisme-et-handicap-auditif',
                        'j-accueille-les-chiens-guides'
                    ],
                    ThematiqueSlugEnum::INCLUSIVITE_SOCIALE->value => [
                        "je-propose-des-offres-accessibles-a-tout-public-avec-une-tarification-ou-des-prestations-adaptees",
                    ],
                    ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                    ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE->value => [
                        'j-ai-mis-en-place-un-management-facilitant-la-remontee-d-information-au-dela-du-cadre-legal-entretiens-reguliers-entretiens-croises-boite-a-idee',
                        'des-actions-sont-mises-en-oeuvre-pour-prevenir-et-limiter-les-tms-troubles-musculo-squelettiques-et-psychologiques'
                    ],
                    ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL->value => [
                        'au-moins-80-de-mes-fournisseurs-non-alimentaires-sont-locaux-dans-un-rayon-de-moins-de-150-km',
                    ],
                    ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS->value => [
                        "l-un-de-nos-salaries-dirigeant-est-elu-ou-benevole-au-sein-d-une-instance-publique-ou-professionnelle-et-du-temps-lui-est-mis-a-disposition-pour-s-impliquer",
                        "la-structure-soutient-une-association-locale-par-du-don-financier-du-temps-offert-du-pret-de-materiel-ou-toute-forme-d-aide-significative",
                    ],
                    ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE->value => [
                        "notre-structure-soutient-une-association-culturelle-locale-ou-non",
                    ],
                    ThematiqueSlugEnum::LABELS->value => [
                        'je-n-ai-rien-entrepris-en-ce-sens',
                    ],
                ]
            ],
        ];

        foreach ($repondantDatas as $repondantData) {
            $repondant = new Repondant();
            $typologie = $repondantData[8];

            $repondant->setFirstname($repondantData[0]);
            $repondant->setLastname($repondantData[1]);
            $repondant->setPhone($repondantData[2]);
            $repondant->setEmail($repondantData[3]);
            $repondant->setCompany($repondantData[4]);
            $repondant->setAddress($repondantData[5]);
            $repondant->setZip($repondantData[6]);
            $repondant->setCity($repondantData[7]);
            $repondant->setCountry('France');
            $repondant->setRestauration($repondantData[9]);
            $repondant->setGreenSpace($repondantData[10]);
            $repondant->setDepartment($this->departmentRepository->findOneBy(['slug' => 'alsace']));
            $repondant->setTypologie($this->typologieRepository->findOneBy(['slug' => $typologie]));
            $manager->persist($repondant);

            $requestStack = new RequestStack();
            $requestStack->push(new Request([], [
                'reponse' => [
                    'repondant' => [
                        'typologie' => $repondant->getTypologie()->getId(),
                        'restauration' => $repondant->isRestauration() ? '1' : '0',
                    ],
                ]
            ]));

            $reponse = new Reponse();
            $reponse->setUuid(Ulid::fromString($this->faker->uuid()));
            $reponse->setRepondant($repondant);
            $reponse->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $repondantData[11]));
            $reponse->setSubmittedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $repondantData[12]));
            $reponse->setCompleted(true);

            $rawForm = [];
            foreach ($repondantData[13] as $thematiqueSlug => $repondantChoicesSlugs) {
                $thematique = $this->thematiqueRepository->findOneBy(['slug' => $thematiqueSlug]);
                $question = $thematique->getQuestion();

                foreach ($repondantChoicesSlugs as $repondantChoicesSlug){
                    $choice = $this->choiceRepository->findOneBy(['question' => $question->getId(),'slug' => str_replace(' ', '', $repondantChoicesSlug)]);
                    if ($choice){
                        $reponse->addChoice($choice);
                        $rawForm[$question->getId()]['answers'][$choice->getId()] = 'on';
                    } else {
                        dump('choice not found for slug : ' . $repondantChoicesSlug);
                    }
                }
            }
            $reponse->setRawForm($rawForm);

            $processor = new ProcessedFormReponseDataTransformer($requestStack, $this->choiceTypologieRepository, $this->choiceRepository, $this->thematiqueRepository);
            $processedAnswers = $processor->reverseTransform($reponse->getRawForm());
            $reponse->setProcessedForm($processedAnswers);

            $scoreGeneration = $this->reponseScoreGeneration->generateScore($reponse);
            $reponse->setPoints($scoreGeneration->getPoints());
            $reponse->setTotal($scoreGeneration->getTotal());
            foreach ($scoreGeneration->getScores() as $score) {
                $reponse->addScore($score);
                $manager->persist($score);
            }
            $manager->persist($reponse);
            //            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ChoiceTypologiesFixtures::class,
            CitiesFixtures::class,
        ];
    }
}
