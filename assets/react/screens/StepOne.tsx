import React, { useState } from 'react';
import { ObjectSchema } from 'yup';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { SubmitHandler, useForm } from 'react-hook-form';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { useValidation } from '@hooks/useValidation';
import { TextInput } from '@components/Fields/TextInput';
import { Checkbox } from '@components/Fields/Checkbox';
import { Fields } from '@react/types/Fields';

interface DataFields {
  firstname: string;
  lastname: string;
  tel: string;
  email: string;
}

const StepOne = ({nextStep} : {
  nextStep: Function
}) => {
  const { textRequired, phoneRequired, emailRequired, consentRequired } =
    useValidation();

  const schema: ObjectSchema<DataFields> = yup.object().shape({
    firstname: textRequired,
    lastname: textRequired,
    tel: phoneRequired,
    email: emailRequired,
    legal: consentRequired,
  });

  const {
    handleSubmit,
    control,
    formState: { isValid },
  } = useForm<DataFields>({
    mode: 'onBlur',
    resolver: yupResolver(schema),
  });

  const onSubmit: SubmitHandler<DataFields> = data => {
    console.log('data', data);
  };

  return (
    <>
      <form onSubmit={handleSubmit(onSubmit)}>
        <Heading variant="display-4">Pour commencer</Heading>
        <Text className="mt-6" color="neutral-500" weight={400} size="sm">
          Renseignez ces informations afin que nous puissions vous identifier.
        </Text>
        <div className="flex-wrap md:flex-nowrap flex gap-6 w-full mt-8">
          <TextInput
            containerClass="w-full md:w-1/2"
            label={'Prénom'}
            name="firstname"
            type={Fields.TEXT}
            placeholder={'Ex : Julie'}
            control={control}
          ></TextInput>
          <TextInput
            containerClass="w-full md:w-1/2"
            label={'Nom'}
            name="lastname"
            type={Fields.TEXT}
            placeholder={'Ex : Dupont'}
            control={control}
          ></TextInput>
        </div>
        <div className="flex flex-wrap md:flex-nowrap gap-6 w-full mt-8">
          <TextInput
            containerClass="w-full md:w-1/2"
            label={'Téléphone'}
            name={'tel'}
            type={Fields.PHONE}
            placeholder={'Ex : 0612345678'}
            control={control}
          ></TextInput>
          <TextInput
            containerClass="w-full md:w-1/2"
            label={'Email'}
            name="email"
            type={Fields.EMAIL}
            placeholder={'Ex : julie.dupont@mail.com'}
            control={control}
          ></TextInput>
        </div>

        <Checkbox
          containerClass="flex flex-row-reverse items-start gap-4 mt-8"
          children={
            <Text weight={400} color="neutral-800">
                J’accepte que mes données soient transmises à l’ART GE et à ses
                partenaires. Pour en savoir plus, consultez la{' '}
                <a href="#" className="classic-link">
                  politique de confidentialité
                </a>
            </Text>
          }
          id="legal"
          name="legal"
          control={control}
        ></Checkbox>

        <Button
          size="lg"
          className="mt-8"
          icon="fa-minus"
          iconSide="left"
          disabled={!isValid}
          onClick={(event) => {event.preventDefault(); nextStep()} }
          type="submit"
        >
          Suivant
        </Button>
      </form>
    </>
  );
};

export default StepOne;
