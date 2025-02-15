<?php

declare(strict_types=1);

namespace App\Enum;

enum PilierEnum: string
{
    case ENVIRONNEMENT = 'environnement';

    case ECONOMIE = 'economie';
    case SOCIAL = 'social';

    public static function getThematiquesSlugsByPilier(self $pilier): array
    {
        return match ($pilier) {
            self::ENVIRONNEMENT => [
                ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE,
                ThematiqueSlugEnum::GESTION_DES_DECHETS,
                ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION,
                ThematiqueSlugEnum::ECO_CONSTRUCTION,
                ThematiqueSlugEnum::GESTION_DE_L_ENERGIE,
                ThematiqueSlugEnum::ENTRETIEN_ET_PROPRETE,
                ThematiqueSlugEnum::TRANSPORT_ET_MOBILITE,
            ],
            self::ECONOMIE => [
                ThematiqueSlugEnum::DEVELOPPEMENT_ECONOMIQUE_LOCAL,
                ThematiqueSlugEnum::CULTURE_ET_PATRIMOINE,
                ThematiqueSlugEnum::COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS,
            ],
            self::SOCIAL => [
                ThematiqueSlugEnum::ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP,
                ThematiqueSlugEnum::INCLUSIVITE_SOCIALE,
                ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS,
                ThematiqueSlugEnum::BIEN_ETRE_DE_L_EQUIPE,
            ],
        };
    }
}
