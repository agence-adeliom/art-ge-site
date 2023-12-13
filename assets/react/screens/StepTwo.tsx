import React, { useState } from 'react';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { ChoiceCard } from '@components/Fields/ChoiceCard';
import Hotel from '@icones/hotel.svg';
import Bed from '@icones/bed.svg';
import Map from '@icones/map-location-dot.svg';
import Tent from '@icones/tent.svg';
import Tipie from '@icones/tipie.svg';
import Ustensil from '@icones/utensils.svg';
import Trees from '@icones/trees.svg';
import useData from '@hooks/useReponseData/useReponseData';
import useReponseData from '@hooks/useReponseData/useReponseData';
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
    iconSrc: Tent,
    alt: 'Location de vacances',
    title: 'Une location de vacances (gîte et meublé…)',
  },
  {
    value: 3,
    iconSrc: Bed,
    alt: 'Chambre d’hôte',
    title: 'Une chambre d’hôte',
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
    alt: 'Hébergement insolite',
    title: 'Hébergement insolite(bulles, cabanes, tiny house…)',
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
    alt: 'Activité de loisir',
    title: 'Activité de loisir',
  },
  {
    value: 8,
    iconSrc: Ustensil,
    alt: 'Restaurant',
    title: 'Un restaurant',
  },
];

const StepTwo = ({ nextStep }: { nextStep: () => void }) => {
  const [etablissement, setEtablissement] = useState<number | undefined>();
  const { feedRepondant } = useReponseData();

  const onSubmit = () => {
    feedRepondant({
      typologie: etablissement,
    });
    nextStep();
  };

  return (
    <>
      <StepAnim>
        <Heading variant="display-4">Vous êtes...</Heading>
        <Text className="mt-6" color="neutral-500" weight={400} size="sm">
          Indiquez l’activité de votre établissement touristique.
        </Text>
        <div className="grid gap-6 grid-cols-2 mt-8">
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
                <Text color="neutral-700" weight={400}>
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
