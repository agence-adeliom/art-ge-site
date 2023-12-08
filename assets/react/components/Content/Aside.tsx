import React from "react";
import {Text} from '@components/Typography/Text';
import {Heading} from '@components/Typography/Heading';
import peopleGroup from '@icones/people-group.svg';
import lapTop from '@icones/laptop.svg';
import bioDiversity from '@icones/bio-diversite.svg';
import leafs from '@icones/leafs.svg';

const Aside = () => {
    return (
        <>
        {/* Aside */}
        <div className="col-span-full lg:col-start-9 lg:row-span-2 lg:col-span-4 dark  h-full lg:min-h-screen py-10 lg:p-10 pr-0 flex items-center relative">
            <div className="mobileLeftBleed containerBleed h-full absolute top-0 lg:left-0 z-0 bg-secondary-950 overflow-hidden">
                <img src={leafs} className="block absolute -bottom-7 -right-7 w-72 aspect-square" alt="background feuilles" />
            </div>
            <div className="flex flex-col gap-10 relative z-2">
                {/* First Element */}
                <div className="flex gap-4">
                    <div className="iconClass bg-secondary-200">
                        <img src={peopleGroup} alt="icon groupe"/>
                    </div>
                    <div className="flex flex-col gap-2">
                        <Heading weight={400} variant="display-4" color="white">Avant de vous lancer :</Heading>
                        <Text size="sm" weight={500} color="white">Prévoyez environ 15 minutes pour remplir le questionnaire.</Text>
                    </div>
                </div>
                {/* Second Element */}
                <div className="flex gap-4">
                    <div className="iconClass bg-secondary-200">
                        <img src={lapTop} alt="icon ordinateur"/>
                    </div>
                    <div className="flex flex-col gap-2">
                        <Heading weight={400} variant="display-4" color="white">Diagnostic :</Heading>
                        <Text size="sm" weight={500} color="white">Vous visualiserez votre diagnostic instantanément, vos actions à capitaliser ainsi que les nouvelles orientations à donner à votre stratégie.</Text>
                    </div>
                </div>
                {/* Third Element */}
                <div className="flex gap-4">
                    <div className="iconClass bg-secondary-200">
                        <img src={bioDiversity} alt="icon bio diversité"/>
                    </div>
                    <div className="flex flex-col gap-2">
                        <Heading weight={400} variant="display-4" color="white">Et ensuite ?</Heading>
                        <Text size="sm" weight={500} color="white">Profitez de nos outils ainsi que de nos accompagnements personnalisés.</Text>
                    </div>
                </div>
            </div>      
            </div>
        </>
    )
}

export default Aside