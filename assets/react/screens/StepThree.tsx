import React, {useState} from 'react';
import { Heading } from '@components/Typography/Heading'
import { Text } from '@components/Typography/Text'
import { Button } from '@components/Action/Button'
import { YesNoCard } from '@components/Fields/YesNoCard';

const cardClassName = "col-span-1 w-full p-4 cursor-pointer flex gap-4 flex items-center border border-neutral-200 group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50 peer"

const StepThree = ({isRestaurant, setIsRestaurant, setIsGreenSpace, isGreenSpace, nextStep, setIsLoading} : {
    isRestaurant: string,
    setIsRestaurant: Function,
    setIsGreenSpace: Function,
    isGreenSpace: string,
    nextStep: Function,
    setIsLoading: Function
}) => {

    const getQuestions = () => {
        let formAPI = 'api/form?green_space=';
        let greenSpaceChoice =
          isGreenSpace === 'true' ? true : isGreenSpace === 'false' ? false : null;
        let formAPIresults = formAPI + greenSpaceChoice;
    
        if (greenSpaceChoice !== null) {
          fetch(formAPIresults)
            .then(async (response: Response) => {
              window.localStorage.setItem(
                'allQuestions',
                JSON.stringify(await response.json()),
              );
            setIsLoading(false)

            })
            .catch(() => {
              console.log('error');
            });
        }
      };


    const arrayQuestions = [
        {
            text: `Disposez-vous d'un espace vert, d'un espace extérieur de plus de 100m2 ?`,
            choice: isRestaurant,
            handleChoice: setIsRestaurant,
            id: 'restaurant'
        },
        {
            text: `Proposez-vous une offre de restauration (panier, pique-nique, restaurant…) ?`,
            choice: isGreenSpace,
            handleChoice: setIsGreenSpace,
            id: 'greenSpace'
        },
    ]
    return (
        <>
            <Heading variant="display-4">Vous êtes...</Heading>
            <Text className="mt-6" color="neutral-500" weight={400} size="sm">Indiquez l’activité de votre établissement touristique.</Text>

            
            {arrayQuestions.map((item, index) => {
                return (
                    <div className="mt-8" key={index}>
                        <Text className="mb-4" color="neutral-700" weight={400} size="sm">
                            {item.text}
                        </Text>
                        <YesNoCard handleChoice={item.handleChoice} choice={item.choice} className={cardClassName} id={item.id}></YesNoCard>            
                    </div>
                )
            })}
            
            <Button 
                size="lg" 
                className="mt-8"
                disabled={isGreenSpace === '' || isRestaurant === '' ? true : false} 
                icon="fa-minus"
                iconSide="left"
                onClick={event => {event.preventDefault(); nextStep(); getQuestions() }}
                >
                    Suivant
            </Button>
        </>
    )
}

export default StepThree