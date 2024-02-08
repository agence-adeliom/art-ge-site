import React, { FunctionComponent, useState } from 'react';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { ChoiceCard } from '@components/Fields/ChoiceCard';
import Hotel from '@icones/hotel.svg';
import Bed from '@icones/bed.svg';
import Map from '@icones/map-location-dot.svg';
import Tent from '@icones/tent.svg';
import Location from '@icones/location.svg';
import Tipie from '@icones/tipie.svg';
import Ustensil from '@icones/utensils.svg';
import Trees from '@icones/trees.svg';
import { useWizard } from '@hooks/useWizard';
import { StepAnim } from '@components/Animation/Step';

const establishmentData: {
  value: number;
  iconSrc: string;
  alt: string;
  title: string;
}[] = [
  {
    value: 1,
    iconSrc: Hotel,
    alt: 'Hotel',
    title: 'Un hôtel',
  },
  {
    value: 2,
    iconSrc: Location,
    alt: 'Location de vacances',
    title: 'Une location de vacances (gîte et meublé…)',
  },
  {
    value: 3,
    iconSrc: Bed,
    alt: 'Chambre d’hôtes',
    title: 'Une chambre d’hôtes',
  },
  {
    value: 4,
    iconSrc: Tent,
    alt: 'Camping',
    title: 'Un camping ou un locatif de plein air',
  },
  {
    value: 5,
    iconSrc: Tipie,
    alt: 'Un hébergement insolite',
    title: 'Un hébergement insolite (bulles, cabanes, tiny house…)',
  },
  {
    value: 6,
    iconSrc: Map,
    alt: 'Lieu de visite',
    title: 'Un lieu de visite',
  },
  {
    value: 7,
    iconSrc: Trees,
    alt: 'Une activité de loisir',
    title: 'Une activité de loisir',
  },
  {
    value: 8,
    iconSrc: Ustensil,
    alt: 'Restaurant',
    title: 'Un restaurant',
  },
];

const StepTwo: FunctionComponent = () => {
  const { wizard, feedRepondantAndGoToNextStep, prevStep } = useWizard();
  const [etablissement, setEtablissement] = useState<number | undefined>(
    wizard?.reponse?.repondant?.typologie,
  );
  const onSubmit = () => {
    feedRepondantAndGoToNextStep({
      typologie: etablissement,
    });
  };

  return (
    <>
      <StepAnim>
        <Button
          variant="textOnly"
          icon={'fa-chevron-left'}
          iconSide="left"
          className="!w-fit"
          weight={600}
          onClick={() => prevStep()}
        >
          Retour
        </Button>
        <Heading variant="display-4" className="mt-2 lg:mt-6">
          Vous proposez...
        </Heading>
        <Text className="mt-6" color="neutral-500" weight={400} size="sm">
          Indiquez l’activité de votre établissement touristique.
        </Text>
        <div className="grid gap-4 grid-cols-1 md:grid-cols-2 mt-8">
          {establishmentData.map((item, index) => {
            return (
              <ChoiceCard
                key={index}
                isActive={etablissement === item.value}
                icon={{
                  iconSrc: item.iconSrc,
                  alt: item.alt,
                }}
                onClick={() => setEtablissement(item.value)}
              >
                <Text color="neutral-700" weight={400} size="sm">
                  {item.title}
                </Text>
              </ChoiceCard>
            );
          })}
        </div>
        <Button
          size="lg"
          className="mt-8"
          disabled={!etablissement}
          icon="fa-minus"
          iconSide="left"
          onClick={onSubmit}
        >
          Suivant
        </Button>
      </StepAnim>
    </>
  );
};

export default StepTwo;
