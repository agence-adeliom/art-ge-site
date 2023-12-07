<?php

declare(strict_types=1);

namespace App\Enum;

enum ThematiqueSlugEnum: string
{
    case BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE = 'biodiversite-et-conservation-de-la-nature-sur-site';
    case GESTION_DE_L_EAU_ET_DE_L_EROSION = 'gestion-de-l-eau-et-de-l-erosion';
    case ECO_CONSTRUCTION = 'eco-construction';
    case GESTION_DE_L_ENERGIE = 'gestion-de-l-energie';
    case ENTRETIEN_ET_PROPRETE = 'entretien-et-proprete';
    case TRANSPORT_ET_MOBILITE = 'transport-et-mobilite';
    case ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP = 'acces-aux-personnes-en-situation-de-handicap';
    case INCLUSIVITE_SOCIALE = 'inclusivite-sociale';
    case SENSIBILISATION_DES_ACTEURS = 'sensibilisation-des-acteurs';
    case BIEN_ETRE_DE_L_EQUIPE = 'bien-etre-de-l-equipe';
    case DEVELOPPEMENT_ECONOMIQUE_LOCAL = 'developpement-economique-local';
    case COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS = 'cooperation-locale-et-liens-avec-les-habitants';
    case CULTURE_ET_PATRIMOINE = 'culture-et-patrimoine';

    case LABELS = 'labels';
}
