import React, { useEffect, useState } from 'react';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { YesNoCard } from '@components/Fields/YesNoCard';
import useReponseData from '@hooks/useReponseData/useReponseData';
import { useValidation } from '@hooks/useValidation';
import { ObjectSchema } from 'yup';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { SubmitHandler, useForm } from 'react-hook-form';
import { StepAnim } from '@components/Animation/Step';

const arrayQuestions = [
  {
    text: `Disposez-vous d'un espace vert, d'un espace extérieur de plus de 100m2 ?`,
    id: 'restauration',
  },
  {
    text: `Proposez-vous une offre de restauration (panier, pique-nique, restaurant…) ?`,
    id: 'greenSpace',
  },
];

interface DataFields {
  restauration: number;
  greenSpace: number;
}

const StepThree = ({ nextStep }: { nextStep: Function }) => {
  const { feedRepondant } = useReponseData();

  const { booleanNumberRequired } = useValidation();

  const schema: ObjectSchema<DataFields> = yup.object().shape({
    restauration: booleanNumberRequired,
    greenSpace: booleanNumberRequired,
  });

  const {
    handleSubmit,
    control,
    formState: { isValid },
  } = useForm<DataFields>({
    resolver: yupResolver(schema),
  });

  const onSubmit: SubmitHandler<DataFields> = data => {
    feedRepondant(data);
    getQuestions(data.greenSpace === 1);
    nextStep();
  };

  const getQuestions = async (isGreenSpace: boolean) => {
    let formAPI = 'api/form?green_space=';
    let formAPIresults = formAPI + isGreenSpace;

    try {
      const response = await fetch(formAPIresults);
      const results = await response.json();
      window.localStorage.setItem('allQuestions', JSON.stringify(results));
    } catch (error) {
      console.log(error);
    }
  };

  return (
    <>
      <StepAnim>
        <form onSubmit={handleSubmit(onSubmit)}>
          <Heading variant="display-4">Vous êtes...</Heading>
          <Text className="mt-6" color="neutral-500" weight={400} size="sm">
            Indiquez l’activité de votre établissement touristique.
          </Text>

          {arrayQuestions.map((item, index) => {
            return (
              <div className="mt-8" key={index}>
                <Text
                  className="mb-4"
                  color="neutral-700"
                  weight={400}
                  size="sm"
                >
                  {item.text}
                </Text>
                <YesNoCard name={item.id} control={control}></YesNoCard>
              </div>
            );
          })}

          <Button
            size="lg"
            className="mt-8"
            disabled={!isValid}
            icon="fa-minus"
            iconSide="left"
          >
            Suivant
          </Button>
        </form>
      </StepAnim>
    </>
  );
};

export default StepThree;
