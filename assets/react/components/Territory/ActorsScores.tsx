import React from "react";
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import Bar from "@components/Graph/Bar";
import DurabilityCursor from "@components/Graph/DurabilityCursor";

const hostelIcon = "fa-duotone fa-hotel"
const campingIcon = "fa-duotone fa-campground"
const tentIcon = "fa-duotone fa-tent"
const ustensilIcon = "fa-duotone fa-utensils"
const bedRoomIcon = "fa-duotone fa-bed-front"
const vacation = "fa-duotone fa-apartment"
const visit = "fa-duotone fa-map-location-dot"

const TouristsScores = () => {
    return (
        <div className="px-10 py-12">
            <Heading variant="display-4">Score détaillé par type d’acteur touristique</Heading>
            <Text className="mt-4" size="sm">Vous trouverez ci-dessous le score global de l’ensemble des filtres sélectionnés pour chaque typologie de prestataire.</Text>

            <div className="flex gap-4 mt-10 relative">
                <Bar 
                    color={'#264653'} 
                    percentage={22} 
                    icon={hostelIcon}
                    type="Hôtel"
                />
                <Bar 
                    color={'#2A9D8F'} 
                    percentage={44} 
                    icon={campingIcon} 
                    type="Hébergements insolites"
                />

                <Bar 
                    color={'#DEA823'} 
                    percentage={39} 
                    icon={tentIcon} 
                    type="Campings ou locatifs de plein air"
                />

                <Bar 
                    color={'#C5671D'} 
                    percentage={41} 
                    icon={ustensilIcon} 
                    type="Restaurants"
                />
                <Bar 
                    color={'#E55E3C'} 
                    percentage={41} 
                    icon={bedRoomIcon} 
                    type="Chambres d’hôte"
                />
                <Bar 
                    color={'#664E76'} 
                    percentage={41} 
                    icon={vacation} 
                    type="Locations de vacances"
                />
                <Bar 
                    color={'#B56576'} 
                    percentage={33} 
                    icon={visit} 
                    type="Lieux de visite"
                />
               <div className="print:hidden w-full h-1 border-b border-neutral-500 border-dashed absolute left-0 top-[216px]"></div>
            </div>
            <DurabilityCursor />
        </div>
    )
}

export default TouristsScores