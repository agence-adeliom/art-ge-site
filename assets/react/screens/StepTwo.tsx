import React, {useState} from 'react';
import { Heading } from '@components/Typography/Heading'
import { Text } from '@components/Typography/Text'
import { Button } from '@components/Action/Button'
import ChoiceCard from '@components/Forms/ChoiceCard';
import Hotel from '@icones/hotel.svg';
import Bed from '@icones/bed.svg';
import Map from '@icones/map-location-dot.svg';
import Tent from '@icones/tent.svg';
import Tipie from '@icones/tipie.svg';
import Ustensil from '@icones/utensils.svg';

const StepTwo = ({nextStep, setEtablissement, etablissement} : {
    nextStep: Function,
    setEtablissement: Function,
    etablissement: string
}) => {
    const cardClass = "p-4 cursor-pointer flex gap-4 flex items-center border border-neutral-200 group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50"
    const iconBackground = "bg-secondary-50 group-hover:bg-tertiary-400 trans-default is-active:bg-primary-200"

    const selectEl = (event : any) => {
        setEtablissement(event.target.dataset.type)
        //event.target.classList.add('is-active')
        console.log(etablissement)
    }
    return (
        <>
            <Heading variant="display-4">Vous êtes...</Heading>
            <Text className="mt-6" color="neutral-500" weight={400} size="sm">Indiquez l’activité de votre établissement touristique.</Text>
            <div className="grid gap-6 grid-cols-2 mt-8">
                <ChoiceCard type="hostel" selectFunction={selectEl} etablissement={etablissement} className={cardClass} icon={{iconSrc: Hotel, alt: 'Hotel', iconClass: iconBackground}}>
                    <Text color="neutral-700" weight={400}>Un hôtel</Text>
                </ChoiceCard>

                <ChoiceCard type="location" selectFunction={selectEl} etablissement={etablissement} className={cardClass} icon={{iconSrc: Tent, alt: 'Location de vacance', iconClass: iconBackground }}>
                    <Text color="neutral-700" weight={400}>Une location de vacances (gîte et meublé…)</Text>
                </ChoiceCard>

                <ChoiceCard type="guestroom" selectFunction={selectEl} etablissement={etablissement} className={cardClass} icon={{iconSrc: Bed, alt: 'Chambre d`hôte', iconClass: iconBackground }}>
                    <Text color="neutral-700" weight={400}>Une chambre d’hôte</Text>
                </ChoiceCard>

                <ChoiceCard type="camping" selectFunction={selectEl} etablissement={etablissement} className={cardClass} icon={{iconSrc: Tent, alt: 'Camping', iconClass: iconBackground }}>
                    <Text color="neutral-700" weight={400}>Un camping ou un locatif de plein air</Text>
                </ChoiceCard>

                <ChoiceCard type="unsusual" selectFunction={selectEl} etablissement={etablissement} className={cardClass} icon={{iconSrc: Tipie, alt: 'Hébergement insolite', iconClass: iconBackground }}>
                    <Text color="neutral-700" weight={400}>Hébergement insolite(bulles, cabanes, tiny house…)</Text>
                </ChoiceCard>

                <ChoiceCard type="visit" selectFunction={selectEl} etablissement={etablissement} className={cardClass} icon={{iconSrc: Map, alt: 'Lieu de visite', iconClass: iconBackground }}>
                    <Text color="neutral-700" weight={400}>Un lieu de visite</Text>
                </ChoiceCard>

                <ChoiceCard type="restaurant" selectFunction={selectEl} etablissement={etablissement} className={cardClass} icon={{iconSrc: Ustensil, alt: 'Restaurant', iconClass: iconBackground }}>
                    <Text color="neutral-700" weight={400}>Un restaurant</Text>
                </ChoiceCard>                
                
            </div>
            <Button 
                size="lg" 
                className="mt-8"
                disabled={etablissement === '' ? true : false}
                icon="fa-minus"
                iconSide="left"
                onClick={ () => nextStep() }>
                    Suivant
            </Button>
        </>
    )
}

export default StepTwo