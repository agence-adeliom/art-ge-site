import React, { useEffect, useState } from 'react';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { YesNoCard } from '@components/Fields/YesNoCard';
import { useWizard } from '@hooks/useWizard';
import { useValidation } from '@hooks/useValidation';
import { ObjectSchema } from 'yup';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { SubmitHandler, useForm } from 'react-hook-form';
import { StepAnim } from '@components/Animation/Step';
import { Fields } from '@react/types/Fields';
import { TextInput } from '@components/Fields/TextInput';


const arrayQuestions: { text: string; id: 'restauration' | 'greenSpace' }[] = [
  {
    text: `Disposez-vous d'un espace vert, d'un espace extérieur de plus de 100m2 ?`,
    id: 'greenSpace',
  },
  {
    text: `Proposez-vous une offre de restauration (panier, pique-nique, restaurant…) ?`,
    id: 'restauration',
  },
];

interface DataFields {
  restauration: number;
  greenSpace: number;
  contact_by_bird?: string;
}

const StepThree = () => {
  const { wizard, feedRepondantAndGoToNextStep, prevStep } = useWizard();

  const { booleanNumberRequired, textOptional } = useValidation();

  const schema: ObjectSchema<DataFields> = yup.object().shape({
    restauration: booleanNumberRequired,
    greenSpace: booleanNumberRequired,
    contact_by_bird: textOptional
  });

  const {
    handleSubmit,
    control,
    setValue,
    trigger,
    formState: { isValid },
  } = useForm<DataFields>({
    resolver: yupResolver(schema),
  });

  const onSubmit: SubmitHandler<DataFields> = data => {
    feedRepondantAndGoToNextStep(data);
    getQuestions(data.restauration === 1, data.greenSpace === 1);
  };

  const getQuestions = async (isRestauration: boolean, isGreenSpace: boolean) => {
    try {
      const response = await fetch(`api/form?restauration=${isRestauration}&green_space=${isGreenSpace}`);
      const results = await response.json();
      window.localStorage.setItem('allQuestions', JSON.stringify(results));
    } catch (error) {
      console.log(error);
    }
  };

  useEffect(() => {
    arrayQuestions.forEach(item => {
      typeof wizard?.reponse?.repondant?.[item.id] === 'number' &&
        setValue(item.id, wizard?.reponse?.repondant?.[item.id] as number);
    });
    trigger();
  }, [arrayQuestions]);

  
  // check typologie in LocalStorage
  const [isRestaurant, setIsRestaurant] = useState<number | undefined>(JSON.parse(localStorage!.getItem('_etablissement')!)); 
  useEffect(() => {
    const typologie =JSON.parse(localStorage!.getItem('_wizard')!).reponse.repondant.typologie;
    //console.log(typologie)
    setIsRestaurant(typologie === 8 ? 1 : undefined)
  }, [])


  return (
    <>
      <StepAnim>
        <form onSubmit={handleSubmit(onSubmit)}>
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
                <YesNoCard name={item.id} control={control} 
                defaultValue={isRestaurant == 8 && item.id === 'restauration' ? 1 : undefined}
                ></YesNoCard>
              </div>
            );
          })}

          <TextInput
            containerClass="sr-only"
            label={'test'}
            name={'contact_by_bird'}
            type={Fields.TEXT}
            placeholder={'test'}
            control={control}
            autoCompleteChoice={false}
            defaultValue={''}
          ></TextInput>

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
