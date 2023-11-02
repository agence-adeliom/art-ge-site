<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Entity\Choice;
use App\Entity\Question;
use App\Entity\Thematique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class QuestionsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        $questions = [
            "Biodiversité et conservation de la Nature sur site" => [
                "Quelles pratiques avez-vous mis en place dans votre structure pour limiter l'impact sur la biodiversité et conserver la nature ?",
                "J'ai aménagé un jardin avec différentes herbes aromatiques sur au moins 1% du terrain",
                "J'ai au moins un hôtel à insectes, nichoir à oiseaux ou chauve-souris, abris à hérissons avec occupation constatée, pour chaque 500 m² de terrain",
                "Je n'utilise jamais d'insecticides",
                "Je n'utilise jamais de produits de traitements fongiques chimiques",
                "Je limite drastiquement l'éclairage nocturne (les lumières extérieures sont éteintes au plus tard 2h après le coucher du soleil, sans passage)",
                "J'ai 7 espèces de haies différentes, gérées de manière responsable (taille hors période de nidification.) J'ai planté des essences locales et résilientes au changement climatique (pas d'espèces invasives et exotiques",
                "Je dispose d'une mare ou d'un plan d'eau végétalisé sur au moins 1% du terrain",
                "Je procède à un fauchage tardif des espaces verts (après l'été)",
                "Je conserve des arbres morts ou du bois mort au sol",
                "J'ai créé des passages pour la petite faune au pied des clôtures",
                "J’ai un potager en permaculture / 0 phyto, sur au moins 2% de mon terrain et je valorise sa production sur ma carte",
                "J'ai des partenariats avec des organismes locaux ou nationaux pour la valorisation de la connaissance sur les espèces locales et leur observation.",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Gestion des déchets" => [
                "Je réduis la production et gère mieux mes déchets car j'ai mis en place l'une ou plusieurs des actions suivantes :",
                "Je composte, paille, broie pour les disposer au pied des plantations (SI ESPACES VERTS)",
                "J'ai supprimé les plastiques à usage unique et tous mes emballages plastiques sont en grands formats (supérieur à 5L)",
                "J'ai recours à un système de consigne pour au moins la moitié de mes boissons",
                "Si restauration à emporter, je propose un système de boites consignées et la possibilité d'utiliser les boites des clients",
                "Les visiteurs, les clients peuvent trier leurs déchets et j'ai des filières de valorisation en place pour les emballages",
                "J'ai mis en place la collecte et la valorisation des biodéchets (même si je ne suis pas concerné par la réglementation)",
                "Je privilégie le matériel d'occasion (au moins 20%), notamment pour l'ameublement et le matériel électronique",
                "Je donne mes équipements en fin de vie et en bon état de fonctionnement à des associations",
                "J'ai formé mes équipes, ou me suis formé(e) si je travaille seul(e),  à la réduction des déchets",
                "J'ai formé mes équipes, ou me suis formé(e) si je travaille seul(e), à la réduction du gaspillage alimentaire",
                "Je n'imprime plus de flyers, de guides ou de publications papier",
                "Mes contrats avec les prestataires externes indiquent mes critères de réductions et de valorisations des déchets",
                "J'ai mis le tri 5 flux (bois, carton, verre, métal, plastique) en place (même si je ne suis pas concerné par la réglementation)",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Gestion de l'eau et de l'érosion" => [
                "Je préviens les risques de sécheresse et d'inondation par l'une ou plusieurs de ces actions",
                "J'évite l'artificialisation des parkings, je privilégie le revêtement poreux. Les espaces bitumés sont limités aux voies de circulation de lourds véhicules et pour les places PMR",
                "J'ai installé un système de récupération d'eau de pluie qui me permet de couvrir au moins 10% de ma consommation d'eau",
                "Je recycle l'eau de certains usages (rinçage, eau de cuisson, carafes d'eau …) pour l'arrosage extérieur",
                "J'ai installé un système de phyto-épuration (traitement de l'eau d'assainissement par les plantes)",
                "J'ai mis en place un système de toilettes sèches",
                "Tous les points d'eau sont dotés de réducteurs de débits (WC double débits, mousseurs, détecteurs sous robinets…)",
                "Je limite les pertes d'eau lors de l'arrosage des espaces verts (horaire, goutte à goutte, paillage, oyas...)",
                "J'arrose les végétaux en pleine terre uniquement lors de leur première année et je n'arrose pas le gazon",
                "J'optimise quotidiennement, grâce à la filtration, le renouvellement d'eau de ma piscine (moins de 3% du volume de ma piscine ou moins de 50 litres renouvelés /baignade)",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Eco-construction" => [
                "Quelles actions avez-vous mises en place pour limiter l'impact de votre bâtiment ?",
                "J'ai appliqué le bioclimatisme : valoriser l'orientation du bâtiment avec de grandes ouvertures, casquettes solaires, végétalisation coupe-vent…",
                "Maximum 25% de la surface du bâtiment est âgé de moins de  30 ans (je favorise l'ancien et la rénovation)",
                "J'ai un label signifiant que le bâtiment consomme peu d'énergie (HQE, BBC, Breeam,…) ou DPE (Classement A ou B)",
                "Je n'ai que du double ou du triple vitrage",
                "Tous mes murs, toits, sols sont isolées en plus des éléments porteurs",
                "J'ai utilisé au moins 3 matériaux biosourcés pour la construction ou l'isolation (bois, paille, chanvre, liège, ouate de cellulose…)",
                "Meubles et décoration sont majoritairement en bois (non aggloméré, non exotique), à base de végétal, en métal ou à partir d'objets recyclés ou de seconde vie",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Gestion de l'énergie" => [
                "Quelles actions avez-vous mises en place pour limiter les consommations d'énergie ?",
                "J'ai un compteur connecté ou un logiciel de gestion pour piloter/suivre les consommations d'énergie (chauffage, climatisation, équipements...)",
                "J'ai un plan de gestion des températures du bâtiment en fonction des horaires, des jours, des pièces et des usages",
                "Je produis au moins 25% de mon eau chaude avec le soleil ou j'ai au minimum 0,5 m² de panneau par logement pour les hébergements",
                "Je n'ai pas de climatisation",
                "Je produis de l'électricité photovoltaïque ou éolienne",
                "J'ai un abonnement d'électricité avec des garanties d'origine renouvelable de l'énergie dans le bâtiment",
                "Je chauffe au moins 80% du bâtiment au bois ou avec de la géothermie, en limitant l'usage d'énergie fossile",
                "Je produis au moins 80% de mon eau chaude, hors production solaire, avec du bois ou avec de la géothermie",
                "J'affiche des consignes sur les réductions d'énergie pour moi-même ainsi que les salariés",
                "J'ai installé un récupérateur de chaleur pour préchauffer l'eau chaude sanitaire",
                "J'affiche un plan d'allumage pour les salariés (pour les équipements de cuisson)",
                "J'affiche un plan d'entretien pour les équipements de froid (réfrigérateurs nettoyés régulièrement, suivi des températures...)",
                "Une ventilation double flux et/ou bouches d'aération hygroréglables sont installées",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Entretien et propreté" => [
                "Quelles actions sont mises en place pour réduire l'usage de produits dangereux lié à l'entretien des locaux ?",
                "Mes produits d'entretien sont sans produits chimiques (eau ozonée, nettoyage vapeur sèche) et/ou plus de 80% de mes produits sont écolabellisés (Ecocert, Ecolabel Européen ou équivalent) ou faits maison",
                "J'ai des consignes claires sur l'utilisation des produits d'entretien (ex : quantité de produit par rapport à la dureté de l'eau...)",
                "J'utilise des pompes de dosage pour diluer les produits concentrés ou des doseurs",
                "Pour ma piscine : j'ai une technique de traitement limitant le chlore, voire j'ai créé une zone de baignade naturelle (traitement par les plantes) ce qui permet de limiter l'usage de produits chimiques et favorise la biodiversité",
                "J'ai des critères environnementaux stricts dans mon contrat de prestation de nettoyage des locaux (produits labellisés ou techniques de nettoyages alternatives)",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Transport et mobilité" => [
                "Comment j'invite les personnes à se déplacer sans voiture ?",
                "J'ai rédigé un plan de mobilité pour mes collaborateurs et moi",
                "Tous les conducteurs de ma structure ont été formés à l'écoconduite. Si je suis seul(e), j'ai fait une formation - suivi un stage sur le sujet.",
                "J'ai au moins 20% des places de stationnement dédiées à un parking vélo",
                "Je communique des informations claires sur les transports collectifs, le covoiturage, les transports publics pour encourager à venir sans voiture",
                "J'ai au moins 2% des places de stationnement équipées de recharges pour voitures électriques",
                "J'ai une offre dédiée aux voyageurs à pied, en train ou à vélo",
                "Je communique des instructions claires pour réaliser des activités autour de mon site sans voiture",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Accès aux personnes en situation de handicap" => [
                "Parmi ces actions et dispositifs, lesquels avez-vous mis en place ?",
                "J'ai formé l'ensemble de mon équipe, ainsi que moi-même, à l'accueil des personnes en situation de handicap",
                "Je dispose de la marque d'État Tourisme et Handicap moteur",
                "Je dispose de la marque d'État Tourisme et Handicap mental",
                "Je dispose de la marque d'État Tourisme et Handicap visuel",
                "Je dispose de la marque d'État Tourisme et Handicap auditif",
                "J'accueille les chiens guides",
                "Je mets à disposition des supports de médiation adaptés aux différents handicaps (menu en braille…)",
                "J'ai à disposition des fauteuils ou des cannes-sièges",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Inclusivité sociale" => [
                "J'élargis l'accessibilité de mon offre :",
                "Je propose des offres accessibles à tout public avec une tarification ou des prestations adaptées",
                "J'accepte les chèques vacances ANCV",
                "L'entreprise dispose d'un partenariat avec une association pour accueillir des publics jeunes / séniors / défavorisés",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Sensibilisation des acteurs" => [
                "Je sensibilise les clients, salariés et fournisseurs :",
                "Chaque année, l'ensemble de l'entreprise est sensibilisé sur le sujet du tourisme durable (webinaire, formation, fresque, conférence, projection...)",
                "J'ai fait un bilan carbone, un bilan environnemental ou obtenu un label responsable depuis moins de 3 ans",
                "J’ai produit une charte ou un engagement de développement durable à l’attention de mes fournisseurs/partenaires",
                "Je mets en avant les produits locaux, de saison, issus de l'agriculture bio, ainsi que la gastronomie régionale",
                "Je propose des plats végétariens et/ou végans que je mets en avant",
                "J'organise des animations sur l'observation de la faune et de la flore, je sensibilise à la biodiversité et l'environnement",
                "Je mets en place des mesures incitatives pour l'adoption de comportements vertueux à l’attention de mes clients (affichettes, pictos, infographie…)",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Bien-être de l'équipe" => [
                "J'agis de la manière suivante pour prévenir le mal-être au travail",
                "Une enquête annuelle mesure le bien-être de l'équipe",
                "J'ai mis en place un management facilitant la remontée d'information au-delà du cadre légal (entretiens réguliers, entretiens croisés, boite à idée…)",
                "Des actions sont mises en œuvre pour prévenir et limiter les TMS (troubles musculo squelettiques) et psychologiques",
                "Je n'ai rien entrepris en ce sens",
                "Je ne suis pas concerné(e) car je n'ai pas d'équipe",
            ],
            "Développement économique local" => [
                "Je favorise les retombées économiques locales par l'une de ces actions :",
                "Au moins 80% de mes fournisseurs NON-alimentaires sont locaux (dans un rayon de moins de 150 km)",
                "Au moins 80% de mes fournisseurs alimentaires sont locaux (dans un rayon de moins de 150 km)",
                "J'ai un partenariat avec une entreprise de réinsertion locale",
                "J'ai développé des prestations en synergie avec d'autres entreprises de mon territoire (ma commune ou communes voisines)",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Coopération locale et liens avec les habitants" => [
                "Votre structure met en œuvre l'une de ces actions en lien avec le tissus local :",
                "Je participe au moins deux fois par an à des réunions de travail avec mon OT, le CDT, le CRT, ou les collectivités locales",
                "Je suis partenaire au moins une fois par an à un évènement en lien avec les habitants ou associations de la commune",
                "L'un de nos salariés / dirigeant est élu ou bénévole au sein d'une instance publique ou professionnelle et du temps lui est mis à disposition pour s'impliquer",
                "La structure soutient une association locale par du don financier, du temps offert, du prêt de matériel ou toute forme d'aide significative",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Culture et patrimoine" => [
                "Je favorise ou protège le patrimoine local par l'une de ces actions :",
                "Notre structure soutient une association culturelle (locale ou non)",
                "Notre établissement restaure ou développe le patrimoine architectural en respectant les techniques et savoir faire traditionnels locaux",
                "Nous participons à au moins un évènement culturel chaque année",
                "Je propose un plat traditionnel local dans mon menu et je le mets en avant dans mon offre / boutique",
                "J'emploie / je mets en avant des artistes locaux",
                "Je n'ai rien entrepris en ce sens",
            ],
            "Labels" => [
                "Votre établissement dispose-t-il d'un de ces labels ?",
                "Clef Verte",
                "Ecogite - Gîtes de France",
                "Gite Panda - Gites de France",
                "Ecolabel Européen",
                "Hôtels au Naturel",
                "Etiquette Environnementale Ademe",
                "ABCD Tourism - La Note Touristique",
                "Camping Qualité",
                "Chouette Nature",
                "Esprit Parc National",
                "Valeurs Parc Naturel Régional",
                "Ecotable",
                "Maître Restaurateur",
                "Etoile Verte Michelin",
                "Bistrot de Pays",
                "Bon pour le Climat",
                "Accueil Paysan",
                "Bienvenue à la Ferme",
                "Earth Check",
                "Green Globe",
                "ISO 20121",
                "ISO 14001",
                "ISO 50001",
                "Label ATES",
                "Accueil Vélo",
                "Rando Accueil",
                "Divertissement Durable",
                "NF Environnement",
                "Je n'ai rien entrepris en ce sens",
            ],
        ];

        foreach ($questions as $t => $q) {
            $thematique = new Thematique();
            $thematique->setName($t);
            $thematique->setSlug($slugger->slug(strtolower($t))->toString());
            $manager->persist($thematique);

            $question = new Question();
            $question->setThematique($thematique);
            $question->setLibelle($q[0]);

            foreach ($q as $key => $c) {
                if ($key === 0) {
                    continue;
                }
                $choice = new Choice();
                $choice->setQuestion($question);
                $choice->setLibelle($c);
                $choice->setSlug($slugger->slug(strtolower($c))->toString());
                $manager->persist($choice);
            }
        }
        $manager->flush();
    }
}
