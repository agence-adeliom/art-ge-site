import React from "react";
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import Bar from "@components/Graph/Bar";
import DurabilityCursor from "@components/Graph/DurabilityCursor";
import { ActorsScoresList } from "@react/types/Dashboard";

const ActorsScores = ({ scores } : {
    scores: ActorsScoresList
}) => {
    return (
        <div className="px-4 lg:px-10 max-lg:pr-0 py-12">
            <Heading variant="display-4">Score détaillé par type d’acteur touristique</Heading>
            <Text className="mt-4" size="sm">Vous trouverez ci-dessous le score global de l’ensemble des filtres sélectionnés pour chaque typologie de prestataire.</Text>
            <div className="w-full overflow-auto">
                <div className="grid grid-cols-8 gap-4 mt-10 relative w-max lg:w-full max-lg:mr-10 ">
                    {Object.entries(scores).map(([actor, score]) => (
                        <Bar 
                            key={actor}
                            percentage={parseInt(score, 10)} 
                            type={actor}
                        />
                    ))}
                    <svg className="block print:hidden w-full absolute left-0 top-[216px]" width="100%" height="1" viewBox="0 0 100% 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line y1="0.5" x2="100%" y2="0.5" stroke="#71717A" stroke-dasharray="8 8"/>
                    </svg>
                </div>
                <DurabilityCursor />
            </div>
            
        </div>
    )
}

export default ActorsScores