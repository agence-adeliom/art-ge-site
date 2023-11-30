import React, {useState} from 'react';
import { Heading } from '@components/Typography/Heading'
import { Text } from '@components/Typography/Text'
import { Button } from '@components/Action/Button'
import YesNoCard from '@components/Forms/YesNoCard';

const StepThree = ({isRestaurant, setIsRestaurant, setIsGreenSpace, isGreenSpace, nextStep} : {
    isRestaurant: string,
    setIsRestaurant: Function,
    setIsGreenSpace: Function,
    isGreenSpace: string,
    nextStep: Function
}) => {
    const cardClassName = "col-span-1 w-full p-4 cursor-pointer flex gap-4 flex items-center border border-neutral-200 group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50 peer"
    return (
        <>
            <Heading variant="display-4">Vous êtes...</Heading>
            <Text className="mt-6" color="neutral-500" weight={400} size="sm">Indiquez l’activité de votre établissement touristique.</Text>
            
            <div className="mt-8">
                <Text className="mb-4" color="neutral-700" weight={400} size="sm">Proposez-vous une offre de restauration (panier, pique-nique, restaurant…) ?</Text>

                <YesNoCard handleChoice={setIsRestaurant} choice={isRestaurant} className={cardClassName} id="restaurant"></YesNoCard>            
            </div>
            <div className="mt-8">
                <YesNoCard handleChoice={setIsGreenSpace} choice={isGreenSpace} className={cardClassName} id="greenSpace"></YesNoCard>            
            </div>
            
            <Button 
                size="lg" 
                className="mt-8"
                disabled={isGreenSpace === '' || isRestaurant === '' ? true : false} 
                icon="fa-minus"
                iconSide="left"
                onClick={event => {event.preventDefault(); nextStep() }}
                >
                    Suivant
            </Button>
        </>
    )
}

export default StepThree