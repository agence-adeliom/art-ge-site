<?php

declare(strict_types=1);

namespace App\Enum;

enum ThematiqueSlugEnum: string
{
    case BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE = 'biodiversite-et-conservation-de-la-nature-sur-site';
    case GESTION_DE_L_EAU_ET_DE_L_EROSION = 'gestion-de-l-eau-et-de-l-erosion';
    CASE ECO_CONSTRUCTION = 'eco-construction';
    CASE GESTION_DE_L_ENERGIE = 'gestion-de-l-energie';
    CASE ENTRETIEN_ET_PROPRETE = 'entretien-et-proprete';
    CASE TRANSPORT_ET_MOBILITE = 'transport-et-mobilite';
    CASE ACCES_AUX_PERSONNES_EN_SITUATION_DE_HANDICAP = 'acces-aux-personnes-en-situation-de-handicap';
    CASE INCLUSIVITE_SOCIALE = 'inclusivite-sociale';
    CASE SENSIBILISATION_DES_ACTEURS = 'sensibilisation-des-acteurs';
    CASE BIEN_ETRE_DE_L_EQUIPE = 'bien-etre-de-l-equipe';
    CASE DEVELOPPEMENT_ECONOMIQUE_LOCAL = 'developpement-economique-local';
    CASE COOPERATION_LOCALE_ET_LIENS_AVEC_LES_HABITANTS = 'cooperation-locale-et-liens-avec-les-habitants';
    CASE CULTURE_ET_PATRIMOINE = 'culture-et-patrimoine';

    CASE LABELS = 'labels';
}
